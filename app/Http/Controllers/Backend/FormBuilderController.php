<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Services\FormBuilderService;
use App\Models\FormBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FormBuilderController extends Controller
{
    public function form(Request $request)
    {
        if($request->isMethod('post')){
            return (new FormBuilderService())->form($request);
        }
        $all_forms = FormBuilder::latest()->get();
        return view('backend.forms.all-forms',compact('all_forms'));
    }

    public function edit_form(Request $request, $id)
    {
        $form =  FormBuilder::findOrFail($id);
        return view('backend.forms.edit-form',compact('form'));
    }

    public function update_form(Request $request){
        $this->validate($request,[
            'title' => 'required|string',
            'email' => 'required|string',
            'button_title' => 'required|string',
            'field_name' => 'required|max:191',
            'field_placeholder' => 'required|max:191',
            'success_message' => 'required',
        ]);
        $id = $request->id;
        $title = $request->title;
        $email = $request->email;
        $button_title = $request->button_title;
        unset($request['_token'],$request['email'],$request['button_title'],$request['title'],$request['id']);
        $all_fields_name = [];
        $all_request_except_token = $request->all();
        foreach ($request->field_name as $fname){
            $all_fields_name[] = strtolower(Str::slug($fname));
        }
        $all_request_except_token['field_name'] = $all_fields_name;
        $json_encoded_data = json_encode($all_request_except_token);

        FormBuilder::findOrfail($id)->update([
            'title' => $title,
            'email' => $email,
            'button_text' => $button_title,
            'success_message' => $request->success_message,
            'fields' => $json_encoded_data
        ]);

        toastr_success(__('Item Updated Successfully'));
        return back();
    }

    public function delete_form($id){
        return (new FormBuilderService())->delete_form($id);
    }

    public function bulk_action(Request $request){
        return (new FormBuilderService())->bulk_action($request);
    }
}
