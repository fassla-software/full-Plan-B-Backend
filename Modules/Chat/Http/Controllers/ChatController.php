<?php

namespace Modules\Chat\Http\Controllers;

use App\Jobs\SendEmailJob;
use App\Mail\BasicMail;
use App\Models\Order;
use App\Models\User;
use Cache;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Mail;
use JetBrains\PhpStorm\NoReturn;
use Modules\Chat\Entities\LiveChat;
use Modules\Chat\Http\Requests\FetchChatRecordRequest;
use Modules\Chat\Http\Requests\MessageSendRequest;
use Modules\Chat\Services\UserChatService;
use Modules\SecurityManage\Entities\Word;

class ChatController extends Controller
{
    public function live_chat()
    {
        $client_chat_list = LiveChat::with("client","freelancer")
            ->whereHas('freelancer')
            ->withCount("client_unseen_msg","freelancer_unseen_msg")
            ->where("client_id", auth("web")->id())
            ->orderByDesc('client_unseen_msg_count')
            ->get();

        $arr = "";
        foreach($client_chat_list->pluck("freelancer_id") as $id){
            $arr .= "freelancer_id_". $id .": false,";
        }
        $arr = rtrim($arr,",");
        return view("chat::client.index",compact('client_chat_list','arr'));
    }

    public function fetch_chat_record(FetchChatRecordRequest $request){
        $data = $request->validated();
        $data = UserChatService::fetch($data["freelancer_id"],$data["client_id"],from: 1);
        $currentUserType = "freelancer";

        $body = view("chat::client.message-body", compact('data'))->render();
        $header = view("chat::client.message-header", compact('data'))->render();

        return response()->json([
            "body" => $body,
            "header" => $header,
            "allow_load_more" => $data->allow_load_more ?? false,
        ]);
    }

    #[NoReturn]
    public function message_send(Request $request){
        $order_details = Order::where('id',$request->order_id ?? 0)->first();

        # check livechat configuration value are exist or not
        if(empty(env("PUSHER_APP_ID")) && empty(env("PUSHER_APP_KEY")) && empty(env("PUSHER_APP_SECRET")) && empty(env("PUSHER_HOST"))){
            return back()->with(toastr_error(__("Please configure your pusher credentials")));
        }

        //prevent restricted word for chat
        if(moduleExists('SecurityManage')) {
            $message = $request->message;
            $restrictedWords = Word::where('status', 'active')->pluck('word')->toArray();

            $matchedWords = array_filter($restrictedWords, function($word) use ($message) {
                return strpos($message, $word) !== false;
            });

            if (count($matchedWords) > 0) {
                return false;
            }
        }

        if($order_details?->is_project_job != 'offer'){

            try{
                //: send message
                $message_send = UserChatService::send(
                    auth('web')->id(),
                    $request->freelancer_id,
                    $request->message,1,
                    $request->file,
                    (int) ($request->project_id ?? $request->job_id),
                    $order_details->is_project_job ?? $request->type,
                    (int) ($request->proposal_id ?? 0),
                    $request->interview_message ?? '',
                );

                if(get_static_option('chat_email_enable_disable') == 'enable'){
                    if($request->freelancer_id){
                        if (!Cache::has('user_is_online_' . $request->freelancer_id)){
                            $user = User::select('id', 'email', 'check_online_status')->where('id', $request->freelancer_id)->first();
                            try {
                                Mail::to($user->email)->send(new BasicMail([
                                    'subject' =>  __('Chat Email'),
                                    'message' => __('You have a new chat message. Please check')
                                ]));
                            }
                            catch (\Exception $e) {}
                        }

                    }
                }

                if($request->from === 'chatbox'){
                    return $message_send;
                }
            }catch(\RuntimeException $e){
                return back()->with(toastr_warning($e->getMessage()));
            }
        }

        return redirect()->route('client.live.chat',[
            'freelancer_id'=>$request->freelancer_id
        ]);
    }
}
