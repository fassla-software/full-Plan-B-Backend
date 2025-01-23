<?php

namespace Modules\Chat\Http\Controllers;


use App\Models\JobPost;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Chat\Entities\Offer;
use Modules\Chat\Entities\OfferMilestone;

class FreelancerOfferController extends Controller
{
    public function offer_send(Request $request)
    {
        if(Auth::guard('web')->user()->is_suspend == 1){
            return back()->with(toastr_warning(__('You can not send offer because your account is suspended. please try to contact admin')) );
        }

        $pay_by_milestone = $request->pay_by_milestone;
        $pay_at_once = $request->pay_at_once;

        if(!empty($pay_at_once) && $pay_at_once === 'pay-at-once') {
            $request->validate([
                'offer_description' => 'required|max:100000',
                'offer_deadline' => 'required',
                'offer_revision' => 'required|min:1|max:100',
            ]);
        }

        $offer = Offer::create([
            'freelancer_id' => auth()->user()->id,
            'client_id' => $request->client_id,
            'price' => $request->offer_price,
            'description' => $request->offer_description ?? NULL,
            'deadline' => $request->offer_deadline ?? NULL,
            'revision' => $request->offer_revision ?? 0,
            'revision_left' => $request->offer_revision ?? 0,
            'status' => 0,
        ]);

        $last_offer_id = $offer->id;
        $type = 'Offer';
        $msg = __('You have a new offer');
        client_notification($last_offer_id, $request->client_id, $type, $msg);

        //check and create milestone
        if(!empty($pay_by_milestone) && $pay_by_milestone === 'pay-by-milestone'){
            $request->validate([
                'milestone_title.*' => 'required|max:100',
                'milestone_description.*' => 'required|max:10000',
                'milestone_price.*' => 'required|min:1|max:10000',
                'milestone_revision.*' => 'required|digits_between:1,200',
                'milestone_deadline.*' => 'required',
            ]);

            $milestone_price = 0;
            foreach($request->milestone_price as $key => $attr) {$milestone_price += $request->milestone_price[$key];}

            if($milestone_price > $request->offer_price || $milestone_price < $request->offer_price){return back()->with(toastr_warning(__('Total milestone price must be equal to offer price')));}
            self::createMilestone($last_offer_id,$request);
        }
        toastr_success(__('Offer Successfully Send'));
        return redirect()->route('freelancer.live.chat',[
            'client_id'=>$request->client_id
        ]);
    }

    private function createMilestone($last_offer_id,$request)
    {
        $arr = [];
        foreach($request->milestone_title as $key => $attr) {
            $arr[] = [
                'offer_id' => $last_offer_id,
                'title' => $request->milestone_title[$key],
                'description' => $request->milestone_description[$key],
                'price' => $request->milestone_price[$key],
                'revision' => $request->milestone_revision[$key],
                'revision_left' => $request->milestone_revision[$key],
                'deadline' => $request->milestone_deadline[$key],
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];
        }
        OfferMilestone::insert($arr);
    }

    public function all_offers()
    {
        $offers = Offer::where('freelancer_id',auth()->user()->id)->whereHas('client')->latest()->paginate(10);
        $jobs = JobPost::with('job_creator','job_skills')
            ->whereHas('job_creator')
            ->where('on_off','1')
            ->where('status','1')
            ->where('job_approve_request','1')
            ->latest()
            ->take(5)->get();

        return view('chat::freelancer.offer.offer',compact(['offers','jobs']));
    }

    public function offer_details($id)
    {
        $offer_details = Offer::with('milestones')->where('freelancer_id',auth()->user()->id)->where('id',$id)->first();
        return !empty($offer_details) ? view('chat::freelancer.offer.offer-details',compact('offer_details')) : back();
    }

    public function pagination(Request $request)
    {
        if($request->ajax()){
            $offers = Offer::where('freelancer_id',Auth::guard('web')->user()->id)->latest()->paginate(10);
            return view('chat::freelancer.offer.search-result', compact('offers'))->render();
        }
    }

    public function offer_delete(Request $request)
    {
        if($request->offer_id){
            OfferMilestone::where('offer_id',$request->offer_id)->delete();
            Offer::where('id',$request->offer_id)->delete();
            return response()->json([
                'status' => 'success',
                'msg' => __('Offer Successfully Deleted'),
            ]);
        }

        return response()->json([
            'status' => 'success',
            'msg' => __('Something went wrong. Please try again later.'),
        ]);

    }
}
