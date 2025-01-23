<?php


namespace plugins\PageBuilder\Fields;


use plugins\PageBuilder\Helpers\Traits\FieldInstanceHelper;
use plugins\PageBuilder\PageBuilderField;

class DatePicker extends PageBuilderField
{
    use FieldInstanceHelper;

    /**
     * render field markup
     * */
    public function render()
    {
        $output = '';
        $output .= $this->field_before();

        $output .= $this->label('d-block');
        $output .= '<input type="date" value="'.$this->value().'" name="'.$this->name().'"  class="datepicker '.$this->field_class().'"/>';
        $output .= $this->field_after();

        return $output;
    }
}
