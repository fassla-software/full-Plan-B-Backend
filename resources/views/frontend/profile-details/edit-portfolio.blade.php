<!-- Add Portfolio Popup Starts -->
<div class="popup-fixed portfolio_edit_popup">
    <div class="popup-contents">
        <span class="popup-contents-close popup-close"> <i class="fas fa-times"></i> </span>
        <h5 class="popup-contents-title">{{ __('Edit Portfolio') }}</h5>
        <div class="error_msg_container mb-3"></div>
        <form action="#" id="edit_portfolio_form">
            <input type="hidden" name="edit_portfolio_id" id="edit_portfolio_id">
            <div class="photo-uploaded photo-uploaded-padding center-text mt-4">
                <div class="photo-up loaded-flex uploadImage">
                    <div class="photo-uploaded-icon">
                        <img src="" id="portfolio_target_img" class="edit_portfolio_photo_preview">
                    </div>
                    <span class="create-project-wrapper-upload-browse-icon mt-3"><i class="fa-solid fa-image"></i></span>
                    <span class="create-project-wrapper-upload-browse-para"> {{ __('Click to change photo') }} </span>
                </div>
                <input class="photo-uploaded-file" type="file" name="edit_image" id="change_portfolio_photo">
                <small class="text-info">{{ __('dimensions must be: 590x440') }}</small>
            </div>

            <div class="popup-contents-form custom-form mt-4">
                <x-form.text :title="__('Title')" :type="'text'" :name="'edit_portfolio_title'" :id="'edit_portfolio_title'" :divClass="'mb-0'" :class="'form--control'" :placeholder="__('Write Project Title')" />
                <span id="edit_portfolio_title_char_length_check"></span>
                <x-form.textarea :title="__('Description')" :name="'edit_portfolio_description'" :id="'edit_portfolio_description'" :divClass="'mb-0'" :class="'form-message'" :placeholder="__('Type Project Details')" />
                <span id="edit_portfolio_description_char_length_check"></span>
            </div>
            <div class="popup-contents-btn flex-btn justify-content-end profile-border-top">
                <x-btn.close :title="__('Cancel')" :class="'btn-profile btn-outline-gray btn-hover-danger popup-close'" />
                <x-btn.submit :title="__('Update')" :class="'btn-profile btn-bg-1 edit_portfolio'" />
            </div>
        </form>
    </div>
</div>
<!-- Add Portfolio Popup Ends -->
