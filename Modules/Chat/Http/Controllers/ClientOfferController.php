<?php

namespace Modules\Chat\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Chat\Entities\Offer;

class ClientOfferController extends Controller
{
    public function all_offers()
    {
        $offers = Offer::where('client_id',auth()->user()->id)->whereHas('freelancer')->latest()->paginate(10);
        $top_projects = Project::select('id', 'title','slug','user_id','basic_regular_charge','basic_discount_charge','basic_delivery','description','image')
            ->where('project_on_off','1')
            ->where('status','1')
            ->whereHas('project_creator')
            ->latest()
            ->take(3)
            ->get();
        return view('chat::client.offer.offer',compact(['offers','top_projects']));
    }

    public function offer_details($id)
    {
        $offer_details = Offer::with('milestones')->where('client_id',auth()->user()->id)->where('id',$id)->first();
        return !empty($offer_details) ? view('chat::client.offer.offer-details',compact('offer_details')) : back();
    }

    public function pagination(Request $request)
    {
        if($request->ajax()){
            $offers = Offer::where('client_id',Auth::guard('web')->user()->id)->latest()->paginate(10);
            return view('chat::client.offer.search-result', compact('offers'))->render();
        }
    }
}
