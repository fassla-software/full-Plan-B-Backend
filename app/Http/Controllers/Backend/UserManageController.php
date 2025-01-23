<?php

namespace App\Http\Controllers\Backend;

use App\Helper\LogActivity;
use App\Http\Controllers\Controller;
use App\Mail\BasicMail;
use App\Models\Bookmark;
use App\Models\ClientNotification;
use App\Models\Feedback;
use App\Models\FreelancerNotification;
use App\Models\IdentityVerification;
use App\Models\IndividualCommissionSetting;
use App\Models\JobHistory;
use App\Models\JobPost;
use App\Models\JobPostSkill;
use App\Models\JobPostSubCategory;
use App\Models\JobProposal;
use App\Models\Order;
use App\Models\OrderDeclineHistory;
use App\Models\OrderDeclineWalletHistory;
use App\Models\OrderMilestone;
use App\Models\OrderRequestRevision;
use App\Models\OrderSubmitHistory;
use App\Models\Portfolio;
use App\Models\Project;
use App\Models\ProjectAttribute;
use App\Models\ProjectHistory;
use App\Models\ProjectSubCategory;
use App\Models\Report;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Modules\Chat\Entities\LiveChat;
use Modules\Chat\Entities\LiveChatMessage;
use Modules\Chat\Entities\Offer;
use Modules\Chat\Entities\OfferMilestone;
use Modules\SupportTicket\Entities\ChatMessage;
use Modules\SupportTicket\Entities\Ticket;
use Modules\Wallet\Entities\Wallet;

class UserManageController extends Controller
{
    //add user
    public function add_user(Request $request)
    {
        if($request->isMethod('post')){
            $request->validate([
                'first_name' => 'required|max:191',
                'last_name' => 'required|max:191',
                'email' => 'required|email|unique:users|max:191',
                'username' => 'required|unique:users|max:191',
                'phone' => 'required|unique:users|max:191',
                'password' => 'required|min:6|max:191|confirmed',
                'user_type' => 'required',
            ]);

            $email_verify_tokn = sprintf("%d", random_int(123456, 999999));
            $user = User::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'username' => $request->username,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
                'user_type' => $request->user_type,
                'terms_conditions' =>1,
                'email_verify_token'=> $email_verify_tokn,
                'is_email_verified'=> 1,
            ]);

            Wallet::create([
                'user_id' => $user->id,
                'balance' => 0,
                'remaining_balance' => 0,
                'withdraw_amount' => 0,
                'status' => 1
            ]);

            //security manage
            if(moduleExists('SecurityManage')){
                LogActivity::addToLog('User added by admin','Admin');
            }

            return back()->with(toastr_success(__('User Successfully Created')));
        }
        return view('backend.pages.user.new-user.add-new-user');
    }

    public function send_email_to_all_registered_users(Request $request)
    {
        if($request->isMethod('post')){
            $request->validate([
                'subject' => 'required|max:150',
                'message' => 'required',
            ]);

            $all_users = User::select('email')->where('is_email_verified',1)->get();
            foreach ($all_users as $user){
                try {
                    Mail::to($user->email)->send(new BasicMail([
                        'subject' => $request->subject,
                        'message' => $request->message
                    ]));

                }catch (\Exception $e){}
            }
            return back()->with(toastr_success(__('Email Successfully Send')));
        }
        return view ('backend.pages.user.verified-users.verified-users');
    }

    //all client
    public function all_clients()
    {
        $all_users = User::with('identity_verify')->where('user_type',1)->latest()->paginate(10);
        return view('backend.pages.user.clients.all-clients',compact('all_users'));
    }
    // client pagination
    function client_pagination(Request $request)
    {
        if($request->ajax()){
            $all_users = User::with(['identity_verify'])->where('user_type',1)->latest()->paginate(10);
            return view('backend.pages.user.clients.search-result',compact('all_users'));
        }
    }

    // search client
    public function search_client(Request $request)
    {
        $all_users= User::where('user_type',1)->where(function($q) use($request){
                $q->where('first_name', 'LIKE', "%". strip_tags($request->string_search) ."%")
                ->orWhere('last_name', 'LIKE', "%". strip_tags($request->string_search) ."%")
                ->orWhere('email', 'LIKE', "%". strip_tags($request->string_search) ."%")
                ->orWhere('phone', 'LIKE', "%". strip_tags($request->string_search) ."%");
        })->paginate(10);
        return $all_users->total() >= 1 ? view('backend.pages.user.clients.search-result', compact('all_users'))->render() : response()->json(['status'=>__('nothing')]);
    }

    //all freelancer
    public function all_freelancers()
    {
        $all_users = User::with(['identity_verify'])->where('user_type',2)->latest()->paginate(10);
        return view('backend.pages.user.all-users',compact('all_users'));
    }

    // freelancer pagination
    function freelancer_pagination(Request $request)
    {
        if($request->ajax()){
            $all_users = User::with(['identity_verify'])->where('user_type',2)->latest()->paginate(10);
            return view('backend.pages.user.search-result', compact('all_users'))->render();
        }
    }

    // search freelancer
    public function search_freelancer(Request $request)
    {
        $all_users= User::where('user_type',2)->where(function($q) use($request){
            $q->where('first_name', 'LIKE', "%". strip_tags($request->string_search) ."%")
                ->orWhere('last_name', 'LIKE', "%". strip_tags($request->string_search) ."%")
                ->orWhere('email', 'LIKE', "%". strip_tags($request->string_search) ."%")
                ->orWhere('phone', 'LIKE', "%". strip_tags($request->string_search) ."%");
        })->paginate(10);
        return $all_users->total() >= 1 ? view('backend.pages.user.search-result', compact('all_users'))->render() : response()->json(['status'=>__('nothing')]);
    }

    //update user info with username
    public function edit_info(Request $request)
    {
        $request->validate([
            'edit_first_name'=>'required',
            'edit_last_name'=>'required',
            'edit_username'=>'required|max:191|unique:users,username,'.$request->edit_user_id,
            'edit_email'=>'required|max:191|unique:users,email,'.$request->edit_user_id,
            'edit_phone'=>'required|max:191|unique:users,phone,'.$request->edit_user_id,
        ]);
        User::where('id',$request->edit_user_id)->update([
            'first_name'=>$request->edit_first_name,
            'last_name'=>$request->edit_last_name,
            'username'=>$request->edit_username,
            'email'=>$request->edit_email,
            'phone'=>$request->edit_phone,
            'hourly_rate'=>$request->edit_hourly_rate ?? 0,
            'country_id'=>$request->edit_country,
            'state_id'=>$request->edit_state,
            'city_id'=>$request->edit_city,
        ]);

        //security manage
        if(moduleExists('SecurityManage')){
            LogActivity::addToLog('User edit by admin','Admin');
        }

        try {
            $message = get_static_option('user_info_update_message') ?? __('Your information successfully updated');
            $message = str_replace(["@name","@username","@email"],[$request->edit_first_name.' '.$request->edit_last_name, $request->edit_username, $request->edit_email], $message);
            Mail::to($request->edit_email)->send(new BasicMail([
                'subject' => get_static_option('user_info_update_subject') ?? __('User Info Update Email'),
                'message' => $message
            ]));
        }
        catch (\Exception $e) {
        }
        toastr_success(__('User Info Successfully Updated'));
        return back();
    }

    // password change
    public function change_password(Request $request)
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'password' => 'required|min:6',
                'confirm_password' => 'required|min:6',
            ]);
            if ($request->password === $request->confirm_password) {
                $user = User::select(['email','first_name','last_name'])->first();
                User::where('id', $request->user_id)->update(['password' => Hash::make($request->password)]);

                //security manage
                if(moduleExists('SecurityManage')){
                    LogActivity::addToLog('User password change by admin','Admin');
                }

                try {
                    $message = get_static_option('user_password_change_message') ?? __('Your password has been changed');
                    $message = str_replace(["@name","@password"],[$user->first_name.' '.$user->last_name, $request->password], $message);
                    Mail::to($user->email)->send(new BasicMail([
                        'subject' => get_static_option('user_password_change_subject') ?? __('User Password Change Email'),
                        'message' => $message
                    ]));
                }
                catch (\Exception $e) {
                }
                return response()->json(['status'=>__('ok')]);
            }
            return response()->json(['status'=>__('not_match')]);
        }
    }

    //user identity details
    public function identity_details(Request $request)
    {
        $user_details = User::select(['id','first_name','last_name','email','phone','username','user_type','image','hourly_rate','country_id','state_id','city_id',])->where('id',$request->user_id)->first();
        $user_identity_details = IdentityVerification::where('user_id',$request->user_id)->first();
        if(!empty($user_details) || !empty($user_identity_details)){
            return view('backend.pages.user.profile-and-identity-compare', compact('user_details','user_identity_details'))->render();
        }else{
            return response()->json(['status'=>__('nothing')]);
        }
    }

    //user identity verify status change
    public function identity_verify_status(Request $request)
    {
        $user = User::where('id',$request->user_id)->first();
        $user_status = $user->user_verified_status==1 ? 0 : 1;
        User::where('id',$request->user_id)->update([
            'user_verified_status'=>$user_status,
        ]);
        if($user->user_verified_status==0){
            try {
                $message = get_static_option('user_identity_verify_confirm_message') ?? __('Your identity verification successfully done');
                $message = str_replace(["@name","@username","@email"],[$user->first_name.' '.$user->last_name, $user->username, $user->email], $message);
                Mail::to($user->email)->send(new BasicMail([
                    'subject' => get_static_option('user_identity_verify_confirm_subject') ?? __('User Identity Verify Confirm'),
                    'message' => $message
                ]));
            }
            catch (\Exception $e) {

            }
        }else{
            try {
                $message = get_static_option('user_identity_re_verify_message') ?? __('Your identity need to reverification for the following reasons.');
                $message = str_replace(["@name","@username","@email"],[$user->first_name.' '.$user->last_name, $user->username, $user->email], $message);
                Mail::to($user->email)->send(new BasicMail([
                    'subject' => get_static_option('user_identity_re_verify_subject') ?? __('User Identity Reverification.'),
                    'message' => $message
                ]));
            }
            catch (\Exception $e) {}
        }
        $user->user_verified_status == 0 ? IdentityVerification::where('user_id',$request->user_id)->update(['status'=>1]) : IdentityVerification::where('user_id',$request->user_id)->update(['status'=>2]);
        return response()->json(['status'=>'ok']);
    }

    //user identity verify decline
    public function identity_verify_decline(Request $request)
    {
        $user = User::where('id',$request->user_id)->first();
        User::where('id',$request->user_id)->update(['user_verified_status'=>0]);
        IdentityVerification::where('user_id',$request->user_id)->update(['status'=>2]);
        try {
            $message = get_static_option('user_identity_decline_message') ?? __('Your identity verification request decline.');
            $message = str_replace(["@name","@username","@email"],[$user->first_name.' '.$user->last_name, $user->username, $user->email], $message);
            Mail::to($user->email)->send(new BasicMail([
                'subject' => get_static_option('user_identity_decline_subject') ?? __('User Identity Decline'),
                'message' => $message
            ]));
        }
        catch (\Exception $e) {}
        return response()->json(['status'=>'ok']);
    }

    //user active inactive status change
    public function change_status($id)
    {
        $user = User::select(['email','user_active_inactive_status'])->where('id',$id)->first();
        $user->user_active_inactive_status==1 ? $status=0 : $status=1;
        User::where('id',$id)->update(['user_active_inactive_status'=>$status]);

        //security manage
        if(moduleExists('SecurityManage')){
            LogActivity::addToLog('User account status change by admin','Admin');
        }

        if($user->user_active_inactive_status==0){
            try {
                $message = get_static_option('user_status_active_message') ?? __('Your account status has been changed from inactive to active.');
                $message = str_replace(["@name"],[$user->first_name.' '.$user->last_name], $message);
                Mail::to($user->email)->send(new BasicMail([
                    'subject' => get_static_option('user_status_active_subject') ?? __('User Status Activate Email'),
                    'message' => $message
                ]));
            }catch (\Exception $e) {

            }
        }else {
            try {
                $message = get_static_option('user_status_inactive_message') ?? __('Your account status has been changed from active to inactive.');
                $message = str_replace(["@name"], [$user->first_name . ' ' . $user->last_name], $message);
                Mail::to($user->email)->send(new BasicMail([
                    'subject' => get_static_option('user_status_inactive_subject') ?? __('User Status Inactivate Email'),
                    'message' => $message
                ]));
            } catch (\Exception $e) {

            }
        }
        return redirect()->back()->with(toastr_success(__('Status Successfully Changed')));
    }

    // delete user (soft delete)
    public function delete_user($id)
    {
        User::find($id)->delete();
        return redirect()->back()->with(toastr_error(__('User Successfully Deleted.')));
    }

    //permanent delete user
    public function permanent_delete($user_id)
    {
        $user = User::select('id','user_type')->withTrashed()->find($user_id);
        if($user->user_type == 1){
            $jobs = JobPost::where('user_id',$user_id)->get();
            $orders = Order::where('user_id',$user_id)->get();
            $live_chats = LiveChat::where('client_id',$user_id)->get();
            $offers = Offer::where('client_id',$user_id)->get();
            $tickets = Ticket::where('client_id',$user_id)->get();
            $reports = Report::where('client_id',$user_id)->delete();
            $bookmarks = Bookmark::where('user_id',$user_id)->delete();
            $client_notifications = ClientNotification::where('client_id',$user_id)->delete();


            foreach($jobs as $job){
                JobPostSkill::where('job_post_id',$job->id)->delete();
                JobPostSubCategory::where('job_post_id',$job->id)->delete();
                JobProposal::where('job_id',$job->id)->delete();
                JobHistory::where('job_id',$job->id)->delete();
            }
            foreach($orders as $order){
                OrderDeclineHistory::where('order_id',$order->id)->delete();
                OrderDeclineWalletHistory::where('order_id',$order->id)->delete();
                OrderMilestone::where('order_id',$order->id)->delete();
                OrderRequestRevision::where('order_id',$order->id)->delete();
                OrderSubmitHistory::where('order_id',$order->id)->delete();
            }
            foreach($live_chats as $chat){
                LiveChatMessage::where('live_chat_id',$chat->id)->delete();
            }
            foreach($offers as $offer){
                OfferMilestone::where('offer_id',$offer->id)->delete();
            }
            foreach($tickets as $ticket){
                ChatMessage::where('ticket_id',$ticket->id)->delete();
            }

            $jobs->each->delete();
            $live_chats->each->delete();
            $orders->each->delete();
            $offers->each->delete();
            $tickets->each->delete();

        }else{
            $projects = Project::where('user_id',$user_id)->get();
            $orders = Order::where('freelancer_id',$user_id)->get();
            $live_chats = LiveChat::where('freelancer_id',$user_id)->get();
            $offers = Offer::where('freelancer_id',$user_id)->get();
            $tickets = Ticket::where('freelancer_id',$user_id)->get();
            $reports = Report::where('freelancer_id',$user_id)->delete();
            $portfolios = Portfolio::where('user_id',$user_id)->delete();
            $bookmarks = Bookmark::where('user_id',$user_id)->delete();
            $feedbacks = Feedback::where('user_id',$user_id)->delete();
            $freelancer_notifications = FreelancerNotification::where('freelancer_id',$user_id)->delete();
            $job_proposals = JobProposal::where('freelancer_id',$user_id)->delete();


            foreach($projects as $project){
                ProjectAttribute::where('create_project_id',$project->id)->delete();
                ProjectHistory::where('project_id',$project->id)->delete();
                ProjectHistory::where('project_id',$project->id)->delete();
                ProjectSubCategory::where('project_id',$project->id)->delete();
            }
            foreach($orders as $order){
                OrderDeclineHistory::where('order_id',$order->id)->delete();
                OrderDeclineWalletHistory::where('order_id',$order->id)->delete();
                OrderMilestone::where('order_id',$order->id)->delete();
                OrderRequestRevision::where('order_id',$order->id)->delete();
                OrderSubmitHistory::where('order_id',$order->id)->delete();
            }
            foreach($live_chats as $chat){
                LiveChatMessage::where('live_chat_id',$chat->id)->delete();
            }
            foreach($offers as $offer){
                OfferMilestone::where('offer_id',$offer->id)->delete();
            }
            foreach($tickets as $ticket){
                ChatMessage::where('ticket_id',$ticket->id)->delete();
            }

            $projects->each->delete();
            $live_chats->each->delete();
            $orders->each->delete();
            $offers->each->delete();
            $tickets->each->delete();
        }
        $user->forceDelete();
        return back()->with(toastr_error(__('User Successfully Deleted Permanently.')));
    }

    // restore user (soft delete user restore)
    public function user_restore(Request $request, $id=null)
    {
        if($request->isMethod('post')){
            User::withTrashed()->find($id)->restore();
            return redirect()->back()->with(toastr_success(__('User Successfully Restore')));
        }
        $all_users = User::onlyTrashed()->latest()->paginate(10);
        return view('backend.pages.user.trash-user.deleted-users',compact('all_users'));
    }

    // pagination
    function pagination_delete_user(Request $request)
    {
        if($request->ajax()){
            $all_users = User::onlyTrashed()->latest()->paginate(10);
            return view('backend.pages.user.trash-user.search-result-for-delete-users', compact('all_users'))->render();
        }
    }

    // search user
    public function search_delete_user(Request $request)
    {
        $all_users= User::withTrashed()->where('deleted_at','!=',null)->where('first_name', 'LIKE', "%". strip_tags($request->string_search) ."%")->paginate(10);
        return $all_users->total() >= 1 ? view('backend.pages.user.trash-user.search-result-for-delete-users', compact('all_users'))->render() : response()->json(['status'=>__('nothing')]);
    }

    //verification request
    public function verification_requests()
    {
        $all_requests = IdentityVerification::whereHas('user')->with('user')->latest()->where('status',null)
            ->orWhere('status',2)->paginate(10);
        return view('backend.pages.user.verification.verification-request',compact('all_requests'));
    }

    // pagination
    function verification_request_pagination(Request $request)
    {
        if($request->ajax()){
            $all_requests = IdentityVerification::whereHas('user')->latest()->paginate(10);
            return view('backend.pages.user.verification.verification-request-search', compact('all_requests'))->render();
        }
    }

    // search user
    public function verification_request_search_user(Request $request)
    {
        $all_requests= IdentityVerification::whereHas('user',function($query) use ($request){
            $query->where('first_name', 'LIKE', "%". strip_tags($request->string_search) ."%");
        })->paginate(10);
        return $all_requests->total() >= 1 ? view('backend.pages.user.verification.verification-request-search', compact('all_requests'))->render() : response()->json(['status'=>__('nothing')]);

    }

    //disable 2fa
    public function disable_2fa($id)
    {
        $user = User::select(['email','first_name','last_name'])->where('id',$id)->first();
        User::where('id',$id)->update([ 'google_2fa_enable_disable_disable' => 0]);
            try {
                $message = get_static_option('_2fa_disable_message') ?? __('2 factor authentication successfully disable from your account.');
                $message = str_replace(["@name"],[$user->first_name.' '.$user->last_name], $message);
                Mail::to($user->email)->send(new BasicMail([
                    'subject' => get_static_option('_2fa_disable_subject') ?? __('Disable 2FA Email'),
                    'message' => $message
                ]));
            }catch (\Exception $e) {}
        return back()->with(toastr_success(__('2FA Successfully Disable')));
    }

    //verify user email
    public function verify_user_email($id)
    {
        $user = User::select(['is_email_verified','email','first_name','last_name'])->where('id',$id)->first();
        User::where('id',$id)->update([ 'is_email_verified' => 1]);
        try {
            $message = get_static_option('user_email_verified_message') ?? __('Your email address successfully verified.');
            $message = str_replace(["@name"],[$user->first_name.' '.$user->last_name], $message);
            Mail::to($user->email)->send(new BasicMail([
                'subject' => get_static_option('user_email_verified_subject') ?? __('Disable 2FA Email'),
                'message' => $message
            ]));
        }
        catch (\Exception $e) {}
        return redirect()->back()->with(toastr_success(__('Email Address Successfully Verified')));
    }

    public function individual_commission_settings(Request $request)
    {
        IndividualCommissionSetting::updateOrCreate(
            ['user_id'   => $request->user_id_for_individual_settings],
            [
            'admin_commission_type' => $request->admin_commission_type,
            'admin_commission_charge' => $request->admin_commission_charge,
            ]);

            //security manage
            if(moduleExists('SecurityManage')){
                LogActivity::addToLog('Individual user commission setup by admin','Admin');
            }
        return back()->with(toastr_success(__('Individual Settings Successfully Updated')));
    }

    public function bulk_action_user(Request $request){
        foreach($request->ids as $user_id){
            User::find($user_id)->delete();
        }
        return redirect()->back()->with(toastr_error(__('Selected User Successfully Deleted')));
    }
}
