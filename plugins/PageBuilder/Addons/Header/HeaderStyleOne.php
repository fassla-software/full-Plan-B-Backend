<?php


namespace plugins\PageBuilder\Addons\Header;

use App\Models\Order;
use App\Models\User;
use plugins\PageBuilder\Fields\ColorPicker;
use plugins\PageBuilder\Fields\Image;
use plugins\PageBuilder\Fields\Repeater;
use plugins\PageBuilder\Fields\Slider;
use plugins\PageBuilder\Fields\Text;
use plugins\PageBuilder\Helpers\RepeaterField;
use plugins\PageBuilder\PageBuilderBase;
use plugins\PageBuilder\Traits\LanguageFallbackForPageBuilder;
use Carbon\Carbon;


class HeaderStyleOne extends PageBuilderBase
{
    use LanguageFallbackForPageBuilder;

    public function preview_image()
    {
        return 'home-page/header-one.png';
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
                'name' => 'slider_image',
                'label' => __('Slider Image'),
                'value' => $widget_saved_values['slider_image'] ?? null,
                'dimensions' => '390x524'
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

        $background_image = render_background_image_markup_by_attachment_id($this->setting_item('background_image'));
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

        $top_freelancer = User::select('id','first_name','last_name','image')->where('user_type',2)->withCount(['freelancer_orders' => function ($query) {
            $query->where('created_at', '>=', Carbon::now()->subDay(30))->where('status',3);
        }])->orderBy('freelancer_orders_count', 'DESC')
            ->first();

        return $this->renderBlade('header.header-one',compact([
            'section_bg','padding_bottom',
            'padding_top','slider_image',
            'subtitle','title','repeater_data',
            'top_freelancer',
            'shape_image_one',
            'shape_image_two',
            'find_work_button_text',
            'find_work_button_link',
            'find_project_button_link',
            'find_project_button_text',
            'top_freelancer_of_the_month',
        ]));

}

    public function addon_title()
    {
        return __('Header: 01');
    }
}
