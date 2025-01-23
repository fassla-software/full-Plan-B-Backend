<!-- Add Portfolio Popup Starts -->
<div class="popup-fixed portfolioadd-popup">
    <div class="popup-contents">
        <span class="popup-contents-close popup-close"> <i class="fas fa-times"></i> </span>
        <h5 class="popup-contents-title">{{ __('Add Portfolio') }}</h5>
        <div class="error_msg_container mb-3"></div>
        <form action="#" id="add_portfolio_form">
            <div class="photo-uploaded photo-uploaded-padding center-text mt-4">
                <div class="photo-up loaded-flex uploadImage">
                    <div class="photo-uploaded-icon">
                        <img src="" class="portfolio_photo_preview">
                    </div>
                    <span class="create-project-wrapper-upload-browse-icon mt-3">
                        <i class="fa-solid fa-image"></i>
                    </span>
                    <span class="create-project-wrapper-upload-browse-para change_image_text"> {{ __('Click to upload portfolio image') }} </span>
                </div>
                <input class="photo-uploaded-file" type="file" name="image" id="upload_portfolio_photo">
                <span><strong>{{ __('info:') }}</strong>{{ __('dimensions must be: 590x440') }}</span>
            </div>

            <div class="popup-contents-form custom-form mt-4">
                <x-form.text :title="__('Title')" :type="'text'" :name="'portfolio_title'" :id="'portfolio_title'" :divClass="'mb-0'" :class="'form--control'" :placeholder="__('Write Project Title')" />
                <span id="portfolio_title_char_length_check"></span>
                <x-form.textarea :title="__('Description')" :name="'portfolio_description'" :id="'portfolio_description'" :divClass="'mb-0'" :class="'form-message'" :placeholder="__('Type Project Details')" />
                <span id="portfolio_description_char_length_check"></span>
            </div>
            <div class="popup-contents-btn flex-btn justify-content-end profile-border-top">
                <x-btn.close :title="__('Cancel')" :class="'btn-profile btn-outline-gray btn-hover-danger popup-close'" />
                <x-btn.submit :title="__('Save')" :class="'btn-profile btn-bg-1 add_portfolio'" />
            </div>
        </form>
    </div>
</div>
<!-- Add Portfolio Popup Ends -->
