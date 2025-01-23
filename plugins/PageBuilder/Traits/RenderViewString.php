<?php


namespace plugins\PageBuilder\Traits;


trait RenderViewString
{
    public function renderBlade($blade_name, $data = []) : string
    {
        $data = array_merge($data, ['settings' => $this->get_settings()]);
        return view('pagebuilder::' . $blade_name, $data)->render();
    }

    public function renderBlade2($blade_name, $data = []) : string
    {
        $data = array_merge($data, ['settings' => $this->get_settings()]);
        return view('widgetbuilder::' . $blade_name, $data)->render();
    }
}
