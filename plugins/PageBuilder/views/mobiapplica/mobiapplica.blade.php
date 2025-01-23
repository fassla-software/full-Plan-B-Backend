<!-- appStore area starts -->
<section class="appStore-area pat-100 pab-100">
    <div class="container">
        <div class="row gy-5 justify-content-center">
            <div class="col-xl-6 col-lg-9">
                <div class="single-appStore">
                    <div class="single-appStore-flex">
                        <div class="single-appStore-contents">
                            @if($free_app_store_title)
                            <h3 class="single-appStore-contents-title">
                                <a href="javascript:void(0)">{{ $free_app_store_title  }}</a>
                            </h3>
                            @endif
                            <div class="single-appStore-btn flex-btn gap-2 mt-4">
                                @if($free_app_store_image)
                                <a href="{{ $free_app_store_link }}">
                                    {!! render_image_markup_by_attachment_id($free_app_store_image) !!}
                                </a>
                                @endif
                                @if($free_app_play_store_image)
                                <a href="{{ $free_app_play_store_link }}">
                                    {!! render_image_markup_by_attachment_id($free_app_play_store_image) !!}
                                </a>
                                @endif
                            </div>
                            @if($free_app_store_shape)
                                <div class="single-appStore-shapes">
                                    {!! render_image_markup_by_attachment_id($free_app_store_shape) !!}
                                </div>
                            @endif
                        </div>
                        @if($free_app_store_phone)
                            <div class="single-appStore-thumb wow fadeInUp" data-wow-delay=".2s">
                                {!! render_image_markup_by_attachment_id($free_app_store_phone) !!}
                            </div>
                       @endif
                    </div>
                </div>
            </div>
            <div class="col-xl-6 col-lg-9">
                <div class="single-appStore">
                    <div class="single-appStore-flex">
                        <div class="single-appStore-contents">
                            @if($client_app_store_title)
                            <h3 class="single-appStore-contents-title">
                                <a href="javascript:void(0)">{{ $client_app_store_title }}</a>
                            @endif
                            <div class="single-appStore-btn flex-btn gap-2 mt-4">
                                @if($client_app_store_image)
                                <a href="{{ $client_app_store_link }}">
                                    {!! render_image_markup_by_attachment_id($client_app_store_image) !!}
                                </a>
                                @endif
                                @if($client_app_play_store_image)
                                <a href="{{ $client_app_play_store_link }}">
                                    {!! render_image_markup_by_attachment_id($client_app_play_store_image) !!}
                                </a>
                                @endif
                            </div>
                            @if($client_app_store_shape)
                                <div class="single-appStore-shapes">
                                    {!! render_image_markup_by_attachment_id($client_app_store_shape) !!}
                                </div>
                            @endif
                        </div>
                        @if($client_app_store_phone)
                            <div class="single-appStore-thumb wow fadeInUp" data-wow-delay=".5s">
                                {!! render_image_markup_by_attachment_id($client_app_store_phone) !!}
                            </div>
                         @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- appStore area end -->