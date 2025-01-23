<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdditionalSettingsController extends Controller
{
    public function loader_settings(Request $request)
    {
        if($request->isMethod('post')){
            $request->validate(['page_loader' => 'required']);
            $all_fields = ['page_loader'];

            foreach ($all_fields as $field) {
                update_static_option($field, $request->$field);
            }
            toastr_success(__('Page Loader Settings Updated Successfully.'));
            return back();
        }
        return view('backend.pages.additional-settings.loader-settings');
    }

    public function mouse_settings(Request $request)
    {
        if($request->isMethod('post')){
            $request->validate(['mouse_pointer' => 'required']);
            $all_fields = ['mouse_pointer'];

            foreach ($all_fields as $field) {
                update_static_option($field, $request->$field);
            }
            toastr_success(__('Mouse Pointer Settings Updated Successfully.'));
            return back();
        }
        return view('backend.pages.additional-settings.mouse-settings');
    }

    public function bottom_to_top_settings(Request $request)
    {
        if($request->isMethod('post')){
            $request->validate(['bottom_to_top' => 'required']);
            $all_fields = ['bottom_to_top'];

            foreach ($all_fields as $field) {
                update_static_option($field, $request->$field);
            }
            toastr_success(__('Back to Top Button Settings Updated Successfully.'));
            return back();
        }
        return view('backend.pages.additional-settings.back-to-top-settings');
    }

    public function sticky_menu_settings(Request $request)
    {
        if($request->isMethod('post')){
            $request->validate(['sticky_menu' => 'required']);
            $all_fields = ['sticky_menu'];

            foreach ($all_fields as $field) {
                update_static_option($field, $request->$field);
            }
            toastr_success(__('Sticky Menu Settings Updated Successfully.'));
            return back();
        }
        return view('backend.pages.additional-settings.sticky-menu-settings');
    }

    public function commission_display_settings(Request $request)
    {
        if($request->isMethod('post')){
            $request->validate(['commission_disable_client_panel' => 'required']);
            $all_fields = ['commission_disable_client_panel'];

            foreach ($all_fields as $field) {
                update_static_option($field, $request->$field);
            }
            toastr_success(__('Commission Settings Updated Successfully.'));
            return back();
        }
        return view('backend.pages.additional-settings.commission-display-settings-client-panel');
    }

    public function home_page_animation_settings(Request $request)
    {
        if($request->isMethod('post')){
            $request->validate(['home_page_animation' => 'required']);
            $all_fields = ['home_page_animation'];

            foreach ($all_fields as $field) {
                update_static_option($field, $request->$field);
            }
            toastr_success(__('Home Page Animation Settings Updated Successfully.'));
            return back();
        }
        return view('backend.pages.additional-settings.home-page-animation-settings');
    }

    public function project_enable_disable_settings(Request $request)
    {
        if($request->isMethod('post')){
            $request->validate(['project_enable_disable' => 'required']);
            $all_fields = ['project_enable_disable'];

            foreach ($all_fields as $field) {
                update_static_option($field, $request->$field);
            }
            toastr_success(__('Project Enable Disable Settings Updated Successfully.'));
            return back();
        }
        return view('backend.pages.additional-settings.project-enable-disable-settings');
    }

    public function job_enable_disable_settings(Request $request)
    {
        if($request->isMethod('post')){
            $request->validate(['job_enable_disable' => 'required']);
            $all_fields = ['job_enable_disable'];

            foreach ($all_fields as $field) {
                update_static_option($field, $request->$field);
            }
            toastr_success(__('Job Enable Disable Settings Updated Successfully.'));
            return back();
        }
        return view('backend.pages.additional-settings.job-enable-disable-settings');
    }

    public function chat_enable_disable_settings(Request $request)
    {
        if($request->isMethod('post')){
            $request->validate(['chat_email_enable_disable' => 'required']);
            $all_fields = ['chat_email_enable_disable'];

            foreach ($all_fields as $field) {
                update_static_option($field, $request->$field);
            }
            toastr_success(__('Chat Email Settings Updated Successfully.'));
            return back();
        }
        return view('backend.pages.additional-settings.chat-email-settings');
    }

    public function file_extension_settings(Request $request)
    {
        $file_extensions = json_decode(get_static_option('file_extensions')) ?? [];
        if($request->isMethod('post')){
            $request->validate([
                'file_extensions' => 'required',
                'max_upload_size' => 'required'
            ]);
             update_static_option('file_extensions', json_encode($request->file_extensions));
             update_static_option('max_upload_size', $request->max_upload_size);
            return back()->with( toastr_success(__('File Extension Settings Updated Successfully.')));
        }
        return view('backend.pages.additional-settings.file-extension-settings',compact(['file_extensions']));
    }

    public function profile_switch_settings(Request $request)
    {
        if($request->isMethod('post')){
            $request->validate(['profile_switch_enable_disable' => 'required']);
            $all_fields = ['profile_switch_enable_disable'];

            foreach ($all_fields as $field) {
                update_static_option($field, $request->$field);
            }
            toastr_success(__('Profile Switch Enable Disable Settings Updated Successfully.'));
            return back();
        }
        return view('backend.pages.additional-settings.profile-switch-enable-disable-settings');
    }

}
