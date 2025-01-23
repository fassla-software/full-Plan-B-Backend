<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Widget;
use plugins\WidgetBuilder\WidgetBuilderSetup;
use Illuminate\Http\Request;

class WidgetBuilderController extends Controller
{
    public function widgets()
    {
        return view('backend.widgets.all-widgets');
    }

    public function widget_markup(Request $request){
        $output = WidgetBuilderSetup::render_widgets_by_name_for_admin([
            'name' => $request->widget_name,
            'type' => 'new',
            'after' => false,
            'before' => false,
        ]);
        return $output;
    }

    public function new_widget(Request $request){
        $this->validate($request,[
            'widget_name' => 'required',
            'widget_order' => 'required',
            'widget_location' => 'required',
        ]);

        unset($request['_token']);
        $widget_content = (array) $request->all();

        $widget_id =  Widget::create([
            'widget_name' => $request->widget_name,
            'widget_order' => $request->widget_order,
            'widget_location' => $request->widget_location,
            'widget_content' => serialize($widget_content),
        ])->id;
        $data['id'] = $widget_id;
        $data['status'] = 'ok';
        return response()->json($data);
    }
    public function update_widget(Request $request){
        $this->validate($request,[
            'widget_name' => 'required',
            'widget_order' => 'required',
            'widget_location' => 'required',
        ]);

        unset($request['_token']);
        $widget_content = (array) $request->all();

        Widget::findOrFail($request->id)->update([
            'widget_name' => $request->widget_name,
            'widget_order' => $request->widget_order,
            'widget_location' => $request->widget_location,
            'widget_content' => serialize($widget_content),
        ]);

        return response()->json('ok');
    }

    public function delete_widget(Request $request){
        Widget::findOrFail($request->id)->delete();
        return response()->json('ok');
    }

    public function update_order_widget(Request $request){
        Widget::findOrFail($request->id)->update(['widget_order' => $request->widget_order]);
        return response()->json('ok');
    }
}
