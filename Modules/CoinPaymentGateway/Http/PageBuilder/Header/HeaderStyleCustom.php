<?php


namespace Modules\CoinPaymentGateway\Http\PageBuilder\Header;

use App\Service;
use plugins\PageBuilder\Fields\ColorPicker;
use plugins\PageBuilder\Fields\Slider;
use plugins\PageBuilder\Fields\Text;
use plugins\PageBuilder\Fields\Video;
use plugins\PageBuilder\PageBuilderBase;
use plugins\PageBuilder\Traits\LanguageFallbackForPageBuilder;


class HeaderStyleCustom extends PageBuilderBase
{
    use LanguageFallbackForPageBuilder;

    public function preview_image()
    {
        return 'home-page/header-three.png';
    }

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
        $output .= Text::get([
            'name' => 'subtitle',
            'label' => __('Subtitle'),
            'value' => $widget_saved_values['subtitle'] ?? null,
        ]);
        $output .= Text::get([
            'name' => 'find_work_button_text',
            'label' => __('Find Work Button Text'),
            'value' => $widget_saved_values['find_work_button_text'] ?? null,
        ]);
        $output .= Text::get([
            'name' => 'find_work_button_link',
            'label' => __('Find Work Button Link'),
            'value' => $widget_saved_values['find_work_button_link'] ?? null,
        ]);
        $output .= Text::get([
            'name' => 'find_talent_button_text',
            'label' => __('Find Talent Button Text'),
            'value' => $widget_saved_values['find_talent_button_text'] ?? null,
        ]);
        $output .= Text::get([
            'name' => 'find_talent_button_link',
            'label' => __('Find Talent Button Link'),
            'value' => $widget_saved_values['find_talent_button_link'] ?? null,
        ]);
        $output .= Text::get([
            'name' => 'highlighted_banner_text',
            'label' => __('Highlighted Banner Text'),
            'value' => $widget_saved_values['highlighted_banner_text'] ?? null,
        ]);

        $output .= Video::get([
            'name' => 'background_image',
            'label' => __('Background Video'),
            'value' => $widget_saved_values['background_image'] ?? null,
            'dimensions' => '1920x1080'
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

        $background_video = render_background_video_markup_by_attachment_id($this->setting_item('background_image'));
        $title = $settings['title'] ?? null;
        $subtitle = $settings['subtitle'] ?? null;
        $highlighted_banner_text = $settings['highlighted_banner_text'] ?? null;
        $find_work_button_text = $settings['find_work_button_text'] ?? null;
        $find_work_button_link = $settings['find_work_button_link'] ?? null;
        $find_talent_button_text = $settings['find_talent_button_text'] ?? null;
        $find_talent_button_link = $settings['find_talent_button_link'] ?? null;
        $padding_top = $settings['padding_top'];
        $padding_bottom = $settings['padding_bottom'];
        $section_bg = $settings['section_bg'];


        return $this->renderBlade('header.header-three',compact([
            'section_bg',
            'padding_bottom',
            'padding_top',
            'background_video',
            'subtitle',
            'title',
            'highlighted_banner_text',
            'find_work_button_text',
            'find_work_button_link',
            'find_talent_button_text',
            'find_talent_button_link',
        ]));

    }

    public function addon_title()
    {
        return __('Header: 03');
    }
}
