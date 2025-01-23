<?php

namespace Modules\Faq\Http\Controllers\Frontend;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Faq\Entities\QuestionAnswer;

class QuestionController extends Controller
{
    public function question(Request $request)
    {
        $request->validate(['question' => 'required|min:20|max:100|unique:question_answers']);
        $question = QuestionAnswer::create(['question' => $request->question]);
        return !empty($question) ? response()->json(['status'=>'success']) : response()->json(['status'=>'failed','msg' => __('Something Went wrong')]);
    }
}
