<?php


namespace plugins\PageBuilder\Fields;


use plugins\PageBuilder\Helpers\Traits\FieldInstanceHelper;
use plugins\PageBuilder\PageBuilderField;

class Switcher extends PageBuilderField
{
    use FieldInstanceHelper;

    /**
     * render field markup
     * */
    public function render()
    {
        //Implement render() method.
        $output = '';
        $output .= $this->field_before();
        $output .= $this->label();
        $checked = !empty($this->value()) ? 'checked' : '';
        $output .='<label class="switch"><input type="checkbox" name="'.$this->name().'"  '.$checked.' ><span class="slider"></span></label>';
        $output .= $this->field_after();

        return $output;
    }
}
