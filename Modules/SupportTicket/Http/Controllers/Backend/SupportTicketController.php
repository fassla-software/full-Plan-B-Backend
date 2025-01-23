<?php

namespace Modules\SupportTicket\Http\Controllers\Backend;

use App\Mail\BasicMail;
use App\Models\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Modules\SupportTicket\Entities\ChatMessage;
use Modules\SupportTicket\Entities\Department;
use Modules\SupportTicket\Entities\Ticket;

class SupportTicketController extends Controller
{
    //show and add ticket
    public function ticket(Request $request)
    {
        if($request->isMethod('post')){
            $user = User::select('id','user_type','first_name','last_name','email')->where('id',$request->user)->first();
            $request->validate([
                'title'=> 'required|max:191',
                'department'=> 'required|max:191',
                'priority'=> 'required|max:191',
                'user'=> 'required',
                'description'=> 'required',
            ]);

            // create ticket for specific user
           $ticket = Ticket::create([
                'department_id'=>$request->department,
                'admin_id'=>Auth::guard('admin')->check() ? Auth::guard('admin')->user()->id : NULL,
                'client_id'=> $user->user_type == 1 ? $request->user : NULL,
                'freelancer_id'=> $user->user_type == 2 ? $request->user : NULL,
                'title'=>$request->title,
                'priority'=>$request->priority,
                'description'=>$request->description,
            ]);

           // send notification to user
            $user->user_type == 1
                ? client_notification($ticket->id,$user->id,'Ticket',__('New Support Ticket'))
                : freelancer_notification($ticket->id,$user->id,'Ticket',__('New Support Ticket'));

            //Email to user
            try {
                $message = get_static_option('support_ticket_message') ?? __('Support Ticket Message');
                $message = str_replace(["@name","@ticket_id"],[$user->fullname,$ticket->id], $message);
                Mail::to($user->email)->send(new BasicMail([
                    'subject' => get_static_option('support_ticket_subject') ?? __('Support Ticket'),
                    'message' => $message
                ]));
            } catch (\Exception $e) {}

            return back()->with(toastr_success(__('New Ticket Successfully Added')));
        }
        $tickets = Ticket::whereHas('freelancer')->orwhereHas('client')->latest()->paginate(10);
        $departments = Department::all();
        $users = User::select(['id','first_name','last_name','username'])->get();
        return view('supportticket::backend.ticket.tickets',compact(['tickets','departments','users']));
    }

    //paginate
    public function paginate(Request $request)
    {
        if($request->ajax()){
            if(empty($request->string_search)){
                $tickets = Ticket::whereHas('freelancer')->orwhereHas('client')->latest()->paginate(10);
            }else{
                $tickets = Ticket::where(function($q) use ($request){
                    $q->orWhere('id', $request->string_search)
                        ->orWhere('priority',$request->string_search)
                        ->orWhere('status',$request->string_search);
                })->latest()->paginate(10);
            }
            return $tickets->total() >= 1
                ? view('supportticket::freelancer.search-result', compact('tickets'))->render()
                : response()->json(['status'=>__('nothing')]);
        }
    }

    //search
    public function search_ticket(Request $request)
    {
        $tickets = Ticket::whereHas('freelancer')->orwhereHas('client')->where(function($q) use ($request){
            $q->orWhere('id','LIKE', "%".strip_tags($request->string_search)."%")
                ->orWhere('priority','LIKE',"%".strip_tags($request->string_search)."%")
                ->orWhere('status','LIKE',"%".strip_tags($request->string_search)."%");
        })->latest()->paginate(10);

        return $tickets->total() >= 1
            ? view('supportticket::freelancer.search-result', compact('tickets'))->render()
            : response()->json(['status'=>__('nothing')]);
    }

    //status change
    public function change_status($id)
    {
        $ticket = Ticket::select('status')->where('id',$id)->first();
        $ticket->status == 'open' ? $status = 'close' : $status = 'open';
        Ticket::where('id',$id)->update(['status'=>$status]);
        return redirect()->back()->with(toastr_success(__('Status Successfully Changed')));
    }

    //delete ticket
    public function delete_ticket($id)
    {
        ChatMessage::where('ticket_id',$id)->delete();
        Ticket::find($id)->delete();
        return redirect()->back()->with(toastr_error(__('Ticket Successfully Deleted')));
    }

    //delete bulk action
    public function bulk_action(Request $request){
        Ticket::whereIn('id',$request->ids)->delete();
        return redirect()->back()->with(toastr_error(__('Selected Ticket Successfully Deleted')));
    }

    public function ticket_details(Request $request, $id){
        $ticket_details = Ticket::with('client','freelancer','message','get_ticket_latest_message')->where('id',$id)->first();
        if($request->isMethod('post')){
            // freelancer to admin ticket chat
            if(empty($request->attachment) && empty($request->message)){
                $request->validate([
                    'message'=> 'required|max:10000',
                ]);
            }

            if(!empty($request->attachment) || empty($request->message)){
                $request->validate([
                    'attachment'=> 'mimes:jpg,jpeg,png,gif,pdf,svg,xlsx,xls,txt',
                ]);
            }

            $upload_folder = 'ticket/chat-messages';
            $storage_driver = Storage::getDefaultDriver();

            if($attachment = $request->file('attachment')){
                $imageName = time().'-'.uniqid().'.'.$attachment->getClientOriginalExtension();
                $extensions = array('png','jpg','jpeg','gif','svg');

                if (cloudStorageExist() && in_array(Storage::getDefaultDriver(), ['s3', 'cloudFlareR2', 'wasabi'])) {
                    if(in_array($attachment->getClientOriginalExtension(), $extensions)){
                        add_frontend_cloud_image_if_module_exists($upload_folder, $attachment, $imageName,'public');
                    }else{
                        add_frontend_cloud_image_if_module_exists($upload_folder, $attachment, $imageName,'public');
                    }
                }else{
                    if(in_array($attachment->getClientOriginalExtension(), $extensions)){
                        $resize_full_image = Image::make($request->attachment)
                            ->resize(300, 300);
                        $resize_full_image->save('assets/uploads/ticket/chat-messages' .'/'. $imageName);
                    }else{
                        $attachment->move('assets/uploads/ticket/chat-messages',$imageName);
                    }
                }
            }
            ChatMessage::create([
                'ticket_id'=>$id,
                'message'=>$request->message,
                'attachment'=>$imageName ?? '',
                'notify'=>$request->email_notify,
                'type'=>'admin',
                'load_from' => in_array($storage_driver,['CustomUploader']) ? 0 : 1, //added for cloud storage 0=local 1=cloud
            ]);

                // send notification to user
                $ticket_details->client_id !== null
                ? client_notification($ticket_details->id,$ticket_details->client_id,'Ticket Update',__('Ticket updates available'))
                : freelancer_notification($ticket_details->id,$ticket_details->freelancer_id,'Ticket Update',__('Ticket updates available'));

            if($request->email_notify == 'on'){
                //Email to user
                try {
                    $message = get_static_option('support_ticket_message_email_message') ?? __('Support Ticket Message Email Notify');
                    $message = str_replace(["@name","@ticket_id"],[$ticket_details->client?->fullname ?? $ticket_details->freelancer?->fullname ,$id], $message);
                    Mail::to($ticket_details->client?->email ?? $ticket_details->freelancer?->email)->send(new BasicMail([
                        'subject' => get_static_option('support_ticket_message_email_subject') ?? __('Support Ticket Message Email'),
                        'message' => $message
                    ]));
                } catch (\Exception $e) {}
            }
            return back();
        }
        return !empty($ticket_details) ? view('supportticket::backend.ticket.details',compact('ticket_details')) : back();
    }

}
