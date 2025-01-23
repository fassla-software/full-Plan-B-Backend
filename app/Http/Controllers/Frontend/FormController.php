<?php

namespace App\Http\Controllers\Frontend;

use App\Models\FormBuilder;
use App\Http\Controllers\Controller;
use App\Mail\CustomFormBuilderMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class FormController extends Controller
{

    public function custom_form_submit(Request $request)
    {
        $request->validate([
            "custom_form_id" => 'required'
        ]);

        $field_details = FormBuilder::find($request->custom_form_id);

        unset($request['custom_form_id']);
        unset($request['gcaptcha_token']);

        $validated_data = $this->get_filtered_data_from_request($field_details->fields, $request, false);
        $all_attachment = $validated_data['all_attachment'];
        $all_field_serialize_data = $validated_data['field_data'];

        try {
            Mail::to($field_details->email)->send(
                new CustomFormBuilderMail([
                    'data' => [
                        'all_fields' => $all_field_serialize_data,
                        'attachments' => $all_attachment
                    ],
                    'form_title' => $field_details->title,
                    'subject' => sprintf(__('You Have %s Message from'), $field_details->title) . ' ' . get_static_option('site_title')
                ])
            );
        } catch (\Exception $e) {
            toastr_error($e->getMessage());
            return redirect()->back();

        }
        toastr_success($field_details->success_message);
        return redirect()->back();
    }

    public function get_filtered_data_from_request($option_value, $request)
    {

        $all_attachment = [];
        $all_quote_form_fields = (array) json_decode($option_value);
        $all_field_type = isset($all_quote_form_fields['field_type']) ? (array) $all_quote_form_fields['field_type'] : [];
        $all_field_name = isset($all_quote_form_fields['field_name']) ? $all_quote_form_fields['field_name'] : [];
        $all_field_required = isset($all_quote_form_fields['field_required'])  ? (object) $all_quote_form_fields['field_required'] : [];
        $all_field_mimes_type = isset($all_quote_form_fields['mimes_type']) ? (object) $all_quote_form_fields['mimes_type'] : [];
        //get field details from, form request
        $all_field_serialize_data = $request->all();
        unset($all_field_serialize_data['_token']);
        if (!empty($all_field_name)) {
            foreach ($all_field_name as $index => $field) {
                $is_required = !empty($all_field_required) && property_exists($all_field_required, $index) ? $all_field_required->$index : '';
                $mime_type = !empty($all_field_mimes_type) && property_exists($all_field_mimes_type, $index) ? $all_field_mimes_type->$index : '';
                $field_type = isset($all_field_type[$index]) ? $all_field_type[$index] : '';
                if (!empty($field_type) && $field_type == 'file') {
                    unset($all_field_serialize_data[$field]);
                }
                $validation_rules = !empty($is_required) ? 'required|' : '';
                $validation_rules .= !empty($mime_type) ? $mime_type : '';
                //validate field
                $request->validate([
                    $field => $validation_rules
                ]);
                if ($field_type == 'file' && $request->hasFile($field)) {
                    $filed_instance = $request->file($field);
                    $file_extenstion = $filed_instance->getClientOriginalExtension();
                    $attachment_name = 'attachment-' . Str::random(32) . '-' . $field . '.' . $file_extenstion;
                    $filed_instance->move('assets/uploads/attachment/applicant', $attachment_name);
                    $all_attachment[$field] = 'assets/uploads/attachment/applicant/' . $attachment_name;
                }
            }
        }
        return [
            'all_attachment' => $all_attachment,
            'field_data' => $all_field_serialize_data
        ];
    }
}
