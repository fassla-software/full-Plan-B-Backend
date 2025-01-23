<?php

namespace Modules\Blog\Http\Controllers\Frontend;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Blog\Entities\BlogPost;
use Modules\Service\Entities\Category;

class BlogController extends Controller
{

    public function blog()
    {
        $blogs = BlogPost::with('category')->latest()->where('status',1)->paginate(10);
        $categories = Category::select('id','category','status')->where('status',1)->withCount('blogs')->get();
        return view('blog::frontend.blogs.blogs',compact(['blogs','categories']));
    }

    public function blog_filter(Request $request)
    {
        $category = $request->category;
        if ($category == 'all') {
            $blogs = BlogPost::with('category')->latest()
                ->where('status',1)
                ->paginate(10);
        }else {
            $blogs = BlogPost::with('category')->latest()
                ->where('category_id',$category)
                ->where('status',1)
                ->paginate(10);
        }
        return $blogs->count() >= 1 ? view('blog::frontend.blogs.search-result', compact(['blogs','category']))->render() :  response()->json(['status' => 'nothing']);
    }

    // pagination
    function pagination(Request $request)
    {
        if($request->ajax()){
            $category = $request->category;
            if($category == 'all'){
                $blogs = BlogPost::latest()->where('status',1)->paginate(10);
            }else{
                $blogs = BlogPost::latest()
                    ->where('category_id',$category)
                    ->where('status',1)
                    ->paginate(10);
            }
            return view('blog::frontend.blogs.search-result', compact('blogs'))->render();
        }
    }

    //details
    public function blog_details($slug)
    {
        $blog_details = BlogPost::with('meta_data')->where('slug',$slug)->first();
        $related_blogs = BlogPost::with('category')->where('category_id',$blog_details->category_id)->latest()->take(2)->get();
        $categories = Category::select('id','category','status')->where('status',1)->withCount('blogs')->get();
        $blogs = BlogPost::where('status',1)->paginate(10);
        return view('blog::frontend.blogs.blog-details',compact(['blog_details','related_blogs','categories','blogs']));
    }
}
