<?php

namespace Modules\Faq\Http\Controllers\Backend;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Faq\Entities\QuestionAnswer;

class FaqController extends Controller
{
    // all faq
    public function faq_all()
    {
        $all_faqs = QuestionAnswer::latest()->paginate(10);
        return view('faq::backend.faqs.all-faqs',compact('all_faqs'));
    }

    // search faq
    public function search_faq(Request $request)
    {
        $all_faqs = QuestionAnswer::where(function ($query) use($request) {
            $query->where('question', 'LIKE', "%". strip_tags($request->string_search) ."%");
        })->latest()->paginate(10);

        return $all_faqs->total() >= 1 ? view('faq::backend.faqs.search-result', compact('all_faqs'))->render() : response()->json(['status'=>__('nothing')]);
    }

    // pagination
    function pagination(Request $request)
    {
        if($request->ajax()){
            if(empty($request->string_search)){
                $all_faqs = QuestionAnswer::latest()->paginate(10);
                return view('faq::backend.faqs.search-result', compact('all_faqs'))->render();
            }else{
                $all_faqs = QuestionAnswer::where(function ($query) use($request) {
                    $query->where('question', 'LIKE', "%". strip_tags($request->string_search) ."%");
                })
                    ->latest()
                    ->paginate(10);
                return $all_faqs->total() >= 1 ? view('faq::backend.faqs.search-result', compact('all_faqs'))->render() : response()->json(['status'=>__('nothing')]);

            }
        }
    }


}
