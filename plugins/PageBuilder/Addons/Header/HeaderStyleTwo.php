<?php


namespace plugins\PageBuilder\Addons\Header;

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


class HeaderStyleTwo extends PageBuilderBase
{
    use LanguageFallbackForPageBuilder;

    public function preview_image()
    {
        return 'home-page/header-two.png';
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
            'name' => 'find_project_button_text',
            'label' => __('Find Project Button Text'),
            'value' => $widget_saved_values['find_project_button_text'] ?? null,
        ]);
        $output .= Text::get([
            'name' => 'find_project_button_link',
            'label' => __('Find Project Button Link'),
            'value' => $widget_saved_values['find_project_button_link'] ?? null,
        ]);
        $output .= Text::get([
            'name' => 'top_freelancer_of_the_month',
            'label' => __('Top Freelancer of the Month Text'),
            'value' => $widget_saved_values['top_freelancer_of_the_month'] ?? null,
        ]);

        $output .= Image::get([
            'name' => 'shape_image_one',
            'label' => __('Shape Image One'),
            'value' => $widget_saved_values['shape_image_one'] ?? null,
            'dimensions' => '50x53'
        ]);
        $output .= Image::get([
            'name' => 'shape_image_two',
            'label' => __('Shape Image Two'),
            'value' => $widget_saved_values['shape_image_two'] ?? null,
            'dimensions' => '50x53'
        ]);

        $output .= Image::get([
            'name' => 'light_image',
            'label' => __('Complete Project Mini Image'),
            'value' => $widget_saved_values['light_image'] ?? null,
            'dimensions' => '50x53'
        ]);

        $output .= Image::get([
            'name' => 'freelancer_image',
            'label' => __('Complete Project Freelancer Image'),
            'value' => $widget_saved_values['freelancer_image'] ?? null,
            'dimensions' => '196x195'
        ]);
        $output .= Number::get([
            'name' => 'freelancer_id',
            'label' => __('Freelancer ID'),
            'value' => $widget_saved_values['freelancer_id'] ?? null,
            'info' => __('enter freelancer id you want to show in frontend'),
        ]);

        $output .= Image::get([
            'name' => 'talent_image',
            'label' => __('Hired Talent Mini Image'),
            'value' => $widget_saved_values['talent_image'] ?? null,
            'dimensions' => '50x53'
        ]);
        $output .= Image::get([
            'name' => 'client_image',
            'label' => __('Client Image'),
            'value' => $widget_saved_values['client_image'] ?? null,
            'dimensions' => '196x195'
        ]);
        $output .= Number::get([
            'name' => 'client_id',
            'label' => __('Client ID'),
            'value' => $widget_saved_values['client_id'] ?? null,
            'info' => __('enter client id you want to show in frontend'),
        ]);


        $output .= Image::get([
            'name' => 'background_image',
            'label' => __('Background Image'),
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


        $output .= Repeater::get([
            'settings' => $widget_saved_values,
            'id' => 'trusted_by',
            'fields' => [
                [
                    'type' => RepeaterField::IMAGE,
                    'name' => 'logo',
                    'label' => __('Logo')
                ],

            ]
        ]);

        $output .= $this->admin_form_submit_button();
        $output .= $this->admin_form_end();
        $output .= $this->admin_form_after();

        return $output;
    }

    public function frontend_render() : string
    {
        $settings = $this->get_settings();

        $background_image = $this->setting_item('background_image') ?? '';

        $title = $settings['title'] ?? null;
        $subtitle = $settings['subtitle'] ?? null;
        $find_work_button_text = $settings['find_work_button_text'] ?? null;
        $find_work_button_link = $settings['find_work_button_link'] ?? null;
        $find_project_button_text = $settings['find_project_button_text'] ?? null;
        $find_project_button_link = $settings['find_project_button_link'] ?? null;
        $top_freelancer_of_the_month = $settings['top_freelancer_of_the_month'] ?? null;
        $padding_top = $settings['padding_top'];
        $padding_bottom = $settings['padding_bottom'];
        $section_bg = $settings['section_bg'];
        $repeater_data = $settings['trusted_by'];
        $slider_image = $settings['slider_image'] ?? '';
        $shape_image_one = $settings['shape_image_one'] ?? '';
        $shape_image_two = $settings['shape_image_two'] ?? '';
        $light_image = $settings['light_image'] ?? '';
        $freelancer_image = $settings['freelancer_image'] ?? '';
        $freelancer_id = $settings['freelancer_id'] ?? '';
        $talent_image = $settings['talent_image'] ?? '';
        $client_image = $settings['client_image'] ?? '';
        $client_id = $settings['client_id'] ?? '';
        $freelancer_order_count='';
        $client_order_count='';

        $find_freelancer = User::select('id','image')->where('id',$freelancer_id)->where('user_type',2)->first();
        if($find_freelancer){
            $freelancer_order_count = Order::where('freelancer_id', $freelancer_id)->where('status',3)->count();
        }

        $find_client = User::select('id','image')->where('id',$client_id)->where('user_type',1)->first();
        if($find_client){
            $client_order_count = Order::where('user_id', $client_id)->where('status',3)->count();
        }

        return $this->renderBlade('header.header-two',compact([
            'section_bg','padding_bottom',
            'padding_top','slider_image',
            'subtitle','title','repeater_data',
            'shape_image_one',
            'shape_image_two',
            'light_image',
            'freelancer_image',
            'freelancer_id',
            'talent_image',
            'client_image',
            'client_id',
            'find_work_button_text',
            'find_work_button_link',
            'find_project_button_text',
            'find_project_button_link',
            'top_freelancer_of_the_month',
            'background_image',
            'freelancer_order_count',
            'client_order_count',
        ]));

    }

    public function addon_title()
    {
        return __('Header: 02');
    }
}
