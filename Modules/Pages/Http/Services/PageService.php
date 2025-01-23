<?php

namespace Modules\Pages\Http\Services;

use Illuminate\Support\Str;
use Modules\Pages\Entities\Page;
use plugins\FormBuilder\SanitizeInput;

class PageService
{
    public function add_new_page($request)
    {
        $request->validate([
            'title' => 'required',
            'page_content' => 'nullable',
            'status' => 'required',
            'slug' => 'nullable|unique:pages',
            'visibility' => 'nullable|string',
        ]);

        $page = new Page();

        $page->title =  SanitizeInput::esc_html($request->title);
        $page->page_content =  $request->page_content;
        $slug = !empty($request->slug) ? $request->slug : Str::slug($request->title);

        $page->slug = purify_html($slug);
        $page->status = $request->status;
        $page->visibility = $request->visibility;
        $page->page_builder_status = $request->page_builder_status;
        $page->layout = $request->layout;
        $page->page_class = $request->page_class;
        $page->navbar_variant = $request->navbar_variant;
        $page->footer_variant = $request->footer_variant;
        $page->breadcrumb_status = $request->breadcrumb_status;

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

        $page->save();
        $page->meta_data()->create($Metas);
        toastr_success(__("Page Successfully Created"));
        return back();
    }

    public function edit_page($request,$id)
    {
        $request->validate([
            'title' => 'required',
            'page_content' => 'nullable',
            'status' => 'required',
            'slug' => 'nullable',
            'visibility' => 'nullable|string',
        ]);

        $page = Page::find($id);
        $page->title =  SanitizeInput::esc_html($request->title);
        $page->page_content =  $request->page_content;
        $slug = !empty($request->slug) ? $request->slug : Str::slug($request->title);
        $page->slug = purify_html($slug);
        $page->status = $request->status;
        $page->visibility = $request->visibility;
        $page->page_builder_status = $request->page_builder_status;
        $page->layout = $request->layout;
        $page->page_class = $request->page_class;
        $page->navbar_variant = $request->navbar_variant;
        $page->footer_variant = $request->footer_variant;
        $page->breadcrumb_status = $request->breadcrumb_status;

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

        $page->save();
        $page->meta_data()->update($Metas);
        toastr_success(__("Page Successfully Updated"));
        return back();
    }

    public function delete_single_page($id)
    {
        $page = Page::find($id);
        $page->delete();
        $page->meta_data()->delete();
        toastr_success(__("Page Successfully Deleted"));
        return back();
    }

    public function bulk_action($request)
    {
        $all = Page::findOrFail($request->ids);
        foreach($all as $item){
            $item->delete();
            $item->meta_data()->delete();
        }
        toastr_success(__("Page Successfully Deleted"));
        return back();
    }

    public function _404_page($request)
    {
        if($request->isMethod('post'))
        {
            $request->validate([
                'error_404_page_title' => 'nullable|string',
                'error_404_page_subtitle' => 'nullable|string',
                'error_404_page_paragraph' => 'nullable|string',
                'error_404_page_button_text' => 'nullable|string',
            ]);

            $fields = [
                'error_404_page_title',
                'error_404_page_subtitle',
                'error_404_page_paragraph',
                'error_404_page_button_text',
                'error_image',
            ];
            foreach ($fields as $field) {
                update_static_option($field, $request->$field);
            }
            toastr_success(__('404 Page Updated Successfully.'));
            return back();
        }
    }

    public function maintenance_page($request)
    {
        if($request->isMethod('post'))
        {
            $request->validate([
                'maintain_page_title' => 'nullable|string',
                'maintain_page_description' => 'nullable|string',
                'maintenance_duration' => 'nullable|string',
                'maintain_page_logo' => 'nullable|string|max:191',
            ]);

            $fields = [
                'maintain_page_title',
                'maintain_page_description',
                'maintenance_duration',
                'maintain_page_logo',
            ];
            foreach($fields as $field){
                update_static_option($field,$request->$field);
            }
            toastr_success(__('Maintenance Page Updated Successfully.'));
            return back();

        }
    }
}

