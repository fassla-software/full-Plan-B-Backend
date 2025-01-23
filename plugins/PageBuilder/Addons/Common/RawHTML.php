<?php


namespace plugins\PageBuilder\Addons\Common;

use plugins\PageBuilder\Fields\Slider;
use plugins\PageBuilder\Fields\Text;
use plugins\PageBuilder\Fields\Textarea;
use plugins\PageBuilder\Traits\LanguageFallbackForPageBuilder;
use plugins\PageBuilder\Fields\Repeater;
use plugins\PageBuilder\Helpers\RepeaterField;
use plugins\PageBuilder\Fields\Image;

class RawHTML extends \plugins\PageBuilder\PageBuilderBase
{
    use LanguageFallbackForPageBuilder;

    public function preview_image()
    {
        return '';
    }

    public function admin_render()
    {
        $output = $this->admin_form_before();
        $output .= $this->admin_form_start();
        $output .= $this->default_fields();
        $widget_saved_values = $this->get_settings();


        $output .= Textarea::get([
            'name' => 'raw_html',
            'label' => __('Raw HTML'),
            'value' => $widget_saved_values['raw_html'] ?? null,
        ]);
        $output .= $this->admin_form_submit_button();
        $output .= $this->admin_form_end();
        $output .= $this->admin_form_after();

        return $output;
    }


    public function frontend_render() : string
    {

        $settings = $this->get_settings();
        $raw_html = $settings['raw_html'];


return <<<HTML
   {$raw_html}
HTML;

}

    public function addon_title()
    {
        return __('Raw HTML');
    }
}
