<?php

namespace Modules\RolePermission\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionController extends Controller
{
    public function all_role(Request $request)
    {
        if($request->isMethod('post')){
            $request->validate(['role'=>'required|unique:roles,name']);
            Role::create([
                'name' => $request->role,
                'guard_name' => 'admin',
            ]);
            toastr_success(__('Role Successfully Created'));
        }
        $roles = Role::all();
        return view('rolepermission::roles.all-roles',compact('roles'));
    }

    public function edit_role(Request $request)
    {
        $request->validate(['role_name'=>'required|unique:roles,name,'.$request->role_id]);
        Role::create([
            'name' => $request->role_name,
        ]);
        return back()->with(toastr_success(__('Role Successfully Updated')));
    }

    public function permission($id)
    {
        $role = Role::with("permissions")->find($id);
        $permissions = Permission::orderBy("menu_name","asc")->get();
        $permissions = $permissions->groupBy("menu_name");

        $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id",$id)
            ->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')
            ->all();

        return view('rolepermission::roles.permissions',compact(['role','permissions','rolePermissions']));
    }

    public function create_permission(Request $request,$id)
    {
        $role = Role::find($id);
        $role->syncPermissions($request->permission);
        return back()->with(toastr_success(__('Permission Successfully Synced with Role')));
    }

    public function delete_role($id)
    {
        $role = Role::find($id);
        $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id",$id)
            ->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')
            ->all();
        $role->revokePermissionTo($rolePermissions);
        $role->delete();
        return back()->with(toastr_success(__('Role Successfully Deleted')));
    }
}
