<?php


namespace plugins\PageBuilder\Addons\Common;

use plugins\PageBuilder\Fields\Slider;
use plugins\PageBuilder\Fields\Summernote;
use plugins\PageBuilder\Fields\Text;
use plugins\PageBuilder\Fields\Textarea;
use plugins\PageBuilder\Traits\LanguageFallbackForPageBuilder;
use plugins\PageBuilder\Fields\Repeater;
use plugins\PageBuilder\Helpers\RepeaterField;
use plugins\PageBuilder\Fields\Image;

class TextEditor extends \plugins\PageBuilder\PageBuilderBase
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


        $output .= Summernote::get([
            'name' => 'text_editor',
            'label' => __('Text Editor'),
            'value' => $widget_saved_values['raw_html'] ?? null,
        ]);
        $output .= Slider::get([
            'name' => 'padding_top',
            'label' => __('Padding Top'),
            'value' => $widget_saved_values['padding_top'] ?? 260,
            'max' => 500,
        ]);
        $output .= Slider::get([
            'name' => 'padding_bottom',
            'label' => __('Padding Bottom'),
            'value' => $widget_saved_values['padding_bottom'] ?? 190,
            'max' => 500,
        ]);

        $output .= $this->admin_form_submit_button();
        $output .= $this->admin_form_end();
        $output .= $this->admin_form_after();

        return $output;
    }


    public function frontend_render() : string
    {

        $settings = $this->get_settings();
        $text_editor = $this->setting_item('text_editor');
        $padding_top = $this->setting_item('padding_top');
        $padding_bottom = $this->setting_item('padding_bottom');


return <<<HTML
    <section class="text-editor-area" data-padding-top="{$padding_top}" data-padding-bottom="{$padding_bottom}">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                     {$text_editor}
                </div>
            </div>
        </div>
    </section>
HTML;

}

    public function addon_title()
    {
        return __('Text Editor');
    }
}
