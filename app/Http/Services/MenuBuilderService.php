<?php

namespace App\Http\Services;

use App\Models\Menu;

class MenuBuilderService
{
    public function menu($request)
    {
        if($request->isMethod('post')){
            $request->validate([
                'content' => 'nullable',
                'title' => 'required',
            ]);

            Menu::create([
                'content' => $request->page_content,
                'title' => $request->title,
            ]);
            toastr_success(__('Menu Added Successfully.'));
            return back();
        }
    }

    public function update_menu($request,$id)
    {
        $request->validate([
            'content' => 'nullable',
            'title' => 'required',
        ]);
        Menu::where('id', $id)->update([
            'content' => $request->menu_content,
            'title' => $request->title,
        ]);
        toastr_success(__('Menu Updated Successfully.'));
        return back();
    }

    public function delete_menu($id)
    {
        Menu::find($id)->delete();
        toastr_error(__('Menu Deleted Successfully.'));
        return back();
    }

    public function set_default_menu($request,$id){
        $menu = Menu::find($id);
        Menu::where(['status' => 'default'])->update(['status' => '']);

        Menu::find($id)->update(['status' => 'default']);
        $menu->status = 'default';
        $menu->save();
        toastr_success(__('Default Menu Set To')  .' '. purify_html($menu->title));
        return back();
    }
}
