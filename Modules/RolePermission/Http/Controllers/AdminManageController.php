<?php

namespace Modules\RolePermission\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminManageController extends Controller
{

    public function all_admins()
    {
        $all_admins = Admin::all();
        return view('rolepermission::admin-manage.all-admins',compact('all_admins'));
    }

    public function create_admin(Request $request)
    {
        if($request->isMethod('post')){
            $request->validate([
                'name' => 'required',
                'username' => 'required|unique:admins,username',
                'email' => 'required|email|unique:admins,email',
                'password' => 'required|min:8|max:191|confirmed',
                'role' => 'required',
                'image' => 'nullable',
            ]);

            $admin = Admin::create([
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'image' => $request->image,
                'role' => $request->role,
            ]);

            $admin->assignRole($request->role);
            return back()->with(toastr_success(__('New Admin Successfully Created')));
        }
        $roles = Role::all();
        return view('rolepermission::admin-manage.create-admin', compact('roles'));
    }

    //edit admin
    public function edit_admin(Request $request, $id)
    {
        $admin = Admin::where('id',$id)->first();
        $roles = Role::pluck('name','name')->all();
        $admin_role = $admin->roles->pluck('name','name')->all();

        if($request->isMethod('post')){
            $request->validate([
                'name' => 'required',
                'username' => 'required|unique:admins,username,'.$admin->id,
                'email' => 'required|email|unique:admins,email,'.$admin->id,
                'image' => 'nullable',
            ]);

            Admin::where('id',$admin->id)->update([
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                'image' => $request->image,
            ]);

            DB::table('model_has_roles')->where('model_id',$admin->id)->delete();
            $admin->assignRole($request->role);
            return back()->with(toastr_success(__('Admin Info Successfully Updated')));
        }

        return view('rolepermission::admin-manage.edit-admin', compact(['admin','roles','admin_role']));
    }

    //edit admin
    public function update_admin_info(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        $roles = Role::pluck('name','name')->all();
        $admin_role = $admin->roles->pluck('name','name')->all();

        if($request->isMethod('post')){
            $request->validate([
                'name' => 'required',
                'username' => 'required|unique:admins,username,'.$admin->id,
                'email' => 'required|email|unique:admins,email,'.$admin->id,
                'image' => 'nullable',
            ]);

            if(!empty($request->password) || $request->new_password){
                if($request->password != $request->new_password){
                    return back()->with(toastr_warning(__('New and confirm new password does not matched')));
                }
            }

            Admin::where('id',$admin->id)->update([
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                'image' => $request->image,
                'role' => $admin->role ?? 'editor',
            ]);

            if ($request->filled('password')) {
                Admin::where('id',$admin->id)->update([
                    'password' =>Hash::make($request->password),
                ]);
            }
            return back()->with(toastr_success(__('Your Info Successfully Updated')));
        }

        return view('rolepermission::admin-manage.update-info', compact(['admin','roles','admin_role']));
    }

    // delete admin
    public function delete_admin($id)
    {
        $admin = Admin::where('id',$id)->first();
        DB::table('model_has_roles')->where('model_id',$admin->id)->delete();
        $admin->delete();
        return back()->with(toastr_success(__('Admin Successfully Deleted')));
    }

    //change password
    public function change_password(Request $request)
    {
        $request->validate([
            'password'=>'required|min:8|max:191'
        ]);

        Admin::where('id',$request->admin_id_for_change_password)->update([
            'password' => Hash::make($request->password)
        ]);
        Auth::guard('admin')->logout();

        return back()->with(toastr_success(__('Password Successfully Changed.')));
    }
}
