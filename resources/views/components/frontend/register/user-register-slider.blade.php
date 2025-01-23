<div class="login-right-item">
    <div class="login-right-shapes">
        <div class="login-right-thumb">
            @if(empty(get_static_option('register_page_sidebar_image')))
                <img src="{{ asset('assets/static/single-page/fr_1.png') }}" alt="loginImg">
            @else
                {!! render_image_markup_by_attachment_id(get_static_option('register_page_sidebar_image')) !!}
            @endif
        </div>
    </div>
    <div class="login-right-contents text-white">
        <h4 class="login-right-contents-title"> {{ get_static_option('register_page_sidebar_title') ?? __('Register and start discover') }} </h4>
        <p class="login-right-contents-para">{{ get_static_option('register_page_sidebar_description') ?? __('Once register you will see the magic of xilancer marketplace.') }}</p>
    </div>
</div>
