<?php

namespace plugins\PageBuilder\Addons\Contact;

use App\Helper\FormBuilderCustom;
use App\Models\FormBuilder;
use plugins\FormBuilder\SanitizeInput;
use plugins\PageBuilder\Fields\IconPicker;
use plugins\PageBuilder\Fields\Repeater;
use plugins\PageBuilder\Fields\Select;
use plugins\PageBuilder\Fields\Slider;
use plugins\PageBuilder\Fields\Text;
use plugins\PageBuilder\Helpers\RepeaterField;
use plugins\PageBuilder\PageBuilderBase;

class ContactMessage extends PageBuilderBase
{

    public function preview_image()
    {
        return 'contact-page/contact-form.png';
    }

    public function admin_render()
    {
        $output = $this->admin_form_before();
        $output .= $this->admin_form_start();
        $output .= $this->default_fields();
        $widget_saved_values = $this->get_settings();

        $output .= Text::get([
            'name' => 'heading',
            'label' => __('Contact Form Heading'),
            'value' => $widget_saved_values['heading'] ?? __('Heading'),
            'max' => 500,
        ]);
        $output .= Text::get([
            'name' => 'contact_form_des',
            'label' => __('Contact Form Description'),
            'value' => $widget_saved_values['title'] ?? 260,
            'max' => 500,
        ]);

        $output .= Text::get([
            'name' => 'title',
            'label' => __('Contact Info Heading'),
            'value' => $widget_saved_values['title'] ?? 260,
            'max' => 500,
        ]);
        $output .= Text::get([
            'name' => 'contact_info_des',
            'label' => __('Contact Info Description'),
            'value' => $widget_saved_values['title'] ?? 260,
            'max' => 500,
        ]);


        //repeater
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
                    'name' => 'title',
                    'label' => __('Title')
                ],
                [
                    'type' => RepeaterField::TEXTAREA,
                    'name' => 'description',
                    'label' => __('Details'),
                ],

            ]
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
        $output .= Select::get([
            'name' => 'custom_form_id',
            'label' => __('Custom Form'),
            'placeholder' => __('Select form'),
            'options' => FormBuilder::all()->pluck('title','id')->toArray(),
            'value' =>   $widget_saved_values['custom_form_id'] ?? []
        ]);

        $output .= $this->admin_form_submit_button();
        $output .= $this->admin_form_end();
        $output .= $this->admin_form_after();

        return $output;

    }

    public function frontend_render()
    {

        $settings = $this->get_settings();

        $heading = SanitizeInput::esc_html($this->setting_item('heading'));
        $contact_info_heading = SanitizeInput::esc_html($this->setting_item('title'));
        $custom_form_id = SanitizeInput::esc_html($this->setting_item('custom_form_id') ?? '');
        $padding_top = $settings['padding_top'];
        $padding_bottom = $settings['padding_bottom'];
        $repeater_data = $settings['contact_info'] ?? '';
        $contact_info_des = $settings['contact_info_des'] ?? '';
        $contact_form_des = $settings['contact_form_des'] ?? '';


        $form = FormBuilder::find($custom_form_id ?? 0);
        $form_details =  FormBuilderCustom::render_form(optional($form)->id,null,null,'btn-default');

        return $this->renderBlade('contact-page.form',compact(['heading','contact_info_heading', 'custom_form_id','form','padding_top','padding_bottom', 'form_details','repeater_data','contact_info_des','contact_form_des']));
    }

    public function addon_title()
    {
        return __('Contact Form');
    }
}
