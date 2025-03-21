<?php


namespace plugins\PageBuilder\Fields;


use plugins\PageBuilder\Helpers\Traits\FieldInstanceHelper;
use plugins\PageBuilder\PageBuilderField;

class Video extends PageBuilderField
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
        $image_upload_btn_label  = __('Upload Video');
        $output .= '<div class="media-upload-btn-wrapper"> <div class="img-wrap">';
        $img = !empty($this->value()) ? get_attachment_image_by_id($this->value(),'medium',false) : '';
        if (!empty($img)){
            $output .= ' <div class="rmv-span"><i class="fas fa-trash"></i></div>';
            $output .= '<div class="attachment-preview"><div class="thumbnail"><div class="centered">';
            $output .= ' <video autoplay loop muted plays-inline class="back-video"><source class="avatar user-thumb" src="'.$img['img_url'].'" type="video/mp4" /></video>';
            $output .= '</div></div></div>';
            $image_upload_btn_label = __('Change Video');
        }
        $output .= '</div><br>';
        $output .= '<input type="hidden" value="'.$this->value().'" name="'.$this->name().'" />';
        $output .= ' <button type="button" class="btn btn-info media_upload_form_btn" data-btntitle="'.__('Select Image').'" data-modaltitle="'.__('Upload Video').'" data-imgid="'.$this->value().'" data-bs-toggle="modal" data-bs-target="#media_upload_modal">'.$image_upload_btn_label.'</button>';
        $output .= '</div>';

        if (isset( $this->args['dimensions'])){
            $output .= '<small>'.__('recommended image size is').' '. $this->args['dimensions'].'</small>';
        }

        $output .= $this->field_after();

        return $output;
    }
}
