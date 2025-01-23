<?php


namespace plugins\PageBuilder\Addons\WhyChooseUs;

use plugins\PageBuilder\Fields\ColorPicker;
use plugins\PageBuilder\Fields\Image;
use plugins\PageBuilder\Fields\Slider;
use plugins\PageBuilder\Fields\Text;
use plugins\PageBuilder\Fields\Textarea;
use plugins\PageBuilder\PageBuilderBase;
use plugins\PageBuilder\Traits\LanguageFallbackForPageBuilder;
use plugins\PageBuilder\Fields\Repeater;
use plugins\PageBuilder\Helpers\RepeaterField;


class WhyChooseUs extends PageBuilderBase
{
    use LanguageFallbackForPageBuilder;

    public function preview_image()
    {
        return 'home-page/why-choose-us.png';
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
            'name' => 'mini_description',
            'label' => __('Mini Description'),
            'value' => $widget_saved_values['mini_description'] ?? null,
        ]);
        $output .= Image::get([
            'name' => 'shape_image_one',
            'label' => __('Thumb Shape Image'),
            'value' => $widget_saved_values['shape_image_one'] ?? null,
        ]);
        $output .= Image::get([
            'name' => 'shape_image_two',
            'label' => __('Shape Image'),
            'value' => $widget_saved_values['shape_image_two'] ?? null,
        ]);
        $output .= Image::get([
            'name' => 'thumbnail_image',
            'label' => __('Thumbnail Image'),
            'value' => $widget_saved_values['thumbnail_image'] ?? null,
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


        //repeater
        $output .= Repeater::get([
            'settings' => $widget_saved_values,
            'id' => 'why_choose_us',
            'fields' => [
                [
                    'type' => RepeaterField::TEXT,
                    'name' => 'title',
                    'label' => __('Title')
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
        $title =$settings['title'] ?? null;
        $subtitle =$settings['subtitle'] ?? null;
        $mini_description =$settings['mini_description'] ?? null;
        $shape_image_one =$settings['shape_image_one'] ?? null;
        $shape_image_two =$settings['shape_image_two'] ?? null;
        $thumbnail_image =$settings['thumbnail_image'] ?? null;
        $padding_top = $settings['padding_top'] ?? null;
        $padding_bottom = $settings['padding_bottom'] ?? null;
        $section_bg = $settings['section_bg'] ?? null;
        $repeater_data = $settings['why_choose_us'] ?? null;

        return $this->renderBlade('why-choose-us.why-choose-us',compact([
            'title',
            'subtitle',
            'mini_description',
            'shape_image_one',
            'shape_image_two',
            'thumbnail_image',
            'padding_top',
            'padding_bottom',
            'section_bg',
            'repeater_data'
        ]));
    }

    public function addon_title()
    {
        return __('Why Choose Us');
    }
}
