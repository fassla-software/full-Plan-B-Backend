<?php

namespace Modules\SupportTicket\Http\Controllers\Freelancer;

use App\Helper\LogActivity;
use App\Mail\BasicMail;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Modules\SupportTicket\Entities\ChatMessage;
use Modules\SupportTicket\Entities\Department;
use Modules\SupportTicket\Entities\Ticket;

class FreelancerTicketController extends Controller
{
    //all tickets
    public function ticket(Request $request)
    {
        if($request->isMethod('post')){
            $request->validate([
                'title'=> 'required|min:10|max:191',
                'department'=> 'required|max:191',
                'priority'=> 'required|max:191',
                'description'=> 'required',
            ]);

            // create ticket for specific user
            $ticket = Ticket::create([
                'department_id'=>$request->department,
                'freelancer_id'=> auth()->user()->id,
                'title'=>$request->title,
                'priority'=>$request->priority,
                'description'=>$request->description,
            ]);

            //security manage
            if(moduleExists('SecurityManage')){
                LogActivity::addToLog('Support ticket create','Freelancer');
            }

            // send notification to admin
             notificationToAdmin($ticket->id,auth()->user()->id,'Ticket',__('New Support Ticket'));
            //Email to admin
            try {
                $message = get_static_option('support_ticket_message') ?? __('Support Ticket Message');
                $message = str_replace(["@name","@ticket_id"],[__('Admin'),$ticket->id], $message);
                Mail::to(get_static_option('site_global_email'))->send(new BasicMail([
                    'subject' => get_static_option('support_ticket_subject') ?? __('Support Ticket'),
                    'message' => $message
                ]));
            } catch (\Exception $e) {}

            return back()->with(toastr_success(__('New Ticket Successfully Added')));
        }

        $departments = Department::where('status',1)->get();
        $tickets = Ticket::where('freelancer_id',auth()->user()->id)->latest()->paginate(10);
        return view('supportticket::freelancer.tickets',compact('tickets','departments'));
    }

    //paginate
    public function paginate(Request $request)
    {
       if($request->ajax()){
           if(empty($request->string_search)){
               $tickets = Ticket::where('freelancer_id',auth()->user()->id)->latest()->paginate(10);
           }else{
               $tickets = Ticket::where('freelancer_id',auth()->user()->id)->where(function($q) use ($request){
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
        $tickets = Ticket::where('freelancer_id',auth()->user()->id)->where(function($q) use ($request){
            $q->orWhere('id','LIKE', "%".strip_tags($request->string_search)."%")
                ->orWhere('priority','LIKE',"%".strip_tags($request->string_search)."%")
                ->orWhere('status','LIKE',"%".strip_tags($request->string_search)."%");
        })->latest()->paginate(10);

        return $tickets->total() >= 1
            ? view('supportticket::freelancer.search-result', compact('tickets'))->render()
            : response()->json(['status'=>__('nothing')]);
    }

    //ticket details and chat
    public function ticket_details(Request $request, $id){
        $ticket_details = Ticket::with(['freelancer','message','get_ticket_latest_message'])
            ->where('id',$id)
            ->where('freelancer_id',auth()->user()->id)
            ->first();
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
                'type'=>'freelancer',
                'load_from' => in_array($storage_driver,['CustomUploader']) ? 0 : 1, //added for cloud storage 0=local 1=cloud
            ]);

            // send notification to admin
            notificationToAdmin($id,$ticket_details?->freelancer?->id,'Ticket',__('Ticket New Message'));

            if($request->email_notify == 'on'){
                //Email to admin
                try {
                    $message = get_static_option('support_ticket_message_email_message') ?? __('Support Ticket Message Email Notify');
                    $message = str_replace(["@name","@ticket_id"],[__('Admin') ,$id], $message);
                    Mail::to(get_static_option('site_global_email'))->send(new BasicMail([
                        'subject' => get_static_option('support_ticket_message_email_subject') ?? __('Support Ticket Message Email'),
                        'message' => $message
                    ]));
                } catch (\Exception $e) {}
            }

            return back();
        }
        return !empty($ticket_details) ? view('supportticket::freelancer.details',compact('ticket_details')) : back();
    }
}
