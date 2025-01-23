<?php

namespace Modules\Pages\Http\Controllers;

use App\Helpers\FlashMsg;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Modules\Pages\Entities\Page;
use Modules\Pages\Http\Services\PageService;

class PagesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function all_pages()
    {
        $all_pages = Page::latest()->get();
        return view('pages::all-pages',compact('all_pages'));
    }

    public function add_new_page(Request $request)
    {
        if($request->isMethod('post')){
           return (new PageService)->add_new_page($request);
        }
        return view('pages::add-new-page');
    }

    public function edit_page(Request $request,$id=null)
    {
        if($request->isMethod('post')){
            return (new PageService)->edit_page($request,$id);
        }
        $page_details = Page::with('meta_data')->where('id',$id)->first();
        return view('pages::edit-page',compact('page_details'));
    }

    public function delete_single_page($id){
        return (new PageService)->delete_single_page($id);
    }

    public function bulk_action(Request $request)
    {
        return (new PageService)->bulk_action($request);
    }

    public function _404_page(Request $request)
    {
        if($request->isMethod('post'))
        {
            return (new PageService)->_404_page($request);
        }
        return view('pages::404-page');
    }

    public function maintenance_page(Request $request)
    {
        if($request->isMethod('post'))
        {
            return (new PageService)->maintenance_page($request);
        }
        return view('pages::maintenance-page');
    }

}
