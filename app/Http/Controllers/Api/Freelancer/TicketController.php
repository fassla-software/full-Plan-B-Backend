<?php

namespace App\Http\Controllers\Api\Freelancer;

use App\Http\Controllers\Controller;
use App\Mail\BasicMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Modules\SupportTicket\Entities\ChatMessage;
use Modules\SupportTicket\Entities\Department;
use Modules\SupportTicket\Entities\Ticket;

class TicketController extends Controller
{
    public function all_department()
    {
        $departments = Department::select(['id','name','status'])->where('status',1)->get();
        if($departments){
            return response()->json([
                'departments' => $departments,
            ]);
        }
        return response()->json(['msg' => __('no departments found.')]);
    }

    public function create_ticket(Request $request)
    {
        $request->validate([
            'title'=> 'required|min:10|max:191',
            'department'=> 'required|max:191',
            'priority'=> 'required|max:191',
            'description'=> 'required',
        ]);

        // create ticket for specific user
        $ticket = Ticket::create([
            'department_id'=>$request->department,
            'freelancer_id'=> auth('sanctum')->user()->id,
            'title'=>$request->title,
            'priority'=>$request->priority,
            'description'=>$request->description,
        ]);

        //Email to admin
        try {
            $message = get_static_option('support_ticket_message') ?? __('Support Ticket Message');
            $message = str_replace(["@name","@ticket_id"],[__('Admin'),$ticket->id], $message);
            Mail::to(get_static_option('site_global_email'))->send(new BasicMail([
                'subject' => get_static_option('support_ticket_subject') ?? __('Support Ticket'),
                'message' => $message
            ]));
        } catch (\Exception $e) {}

        if($ticket){
            // send notification to admin
            notificationToAdmin($ticket->id,auth('sanctum')->user()->id,'Ticket',__('New Support Ticket'));
            return response()->json(['msg' => __('New Ticket Successfully Added')]);
        }else{
            return response()->json(['msg' => __('Ticket Create Failed')]);
        }
    }

    public function all_ticket()
    {
        $tickets = Ticket::where('freelancer_id',auth('sanctum')->user()->id)->latest()->paginate(10)->withQueryString();
        if($tickets){
            return response()->json([
                'tickets' => $tickets,
            ]);
        }
        return response()->json(['msg' => __('no ticket found.')]);
    }

    public function ticket_details($id=null)
    {
        $ticket_details = Ticket::with('freelancer','message')->where('id',$id)
            ->where('freelancer_id',auth('sanctum')->user()->id)
            ->first();

        if($ticket_details){
            return response()->json([
                'ticket_details' => $ticket_details,
                'attachment_path' => asset('assets/uploads/ticket/chat-messages/'),
            ]);
        }
        return response()->json(['msg' => __('no ticket found.')]);
    }

    public function all_message($id=null)
    {
        $all_message = ChatMessage::where('ticket_id',$id)
            ->paginate(20)->withQueryString();

        $all_message->transform(function ($msg){
            if($msg->attachment && in_array(Storage::getDefaultDriver(), ['s3', 'cloudFlareR2', 'wasabi'])){
                $msg->cloud_link = render_frontend_cloud_image_if_module_exists('ticket/chat-messages/'.$msg->attachment, load_from: $msg->load_from);
            } else{
                $msg->cloud_link = null;
            }
            return $msg;
        });

        if($all_message){
            return response()->json([
                'all_message' => $all_message,
                'attachment_path' => asset('assets/uploads/ticket/chat-messages/'),
                'storage_driver' => Storage::getDefaultDriver() ?? '',
            ]);
        }
        return response()->json(['msg' => __('No message found.')]);
    }

    public function ticket_message_send(Request $request)
    {
        // freelancer to admin ticket chat
        $request->validate([
            'ticket_id'=> 'required|integer',
            'message'=> 'required|max:10000',
        ]);

        $upload_folder = 'ticket/chat-messages';
        $storage_driver = Storage::getDefaultDriver();

        if($attachment = $request->file('attachment')) {
            $imageName = time() . '-' . uniqid() . '.' . $attachment->getClientOriginalExtension();
            $extensions = array('png', 'jpg', 'jpeg', 'gif', 'svg');
            if (cloudStorageExist() && in_array(Storage::getDefaultDriver(), ['s3', 'cloudFlareR2', 'wasabi'])) {
                if(in_array($attachment->getClientOriginalExtension(), $extensions)){
                    add_frontend_cloud_image_if_module_exists($upload_folder, $attachment, $imageName,'public');
                }else{
                    add_frontend_cloud_image_if_module_exists($upload_folder, $attachment, $imageName,'public');
                }
            }else{
                $attachment->move('assets/uploads/ticket/chat-messages',$imageName);
            }
        }

        $send_message = ChatMessage::create([
            'ticket_id'=>$request->ticket_id,
            'message'=>$request->message,
            'attachment'=>$imageName ?? '',
            'notify'=>$request->email_notify,
            'type'=>'freelancer',
            'load_from' => in_array($storage_driver,['CustomUploader']) ? 0 : 1,
        ]);

        if($request->email_notify == 'on'){
            // send notification to admin
            notificationToAdmin($request->ticket_id,auth('sanctum')->user()->id,'Ticket',__('Ticket New Message'));
            //Email to admin
            try {
                $message = get_static_option('support_ticket_message_email_message') ?? __('Support Ticket Message Email Notify');
                $message = str_replace(["@name","@ticket_id"],[__('Admin') ,$request->ticket_id], $message);
                Mail::to(get_static_option('site_global_email'))->send(new BasicMail([
                    'subject' => get_static_option('support_ticket_message_email_subject') ?? __('Support Ticket Message Email'),
                    'message' => $message
                ]));
            } catch (\Exception $e) {}
        }

        if($send_message){
            return response()->json([
                'msg' => __('Message Successfully Send'),
                'message' => $request->message,
                'attachment ' => $imageName ?? '',
            ]);
        }else{
            return response()->json(['msg' => __('Message Send Failed')]);
        }
    }
}
