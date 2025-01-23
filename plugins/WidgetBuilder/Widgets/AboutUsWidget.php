<?php

namespace plugins\WidgetBuilder\Widgets;
use plugins\FormBuilder\SanitizeInput;
use plugins\PageBuilder\Fields\Repeater;
use plugins\PageBuilder\Fields\Summernote;
use plugins\PageBuilder\Fields\Text;
use plugins\PageBuilder\Helpers\RepeaterField;
use plugins\PageBuilder\Traits\LanguageFallbackForPageBuilder;
use plugins\WidgetBuilder\WidgetBase;
use plugins\PageBuilder\Fields\Textarea;
use plugins\PageBuilder\Fields\Image;

class AboutUsWidget extends WidgetBase
{
    use LanguageFallbackForPageBuilder;

    public function admin_render()
    {
        $output = $this->admin_form_before();
        $output .= $this->admin_form_start();
        $output .= $this->default_fields();
        $widget_saved_values = $this->get_settings();

        $output .= Text::get([
            'name' => 'title',
            'label' => __('Title'),
            'value' => $widget_saved_values['title'] ?? null,
        ]);

        $output .= Repeater::get([
            'settings' => $widget_saved_values,
            'id' => 'menu_link',
            'fields' => [
                [
                    'type' => RepeaterField::TEXT,
                    'name' => 'list_item',
                    'label' => __('List Item')
                ],
                [
                    'type' => RepeaterField::TEXT,
                    'name' => 'url',
                    'label' => __('Url')
                ],
            ]]);

        $output .= $this->admin_form_submit_button();
        $output .= $this->admin_form_end();
        $output .= $this->admin_form_after();

        return $output;
    }

    public function frontend_render()
    {
        $settings = $this->get_settings();
        $title = purify_html($settings['title']);

        $repeater_data = $settings['menu_link'];
        $item_list_markup = '';

        foreach ($repeater_data['list_item_'] as $key => $list) {
            $list = SanitizeInput::esc_html($list);
            $url = SanitizeInput::esc_html($repeater_data['url_'][$key]);
            $item_list_markup.= <<<ITEMLIST
                <li class="footer-widget-link-list-item">
                        <a href="{$url}">{$list}</a>
                </li>
            ITEMLIST;
        }

        return <<<HTML
        <div class="col-lg-3 col-sm-6 mt-4">
            <div class="footer-widget widget">
                <h4 class="footer-widget-title">{$title}</h4>
                <div class="footer-widget-inner mt-4">
                    <ul class="footer-widget-link-list">
                        {$item_list_markup}
                    </ul>
                </div>
            </div>
        </div>
        HTML;
    }

    public function widget_title()
    {
        return __('About Us');
    }

}
