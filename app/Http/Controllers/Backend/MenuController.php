<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Services\MenuBuilderService;
use App\Models\Menu;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function menu(Request $request)
        {
            if($request->isMethod('post')){
                return (new MenuBuilderService())->menu($request);
            }
            $all_menus = Menu::latest()->get();
            return view('backend.menus.all-menus',compact('all_menus'));
        }

    public function edit_menu($id)
    {
        $menu= Menu::find($id);
        return view('backend.menus.edit-menu',compact('menu'));
    }

    public function update_menu(Request $request, $id)
    {
        return (new MenuBuilderService())->update_menu($request,$id);
    }

    public function delete_menu($id)
    {
        return (new MenuBuilderService())->delete_menu($id);
    }

    public function set_default_menu(Request $request, $id)
    {
        return (new MenuBuilderService())->set_default_menu($request,$id);
    }

    public function mega_menu_item_select_markup(Request $request){

        return render_mega_menu_item_select_markup($request->type,$request->menu_id);
    }
}
