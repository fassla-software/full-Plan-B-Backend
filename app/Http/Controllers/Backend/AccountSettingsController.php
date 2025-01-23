<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AccountSettingsController extends Controller
{
    //main page settings
    public function main_page(Request $request)
    {
        if($request->isMethod('post')){
            $this->validate($request, [
                'account_page_title' => 'nullable|string|max:100',
                'account_page_skip_title' => 'nullable|string|max:100',
                'account_page_back_button_title' => 'nullable|string|max:100',
            ]);

            $all_fields = [
                'account_page_title',
                'account_page_skip_title',
                'account_page_back_button_title',
            ];
            foreach ($all_fields as $field) {
                update_static_option($field, $request->$field);
            }
            toastr_success(__('Main Page Settings Updated Successfully.'));
            return back();
        }
        return view('backend.pages.account-settings.main-page');
    }

    //introduction settings
    public function introduction(Request $request)
    {
        if($request->isMethod('post')){
            $this->validate($request, [
                'introduction_menu_title' => 'nullable|string|max:100',
                'introduction_menu_sub_title' => 'nullable|string|max:300',
                'professional_title' => 'nullable|string|max:100',
                'intro_title' => 'nullable|string|max:100',
            ]);

            $all_fields = [
                'introduction_menu_title',
                'introduction_menu_sub_title',
                'professional_title',
                'intro_title',
            ];
            foreach ($all_fields as $field) {
                update_static_option($field, $request->$field);
            }
            toastr_success(__('Introduction Settings Updated Successfully.'));
            return back();
        }
        return view('backend.pages.account-settings.introduction');
    }

    //experience settings
    public function experience(Request $request)
    {
        if($request->isMethod('post')){
            $this->validate($request, [
                'experience_menu_title' => 'nullable|string|max:100',
                'experience_menu_sub_title' => 'nullable|string|max:300',
                'experience_title' => 'nullable|string|max:100',
                'experience_inner_title' => 'nullable|string|max:100',
                'experience_modal_title' => 'nullable|string|max:100',
                'experience_edit_modal_title' => 'nullable|string|max:100',
            ]);

            $all_fields = [
                'experience_menu_title',
                'experience_menu_sub_title',
                'experience_title',
                'experience_inner_title',
                'experience_modal_title',
                'experience_edit_modal_title',
            ];
            foreach ($all_fields as $field) {
                update_static_option($field, $request->$field);
            }
            toastr_success(__('Experience Settings Updated Successfully.'));
            return back();
        }
        return view('backend.pages.account-settings.experience');
    }

    //education settings
    public function education(Request $request)
    {
        if($request->isMethod('post')){
            $this->validate($request, [
                'education_menu_title' => 'nullable|string|max:100',
                'education_menu_sub_title' => 'nullable|string|max:300',
                'education_title' => 'nullable|string|max:100',
                'education_inner_title' => 'nullable|string|max:100',
                'education_modal_title' => 'nullable|string|max:100',
                'education_edit_modal_title' => 'nullable|string|max:100',
            ]);

            $all_fields = [
                'education_menu_title',
                'education_menu_sub_title',
                'education_title',
                'education_inner_title',
                'education_modal_title',
                'education_edit_modal_title',
            ];
            foreach ($all_fields as $field) {
                update_static_option($field, $request->$field);
            }
            toastr_success(__('Education Settings Updated Successfully.'));
            return back();
        }
        return view('backend.pages.account-settings.education');
    }

    //work settings
    public function work(Request $request)
    {
        if($request->isMethod('post')){
            $this->validate($request, [
                'work_menu_title' => 'nullable|string|max:100',
                'work_menu_sub_title' => 'nullable|string|max:300',
                'work_title' => 'nullable|string|max:100',
                'work_inner_title' => 'nullable|string|max:100',
                'work_modal_title' => 'nullable|string|max:100',
            ]);

            $all_fields = [
                'work_menu_title',
                'work_menu_sub_title',
                'work_title',
                'work_inner_title',
                'work_modal_title',
            ];
            foreach ($all_fields as $field) {
                update_static_option($field, $request->$field);
            }
            toastr_success(__('Work Settings Updated Successfully.'));
            return back();
        }
        return view('backend.pages.account-settings.work');
    }

    //skill settings
    public function skill(Request $request)
    {
        if($request->isMethod('post')){
            $this->validate($request, [
                'skill_menu_title' => 'nullable|string|max:100',
                'skill_menu_sub_title' => 'nullable|string|max:300',
                'skill_title' => 'nullable|string|max:100',
            ]);

            $all_fields = [
                'skill_menu_title',
                'skill_menu_sub_title',
                'skill_title',
            ];
            foreach ($all_fields as $field) {
                update_static_option($field, $request->$field);
            }
            toastr_success(__('Skill Settings Updated Successfully.'));
            return back();
        }
        return view('backend.pages.account-settings.skill');
    }

    //rate and profile photo settings
    public function rate_and_photo(Request $request)
    {
        if($request->isMethod('post')){
            $this->validate($request, [
                'hourly_rate_menu_title' => 'nullable|string|max:100',
                'hourly_rate_menu_sub_title' => 'nullable|string|max:300',
                'hourly_rate_title' => 'nullable|string|max:100',
                'profile_photo_title' => 'nullable|string|max:100',
            ]);

            $all_fields = [
                'hourly_rate_menu_title',
                'hourly_rate_menu_sub_title',
                'hourly_rate_title',
                'profile_photo_title',
            ];
            foreach ($all_fields as $field) {
                update_static_option($field, $request->$field);
            }
            toastr_success(__('Hourly Rate Settings Updated Successfully.'));
            return back();
        }
        return view('backend.pages.account-settings.rate-photo');
    }
}
