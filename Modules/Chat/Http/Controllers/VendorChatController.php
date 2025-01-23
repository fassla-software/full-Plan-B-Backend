<?php

namespace Modules\Chat\Http\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use JetBrains\PhpStorm\NoReturn;
use Modules\Chat\Entities\LiveChat;
use Modules\Chat\Http\Requests\FetchChatRecordRequest;
use Modules\Chat\Http\Requests\FetchVendorChatRecordRequest;
use Modules\Chat\Http\Requests\MessageSendRequest;
use Modules\Chat\Services\UserChatService;

class VendorChatController extends Controller
{
    public function index()
    {
        $vendor_chat_list = LiveChat::with("vendor","user")->withCount("vendor_unseen_msg","user_unseen_msg")
            ->where("vendor_id", auth("vendor")->id())
            ->orderByDesc('vendor_unseen_msg_count')
            ->get();

        $arr = "";

        foreach($vendor_chat_list->pluck("user.id") as $id){
            $arr .= "user_id_". $id .": false,";
        }

        $arr = rtrim($arr,",");

        return view("chat::vendor.index",compact('vendor_chat_list','arr'));
    }

    public function fetch_chat_record(FetchChatRecordRequest $request){
        $data = $request->validated();
        $data = UserChatService::fetch($data["user_id"],$data["vendor_id"],from: 1);
        $currentUserType = "vendor";

        $body = view("chat::vendor.message-body", compact('data'))->render();
        $header = view("chat::vendor.message-header", compact('data'))->render();

        return response()->json([
            "body" => $body,
            "header" => $header
        ]);
    }

    /**
     * @throws Exception
     */
    #[NoReturn]
    public function message_send(MessageSendRequest $request){
        return UserChatService::send($request->user_id, auth('vendor')->id(), $request->message,2, $request->file, $request->product_id ?? null);
    }
}
