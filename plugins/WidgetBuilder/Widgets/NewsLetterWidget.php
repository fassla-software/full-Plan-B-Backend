<?php

namespace plugins\WidgetBuilder\Widgets;

use plugins\PageBuilder\Fields\Repeater;
use plugins\PageBuilder\Fields\Text;
use plugins\PageBuilder\Fields\Textarea;
use plugins\PageBuilder\Helpers\RepeaterField;
use plugins\PageBuilder\Traits\LanguageFallbackForPageBuilder;
use plugins\WidgetBuilder\WidgetBase;

class NewsLetterWidget extends WidgetBase
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
        $submit = __('Submit');


        return <<<HTML
            <div class="col-lg-3 col-sm-6 mt-4">
                    <div class="footer-widget widget">
                        <h4 class="footer-widget-title">{$title}</h4>
                        <div class="footer-inner mt-4">
                            <p class="footer-widget-para"> {$description}</p>
                            <form action="#" method="post" id="newsletter_subscribe_from_footer">
                                <div class="footer-widget-form mt-4">
                                 <div class="error-message"></div>
                                    <div class="footer-widget-form-single">
                                        <input type="email" name="email" class="footer-widget-form-control" placeholder="Enter Your Email">
                                        <button class="subscription_by_email_newsletter"> {$submit} <i class="fas fa-arrow-right"></i> </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
        HTML;
    }

    public function widget_title()
    {
        return __('News Letter');
    }

}
