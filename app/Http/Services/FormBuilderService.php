<?php

namespace App\Http\Services;

use App\Models\FormBuilder;

class FormBuilderService
{
    public function form($request)
    {
        if($request->isMethod('post')){
            $request->validate([
                'title' => 'required|string',
                'email' => 'required|string',
                'button_text' => 'required|string',
                'success_message' => 'required|string',
            ]);
            FormBuilder::create([
                'title' => $request->title,
                'email' => $request->email,
                'button_text' => $request->button_text,
                'success_message' => $request->success_message,
            ]);
            toastr_success(__('New Form Added Successfully'));
            return back();
        }
    }

    public function delete_form($id)
    {
        FormBuilder::findOrFail($id)->delete();
        toastr_error(__('Form Deleted Successfully'));
        return back();
    }

    public function bulk_action($request){
        FormBuilder::whereIn('id',$request->ids)->delete();
        toastr_error(__('Form Deleted Successfully'));
        return back();
    }
}
