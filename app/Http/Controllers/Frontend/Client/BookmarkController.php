<?php

namespace App\Http\Controllers\Frontend\Client;

use App\Http\Controllers\Controller;
use App\Models\Bookmark;
use Illuminate\Http\Request;

class BookmarkController extends Controller
{
    public function bookmark(Request $request)
    {
        $bookmark_data = Bookmark::where('user_id',auth()->user()->id)
            ->where('is_project_job','job')
            ->where('identity',$request->identity)
            ->first();

        if($bookmark_data){
            return response()->json(['status' => 'exists']);
        }else{
            Bookmark::create([
                'identity' => $request->identity,
                'user_id' => auth()->user()->id,
                'is_project_job' => $request->type,
            ]);
            return response()->json(['status' => 'success']);
        }
    }

    public function bookmark_remove(Request $request)
    {
        $bookmark = Bookmark::where('id', $request->identity)->delete();

        if($bookmark){
            return response()->json([
                'status' => 'success'
            ]);
        }
    }
}
