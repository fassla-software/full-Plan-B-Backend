<?php


namespace plugins\PageBuilder\Fields;


use plugins\PageBuilder\Helpers\Traits\FieldInstanceHelper;
use plugins\PageBuilder\PageBuilderField;

class IconPicker extends PageBuilderField
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
        $output .= $this->label('d-block');
        $output .= '<div class="btn-group ">';
        $output .= '<button type="button" class="btn btn-primary iconpicker-component"><i class="'.$this->value().'"></i></button>';
        $output .= '<button type="button" class="icp icp-dd btn btn-primary dropdown-toggle" data-selected="'.$this->value().'" data-bs-toggle="dropdown"><span class="caret"></span> <span class="sr-only">'.__('Toggle Dropdown').'</span> </button>';
        $output .= '<div class="dropdown-menu"></div></div>';
        $output .= '<input type="hidden" value="'.$this->value().'" name="'.$this->name().'"  class="'.$this->field_class().'"/>';
        $output .= $this->field_after();

        return $output;
    }
}
