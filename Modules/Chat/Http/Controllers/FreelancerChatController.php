<?php

namespace Modules\Chat\Http\Controllers;

use App\Jobs\SendEmailJob;
use App\Mail\BasicMail;
use App\Models\Order;
use App\Models\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use JetBrains\PhpStorm\NoReturn;
use Modules\Chat\Entities\LiveChat;
use Modules\Chat\Http\Requests\FetchChatRecordRequest;
use Modules\Chat\Services\UserChatService;
use Modules\SecurityManage\Entities\Word;

class FreelancerChatController extends Controller
{
    public function live_chat()
    {
        $freelancer_chat_list = LiveChat::with("client","freelancer")
            ->whereHas('client')
            ->withCount("client_unseen_msg","freelancer_unseen_msg")
            ->where("freelancer_id", auth("web")->id())
            ->orderByDesc('freelancer_unseen_msg_count')
            ->get();

        $arr = "";
        foreach($freelancer_chat_list->pluck("client_id") as $id){
            $arr .= "client_id_". $id .": false,";
        }

        $arr = rtrim($arr,",");
        return view("chat::freelancer.index",compact('freelancer_chat_list','arr'));
    }

    public function fetch_chat_record(FetchChatRecordRequest $request){
        $data = $request->validated();
        $data = UserChatService::fetch($data["freelancer_id"],$data["client_id"],from: 2);

        $body = view("chat::freelancer.message-body", compact('data'))->render();
        $header = view("chat::freelancer.message-header", compact('data'))->render();

        return response()->json([
            "body" => $body,
            "header" => $header,
            "allow_load_more" => $data->allow_load_more ?? false,
        ]);
    }

    #[NoReturn]
    public function message_send(Request $request){
        $order_details = Order::where('id',$request->order_id ?? 0)->first();

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
                    $request->client_id,
                    auth('web')->id(),
                    $request->message,2,
                    $request->file,
                    $request->project_id ?? null,
                    $order_details->is_project_job ?? null);

                if(get_static_option('chat_email_enable_disable') == 'enable'){
                    if($request->client_id){
                        if (!Cache::has('user_is_online_' . $request->client_id)){
                            $user = User::select('id', 'email', 'check_online_status')->where('id', $request->client_id)->first();
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
            }
            catch(\RuntimeException $e){
                return back()->with(toastr_warning($e->getMessage()));
            }
        }

        return redirect()->route('freelancer.live.chat',[
            'client_id'=>$request->client_id
        ]);
    }
}
