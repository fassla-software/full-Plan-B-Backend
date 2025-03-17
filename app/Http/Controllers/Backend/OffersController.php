<?php

namespace App\Http\Controllers\Backend;

use App\Helper\LogActivity;
use App\Models\NewProposal;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OffersController extends Controller
{
    public function index()
    {
        $offres = NewProposal::with(['request.requestable.subCategory', 'user:id,first_name,last_name,email'])
            ->latest()->paginate(10);

        return view('backend.pages.offers.all-offers', compact('offres'));
    }

    function offers_pagination(Request $request)
    {
        if ($request->ajax()) {
            $offres = NewProposal::with(['request.requestable.subCategory', 'user:id,first_name,last_name,email'])
                ->latest()->paginate(10);
            return view('backend.pages.offers.search-result', compact('offres'))->render();
        }
    }

    public function search_offers(Request $request)
    {
        $offres = NewProposal::with(['request.requestable.subCategory', 'user:id,first_name,last_name,email'])
            ->where(function ($q) use ($request) {
                $q->whereRelation('user', 'first_name', 'LIKE', "%" . strip_tags($request->string_search) . "%")
                    ->orWhereRelation('user', 'last_name', 'LIKE', "%" . strip_tags($request->string_search) . "%")
                    ->orWhereRelation('user', 'email', 'LIKE', "%" . strip_tags($request->string_search) . "%")
                    ->orWhereRelation('user', 'phone', 'LIKE', "%" . strip_tags($request->string_search) . "%")
                    ->orWhere('price', 'LIKE', "%" . strip_tags($request->string_search) . "%")
                    ->orWhere('per', 'LIKE', "%" . strip_tags($request->string_search) . "%")
                    ->orWhere('other_terms', 'LIKE', "%" . strip_tags($request->string_search) . "%")
                    ->orWhere('offer_ends_at', 'LIKE', "%" . strip_tags($request->string_search) . "%")
                    ->orWhere('current_location', 'LIKE', "%" . strip_tags($request->string_search) . "%");
            })->paginate(10);
        return $offres->total() >= 1 ? view('backend.pages.offers.search-result', compact('offres'))->render() : response()->json(['status' => __('nothing')]);
    }

    public function edit_info(Request $request)
    {
        $request->validate([
            'edit_offer_price' => 'required|numeric|min:1',
            'edit_per' => 'required|in:day,weak,year,month,hour',
            'edit_current_location' => 'required|string',
            'edit_ends_at' => 'required|date',
            'edit_other_terms' => 'required|string',
        ]);

        NewProposal::findOrFail($request->edit_offer_id)
            ->update([
                'price' => $request->edit_offer_price,
                'per' => $request->edit_per,
                'current_location' => $request->edit_current_location,
                'ends_at' => $request->edit_ends_at,
                'other_terms' => $request->edit_other_terms,
            ]);

        toastr_success(__('Offer Info Successfully Updated'));
        return back();
    }

    public function delete_offer($id)
    {
        NewProposal::findOrFail($id)->delete();
        return redirect()->back()->with(toastr_error(__('Offer Successfully Deleted.')));
    }
}
