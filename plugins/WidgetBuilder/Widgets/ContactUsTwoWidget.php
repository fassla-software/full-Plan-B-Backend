<?php

namespace plugins\WidgetBuilder\Widgets;

use plugins\FormBuilder\SanitizeInput;
use plugins\PageBuilder\Fields\Repeater;
use plugins\PageBuilder\Fields\Text;
use plugins\PageBuilder\Fields\Textarea;
use plugins\PageBuilder\Helpers\RepeaterField;
use plugins\PageBuilder\Traits\LanguageFallbackForPageBuilder;
use plugins\WidgetBuilder\WidgetBase;

class ContactUsTwoWidget extends WidgetBase
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
        $output .= Textarea::get([
            'name' => 'description',
            'label' => __('Description'),
            'value' => $widget_saved_values['description'] ?? null,
        ]);

        $output .= Repeater::get([
            'settings' => $widget_saved_values,
            'id' => 'contact_info',
            'fields' => [
                [
                    'type' => RepeaterField::ICON_PICKER,
                    'name' => 'icon',
                    'label' => __('Icon')
                ],
                [
                    'type' => RepeaterField::TEXT,
                    'name' => 'info',
                    'label' => __('Contact Info')
                ],
            ]
        ]);

        $output .= $this->admin_form_submit_button();
        $output .= $this->admin_form_end();
        $output .= $this->admin_form_after();

        return $output;
    }

    public function frontend_render()
    {
        $settings = $this->get_settings();
        $title = purify_html($settings['title']);
        $description = purify_html($settings['description']);

        $repeater_data = $settings['contact_info'];
        $contact_info_markup = '';

        foreach ($repeater_data['icon_'] as $key => $icon) {
            $icon = SanitizeInput::esc_html($icon);
            $info = SanitizeInput::esc_html($repeater_data['info_'][$key]);
            $contact_info_markup .= <<<CONTACTINFO
             <div class="footer-widget-contact-item">
                <div class="footer-widget-contact-item-flex">
                    <div class="footer-widget-contact-item-icon">
                        <i class="{$icon}"></i>
                    </div>
                    <div class="footer-widget-contact-item-details">
                        <span class="footer-widget-contact-item-details-title"> $info </span>
                    </div>
                </div>
            </div>

        CONTACTINFO;
        }

        return <<<HTML
            <div class="col-lg-3 col-sm-6 mt-4">
                    <div class="footer-widget widget">
                        <h4 class="footer-widget-title">{$title}</h4>
                        <div class="footer-widget-inner mt-4">
                            <p class="footer-widget-para">{$description}</p>
                            <div class="footer-widget-contact mt-4">
                                {$contact_info_markup}
                            </div>
                        </div>
                    </div>
                </div>
        HTML;
    }

    public function widget_title()
    {
        return __('Contact Us Two');
    }

}
