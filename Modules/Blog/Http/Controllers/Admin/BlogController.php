<?php

namespace Modules\Blog\Http\Controllers\Admin;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Modules\Blog\Entities\BlogPost;
use Modules\Pages\Entities\Page;
use plugins\FormBuilder\SanitizeInput;

class BlogController extends Controller
{
    public function all_blog()
    {
        $all_blogs = BlogPost::with('category')->latest()->paginate(10);
        return view('blog::backend.all-blog',compact('all_blogs'));
    }

    public function create()
    {
        return view('blog::backend.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|unique:blog_posts',
            'blog_content' => 'required',
            'category' => 'required',
            'status' => 'required',
            'tag_name' => 'nullable|max:255',
        ]);

        if(empty($request->image)){
            toastr_warning(__("Blog image is required"));
            return back();
        }
        $slug = !empty($request->slug) ? $request->slug : $request->title;
        $blog = new BlogPost();

        $blog->title =  SanitizeInput::esc_html($request->title);
        $blog->content =  $request->blog_content;
        $slug = Str::slug(purify_html($slug),'-',null);

        $blog->slug = purify_html($slug);
        $blog->status = $request->status;
        $blog->category_id = $request->category;
        $blog->admin_id = Auth::guard('admin')->user()->id;
        $blog->image = $request->image;
        $blog->tag_name = $request->tag_name;

        $Metas = [
            'meta_title'=> purify_html($request->meta_title),
            'meta_tags'=> purify_html($request->meta_tags),
            'meta_description'=> purify_html($request->meta_description),

            'facebook_meta_tags'=> purify_html($request->facebook_meta_tags),
            'facebook_meta_description'=> purify_html($request->facebook_meta_description),
            'facebook_meta_image'=> $request->facebook_meta_image,

            'twitter_meta_tags'=> purify_html($request->twitter_meta_tags),
            'twitter_meta_description'=> purify_html($request->twitter_meta_description),
            'twitter_meta_image'=> $request->twitter_meta_image,
        ];

        $blog->save();
        $blog->meta_data()->create($Metas);
        toastr_success(__("Blog Post Successfully Created"));
        return back();
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $blog = BlogPost::where('id',$id)->first();
        return view('blog::backend.edit',compact('blog'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|unique:blog_posts,title,'.$id,
            'blog_content' => 'required',
            'category' => 'required',
            'status' => 'required',
            'tag_name' => 'nullable|max:255',
        ]);

        $slug = !empty($request->slug) ? $request->slug : $request->title;
        $blog = BlogPost::find($id);
        $blog->title =  SanitizeInput::esc_html($request->title);
        $blog->slug = Str::slug(purify_html($slug),'-',null);
        $blog->content =  $request->blog_content;
        $blog->status = $request->status;
        $blog->category_id = $request->category;
        $blog->admin_id = $blog->admin_id;
        $blog->image = $request->image;
        $blog->tag_name = $request->tag_name;

        $Metas = [
            'meta_title'=> purify_html($request->meta_title),
            'meta_tags'=> purify_html($request->meta_tags),
            'meta_description'=> purify_html($request->meta_description),

            'facebook_meta_tags'=> purify_html($request->facebook_meta_tags),
            'facebook_meta_description'=> purify_html($request->facebook_meta_description),
            'facebook_meta_image'=> $request->facebook_meta_image,

            'twitter_meta_tags'=> purify_html($request->twitter_meta_tags),
            'twitter_meta_description'=> purify_html($request->twitter_meta_description),
            'twitter_meta_image'=> $request->twitter_meta_image,
        ];

        $blog->save();
        $blog->meta_data()->update($Metas);
        toastr_success(__("Blog Post Successfully Updated"));
        return back();
    }

    public function destroy($id)
    {
        $blog = BlogPost::find($id);
        $blog?->meta_data?->delete();
        $blog->delete();
        toastr_success(__("Blog Post Successfully Deleted"));
        return back();
    }

    // pagination
    function pagination(Request $request)
    {
        if($request->ajax()){
            if(!empty($request->string_search)){
                $all_blogs= BlogPost::where('title', 'LIKE', "%". strip_tags($request->string_search) ."%")->paginate(10);
            }else{
                $all_blogs = BlogPost::latest()->paginate(10);
            }
            return view('blog::backend.search-result', compact('all_blogs'))->render();
        }
    }

    // search blog
    public function search_blog(Request $request)
    {
        $all_blogs = BlogPost::where('title', 'LIKE', "%". strip_tags($request->string_search) ."%")->paginate(10);
        return $all_blogs->total() >= 1 ? view('blog::backend.search-result', compact('all_blogs'))->render() : response()->json(['status'=>__('nothing')]);
    }

}
