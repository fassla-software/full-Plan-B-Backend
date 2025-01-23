<?php


namespace plugins\PageBuilder\Addons\Mobilica;

use App\Models\Order;
use App\Models\User;
use plugins\PageBuilder\Fields\ColorPicker;
use plugins\PageBuilder\Fields\Image;
use plugins\PageBuilder\Fields\Number;
use plugins\PageBuilder\Fields\Repeater;
use plugins\PageBuilder\Fields\Select;
use plugins\PageBuilder\Fields\Slider;
use plugins\PageBuilder\Fields\Text;
use plugins\PageBuilder\Helpers\RepeaterField;
use plugins\PageBuilder\PageBuilderBase;
use plugins\PageBuilder\Traits\LanguageFallbackForPageBuilder;
use Carbon\Carbon;


class Mobilica extends PageBuilderBase
{
    use LanguageFallbackForPageBuilder;

    public function preview_image()
    {
        return 'home-page/mobile-app.png';
    }

    public function admin_render()
    {
        $output = $this->admin_form_before();
        $output .= $this->admin_form_start();
        $output .= $this->default_fields();
        $widget_saved_values = $this->get_settings();


        $output .= Text::get([
            'name' => 'free_app_store_title',
            'label' => __('Freelancer App Store Title'),
            'value' => $widget_saved_values['free_app_store_title'] ?? null,
        ]);

        $output .= Image::get([
            'name' => 'free_app_store_image',
            'label' => __('Freelancer App Store Image'),
            'value' => $widget_saved_values['free_app_store_image'] ?? null,
        ]);
        $output .= Text::get([
            'name' => 'free_app_store_link',
            'label' => __('Freelancer App Store Link'),
            'value' => $widget_saved_values['free_app_store_link'] ?? null,
        ]);
        $output .= Image::get([
            'name' => 'free_app_play_store_image',
            'label' => __('Freelancer App Play Store Image'),
            'value' => $widget_saved_values['free_app_play_store_image'] ?? null,
        ]);
        $output .= Text::get([
            'name' => 'free_app_play_store_link',
            'label' => __('Freelancer App Play Store Link'),
            'value' => $widget_saved_values['free_app_play_store_link'] ?? null,
        ]);
        $output .= Image::get([
            'name' => 'free_app_store_shape',
            'label' => __('Freelancer App Store Shape'),
            'value' => $widget_saved_values['free_app_store_shape'] ?? null,
            'dimensions' => '385x257'
        ]);
        $output .= Image::get([
            'name' => 'free_app_store_phone',
            'label' => __('Freelancer App Store Phone'),
            'value' => $widget_saved_values['free_app_store_phone'] ?? null,
            'dimensions' => '240x330'
        ]);


        $output .= Text::get([
            'name' => 'client_app_store_title',
            'label' => __('Client App Store Title'),
            'value' => $widget_saved_values['client_app_store_title'] ?? null,
        ]);
        $output .= Image::get([
            'name' => 'client_app_store_image',
            'label' => __('Client App Store Image'),
            'value' => $widget_saved_values['client_app_store_image'] ?? null,
        ]);
        $output .= Text::get([
            'name' => 'client_app_store_link',
            'label' => __('Client App Store Link'),
            'value' => $widget_saved_values['client_app_store_link'] ?? null,
        ]);
        $output .= Image::get([
            'name' => 'client_app_play_store_image',
            'label' => __('Client App Play Store Image'),
            'value' => $widget_saved_values['client_app_play_store_image'] ?? null,
        ]);
        $output .= Text::get([
            'name' => 'client_app_play_store_link',
            'label' => __('Client App Play Store Link'),
            'value' => $widget_saved_values['client_app_play_store_link'] ?? null,
        ]);
        $output .= Image::get([
            'name' => 'client_app_store_shape',
            'label' => __('Client App Store Shape'),
            'value' => $widget_saved_values['client_app_store_shape'] ?? null,
            'dimensions' => '385x257'
        ]);
        $output .= Image::get([
            'name' => 'client_app_store_phone',
            'label' => __('Client App Store Phone'),
            'value' => $widget_saved_values['client_app_store_phone'] ?? null,
            'dimensions' => '240x330'
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
        $output .= ColorPicker::get([
            'name' => 'section_bg',
            'label' => __('Background Color'),
            'value' => $widget_saved_values['section_bg'] ?? null,
            'info' => __('select color you want to show in frontend'),
        ]);


        $output .= $this->admin_form_submit_button();
        $output .= $this->admin_form_end();
        $output .= $this->admin_form_after();

        return $output;
    }

    public function frontend_render() : string
    {
        $settings = $this->get_settings();

        $free_app_store_title = $settings['free_app_store_title'] ?? null;
        $free_app_store_image = $settings['free_app_store_image'] ?? null;
        $free_app_store_link = $settings['free_app_store_link'] ?? null;
        $free_app_play_store_image = $settings['free_app_play_store_image'] ?? null;
        $free_app_play_store_link = $settings['free_app_play_store_link'] ?? null;
        $free_app_store_shape = $settings['free_app_store_shape'] ?? null;
        $free_app_store_phone = $settings['free_app_store_phone'] ?? null;

        $client_app_store_title = $settings['client_app_store_title'] ?? null;
        $client_app_store_image = $settings['client_app_store_image'] ?? null;
        $client_app_store_link = $settings['client_app_store_link'] ?? null;
        $client_app_play_store_image = $settings['client_app_play_store_image'] ?? null;
        $client_app_play_store_link = $settings['client_app_play_store_link'] ?? null;
        $client_app_store_shape = $settings['client_app_store_shape'] ?? null;
        $client_app_store_phone = $settings['client_app_store_phone'] ?? null;

        $padding_top = $settings['padding_top'] ?? null;
        $padding_bottom = $settings['padding_bottom'] ?? null;
        $section_bg = $settings['section_bg'] ?? null;


        return $this->renderBlade('mobiapplica.mobiapplica',compact([
            'section_bg',
            'padding_bottom',
            'padding_top',
            'free_app_store_title',
            'free_app_store_image',
            'free_app_store_link',
            'free_app_play_store_image',
            'free_app_play_store_link',
            'free_app_store_shape',
            'free_app_store_phone',
            'client_app_store_title',
            'client_app_store_image',
            'client_app_store_link',
            'client_app_play_store_image',
            'client_app_play_store_link',
            'client_app_store_shape',
            'client_app_store_phone',
        ]));

    }

    public function addon_title()
    {
        return __('App Store');
    }
}
