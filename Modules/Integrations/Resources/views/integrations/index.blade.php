@extends('backend.layout.master')
@section('title')
    {{ __('All Integrations') }}
@endsection

@section('style')
    <style>
        .plugin-grid {
            display: flex;
            flex-wrap: wrap;
            /*justify-content: space-between;*/
            /*padding: 1em;*/
            gap: 1em; /* space between grid items */
        }

        .plugin-card {
            width: calc((100% - 2em) / 3); /* for a three column layout */
            box-shadow: 0px 1px 3px 0px rgba(0, 0, 0, 0.2);
            /*padding: 1em;*/
            text-align: center;
        }

        .plugin-card .thumb-bg-color {
            background-color: #5d666e;
            padding: 40px;
            color: #fff;
        }

        .plugin-card .thumb-bg-color strong {
            font-size: 20px;
            line-height: 26px;
        }

        .plugin-card .thumb-bg-color strong .version {
            font-size: 14px;
            line-height: 18px;
            background-color: #fff;
            padding: 5px 10px;
            display: inline-block;
            color: #333;
            border-radius: 3px;
            margin-top: 15px;
        }

        .plugin-title {
            font-size: 16px;
            font-weight: 500;
            background-color: #03A9F4;
            box-shadow: 0 0 30px 0 rgba(0, 0, 0, 0.2);
            display: inline-block;
            padding: 12px 30px;
            border-radius: 25px;
            color: #fff;
            position: relative;
            margin-top: -20px;
        }

        .plugin-title.externalplugin {
            background-color: #3F51B5;
        }

        .plugin-meta {
            font-size: 0.9em;
            color: #666;
            padding: 20px;
        }

        .padding-30 {
            padding: 30px;
        }

        .plugin-card .thumb-bg-color.externalplugin {
            background-color: #FF9800;
        }

        .thumb-bg-color.whatsapp svg,
        .thumb-bg-color.messenger svg{
            max-width: 60px;
        }

        .plugin-card .thumb-bg-color.google_analytics, .plugin-card .thumb-bg-color.captcha {
            background-color: #F9AB00;
        }
        .plugin-card .thumb-bg-color.google_tags {
            background-color: #4285f4;
        }
        .plugin-card .thumb-bg-color.facebook_pixels {
            background-color: #397bee;
        }
        .plugin-card .thumb-bg-color.addroll {
            background-color: #06aeef;
        }
        .plugin-card .thumb-bg-color.whatsapp {
            background-color: #2cb317;
        }
        .plugin-card .thumb-bg-color.twakto {
            background-color: #00b447;
        }

        .plugin-card .thumb-bg-color.crisp {
            background-color: #1a72f5;
        }

        .plugin-card .thumb-bg-color.tidio {
            background-color: #0567ff;
        }

        .plugin-card .thumb-bg-color.messenger {
            background-color: #A334FA;
        }

        .plugin-card .thumb-bg-color.instagram {
            background: linear-gradient(45deg, #f09433 0%,#e6683c 25%,#dc2743 50%,#cc2366 75%,#bc1888 100%);
        }

        .plugin-card .plugin-meta {
            min-height: 50px;
        }

        .plugin-card .btn-group-wrap {
            margin-bottom: 30px;
            display: flex;
            justify-content: center;
            gap: 20px;
        }

        .plugin-card .btn-group-wrap a {
            display: inline-block;
            padding: 8px 25px;
            background-color: #4b4e5b;
            border-radius: 25px;
            color: #fff;
            text-decoration: none;
            font-size: 12px;
            transition: all 300ms;
        }

        .plugin-card .btn-group-wrap a.pl_delete {
            background-color: #e13a3a;
        }

        .plugin-card .btn-group-wrap a:hover {
            opacity: .8;
        }

        .plugin-card .icon-wrap {
            margin-bottom: 15px;
        }

        /* For large screens and above */
        @media (min-width: 900px) {
            .plugin-card {
                width: calc((100% - 3em) / 3); /* three columns for large screens */
            }
        }

        /* For medium screens and above */
        @media (max-width: 600px) {
            .plugin-card {
                width: calc((100% - 2em) / 2); /* two columns for medium screens */
            }

            .plugin-card .btn-group-wrap {
                gap: 5px;
            }

            .plugin-card .btn-group-wrap a {
                padding: 7px 15px;
            }

            .plugin-title {
                font-size: 12px;
                line-height: 16px;
            }
        }

        @media (max-width: 500px) {
            .plugin-card {
                width: calc((100% - 2em) / 1); /* two columns for medium screens */
            }

            .plugin-title {
                font-size: 16px;
                line-height: 20px;
            }
        }

    </style>
@endsection

@section('content')
    @php
        $method = "get_static_option";
    @endphp

    <div class="dashboard__body">
        <div class="row">
            <div class="col-lg-12">
                <div class="recent-order-wrapper dashboard-table bg-white padding-30">
                    <div class="header-wrap">
                        <h4 class="header-title mb-2">{{__("All Integrations")}}</h4>
                        <p class="mb-3">{{__("Manage all integrations from here, you can active/deactivate integrations.")}}</p>
                    </div>
                    <div class="plugin-grid">
                        <div class="plugin-card">
                            <div class="thumb-bg-color google_analytics">
                                <strong class="google_analytics">{{__("Google Analytics GT4")}}</strong>
                            </div>
                            <p class="plugin-meta">
                                {{__("you can configure google analytics (GT4) into the website.")}}
                            </p>
                            <div class="btn-group-wrap">
                                <a href="#" data-option="google_analytics_gt4_status"
                                   data-status="{{$method("google_analytics_gt4_status")}}"
                                   class="pl-btn pl_active_deactive">{{$method("google_analytics_gt4_status") == 'on' ? __("Deactivate") : __("Active") }}</a>
                                <a href="#" data-bs-target="#google_analytics_modal" data-bs-toggle="modal"
                                   class="pl-btn pl_delete">{{__("Settings") }}</a>
                            </div>
                        </div>
                        <div class="plugin-card">
                            <div class="thumb-bg-color google_tags">

                                <strong class="google_tags">{{__("Google Tags Manager")}}</strong>
                            </div>
                            <p class="plugin-meta">
                                {{__("you can configure google tag manager into the website.")}}
                            </p>
                            <div class="btn-group-wrap">
                                <a href="#" data-option="google_tag_manager_status"
                                   data-status="{{$method("google_tag_manager_status")}}"
                                   class="pl-btn pl_active_deactive">{{$method("google_tag_manager_status") == 'on' ? __("Deactivate") : __("Active") }}</a>
                                <a href="#" data-bs-target="#google_tag_manager_modal" data-bs-toggle="modal"
                                   class="pl-btn pl_delete">{{__("Settings") }}</a>
                            </div>
                        </div>
                        <div class="plugin-card">
                            <div class="thumb-bg-color facebook_pixels">

                                <strong class="facebook_pixels">{{__("Facebook Pixels")}}</strong>
                            </div>
                            <p class="plugin-meta">
                                {{__("you can configure facebook pixels into the website.")}}
                            </p>
                            <div class="btn-group-wrap">
                                <a href="#" data-option="facebook_pixels_status"
                                   data-status="{{$method("facebook_pixels_status")}}"
                                   class="pl-btn pl_active_deactive">{{$method("facebook_pixels_status") == 'on' ? __("Deactivate") : __("Active") }}</a>
                                <a href="#" data-bs-target="#facebook_pixels_modal" data-bs-toggle="modal"
                                   class="pl-btn pl_delete">{{__("Settings") }}</a>
                            </div>
                        </div>
                        <div class="plugin-card">
                            <div class="thumb-bg-color addroll">

                                <strong class="addroll">{{__("Adroll")}}</strong>
                            </div>
                            <p class="plugin-meta">
                                {{__("you can configure AdRoll into the website.")}}
                            </p>
                            <div class="btn-group-wrap">
                                <a href="#" data-option="adroll_pixels_status"
                                   data-status="{{$method("adroll_pixels_status")}}"
                                   class="pl-btn pl_active_deactive">{{$method("adroll_pixels_status") == 'on' ? __("Deactivate") : __("Active") }}</a>
                                <a href="#" data-bs-target="#adroll_pixels_modal" data-bs-toggle="modal"
                                   class="pl-btn pl_delete">{{__("Settings") }}</a>
                            </div>
                        </div>
                        <div class="plugin-card">
                            <div class="thumb-bg-color whatsapp">

                                <strong class="whatsapp">{{__("What's App")}}</strong>
                            </div>
                            <p class="plugin-meta">
                                {{__("you can configure What's App into the website.")}}
                            </p>
                            <div class="btn-group-wrap">
                                <a href="#" data-option="whatsapp_status" data-status="{{$method("whatsapp_status")}}"
                                   class="pl-btn pl_active_deactive">{{$method("whatsapp_status") == 'on' ? __("Deactivate") : __("Active") }}</a>
                                <a href="#" data-bs-target="#whatsapp_modal" data-bs-toggle="modal"
                                   class="pl-btn pl_delete">{{__("Settings") }}</a>
                            </div>
                        </div>
                        <div class="plugin-card">
                            <div class="thumb-bg-color messenger">

                                <strong class="messenger">{{__("Messenger")}}</strong>
                            </div>
                            <p class="plugin-meta">
                                {{__("you can configure messenger into the website.")}}
                            </p>
                            <div class="btn-group-wrap">
                                <a href="#" data-option="messenger_status" data-status="{{$method("messenger_status")}}"
                                   class="pl-btn pl_active_deactive">{{$method("messenger_status") == 'on' ? __("Deactivate") : __("Active") }}</a>
                                <a href="#" data-bs-target="#messenger_modal" data-bs-toggle="modal"
                                   class="pl-btn pl_delete">{{__("Settings") }}</a>
                                <small> <a href="https://www.facebook.com/business/help/1524587524402327"
                                           target="_blank">
                                        <i class="fa-solid fa-circle-question"></i>
                                    </a></small>
                            </div>

                        </div>
                        <div class="plugin-card">
                            <div class="thumb-bg-color twakto">
                                <strong class="twakto">{{__("Twak.to Api")}}</strong>
                            </div>
                            <p class="plugin-meta">
                                {{__("you can configure Twak.to into the website.")}}
                            </p>
                            <div class="btn-group-wrap">
                                <a href="#" data-option="twakto_status" data-status="{{$method("twakto_status")}}"
                                   class="pl-btn pl_active_deactive">{{$method("twakto_status") == 'on' ? __("Deactivate") : __("Active") }}</a>
                                <a href="#" data-bs-target="#twakto_modal" data-bs-toggle="modal"
                                   class="pl-btn pl_delete">{{__("Settings") }}</a>
                            </div>
                        </div>

                        <div class="plugin-card">
                            <div class="thumb-bg-color crisp">
                                <strong class="crisp">{{__("Crsip")}}</strong>
                            </div>
                            <p class="plugin-meta">
                                {{__("you can configure Crsip into the website.")}}
                            </p>
                            <div class="btn-group-wrap">
                                <a href="#" data-option="crsip_status" data-status="{{$method("crsip_status")}}"
                                   class="pl-btn pl_active_deactive">{{$method("crsip_status") == 'on' ? __("Deactivate") : __("Active") }}</a>
                                <a href="#" data-bs-target="#crsip_modal" data-bs-toggle="modal"
                                   class="pl-btn pl_delete">{{__("Settings") }}</a>
                            </div>
                        </div>
                        <div class="plugin-card">
                            <div class="thumb-bg-color tidio">
                                <strong class="tidio">{{__("Tidio")}}</strong>
                            </div>
                            <p class="plugin-meta">
                                {{__("you can configure Tidio into the website.")}}
                            </p>
                            <div class="btn-group-wrap">
                                <a href="#" data-option="tidio_status" data-status="{{$method("tidio_status")}}"
                                   class="pl-btn pl_active_deactive">{{$method("tidio_status") == 'on' ? __("Deactivate") : __("Active") }}</a>
                                <a href="#" data-bs-target="#tidio_modal" data-bs-toggle="modal"
                                   class="pl-btn pl_delete">{{__("Settings") }}</a>
                            </div>
                        </div>

                        <div class="plugin-card">
                            <div class="thumb-bg-color captcha">
                                <strong class="captcha">{{__("Google Captcha V3")}}</strong>
                            </div>
                            <p class="plugin-meta">
                                {{__("you can configure Google Captcha into the website.")}}
                            </p>
                            <div class="btn-group-wrap">
                                <a href="#" data-option="captcha_status" data-status="{{$method("captcha_status")}}"
                                   class="pl-btn pl_active_deactive">{{$method("captcha_status") == 'on' ? __("Deactivate") : __("Active") }}</a>
                                <a href="#" data-bs-target="#google_captcha_modal" data-bs-toggle="modal"
                                   class="pl-btn pl_delete">{{__("Settings") }}</a>
                            </div>
                        </div>

                        <div class="plugin-card">
                            <div class="thumb-bg-color instagram">
                                <strong class="instagram">{{__("Instagram")}}</strong>
                            </div>
                            <p class="plugin-meta">
                                {{__("you can configure Instagram into the website. It will work if any instagram feature is available")}}
                            </p>
                            <div class="btn-group-wrap">
                                <a href="#" data-option="instagram_status" data-status="{{$method("instagram_status")}}"
                                   class="pl-btn pl_active_deactive">{{$method("instagram_status") == 'on' ? __("Deactivate") : __("Active") }}</a>
                                <a href="#" data-bs-target="#instagram_modal" data-bs-toggle="modal"
                                   class="pl-btn pl_delete">{{__("Settings") }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" id="messenger_modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{__("Messenger")}}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.integration') }}" method="post">
                    @csrf
                    <input type="hidden" name="data_type" value="messenger">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="#">{{__("Messenger Page ID")}}</label>
                            <input type="text"
                                   name="messenger_page_id"
                                   class="form-control"
                                   value="{{get_static_option("messenger_page_id")}}"
                            >
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{__('Close')}}</button>
                        <button type="submit" class="btn btn-primary">{{__('Save changes')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" tabindex="-1" id="tidio_modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{__("Tidio")}}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.integration') }}" method="post">
                    @csrf
                    <input type="hidden" name="data_type" value="tidio">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="#">{{__("Chat Page ID")}}</label>
                            <input type="text"
                                   name="tidio_chat_page_id"
                                   class="form-control"
                                   value="{{get_static_option("tidio_chat_page_id")}}"
                            >
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{__('Close')}}</button>
                        <button type="submit" class="btn btn-primary">{{__('Save changes')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" tabindex="-1" id="crsip_modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{__("Crsip")}}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.integration') }}" method="post">
                    @csrf
                    <input type="hidden" name="data_type" value="crsip">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="#">{{__("Website ID")}}</label>
                            <input type="text"
                                   name="crsip_website_id"
                                   class="form-control"
                                   value="{{get_static_option("crsip_website_id")}}"
                            >
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{__('Close')}}</button>
                        <button type="submit" class="btn btn-primary">{{__('Save changes')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" tabindex="-1" id="twakto_modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{__("Twak.to")}}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.integration') }}" method="post">
                    @csrf
                    <input type="hidden" name="data_type" value="twakto">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="#">{{__("Widget ID")}}</label>
                            <input type="text"
                                   name="twakto_widget_id"
                                   class="form-control"
                                   value="{{get_static_option("twakto_widget_id")}}"
                            >
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{__('Close')}}</button>
                        <button type="submit" class="btn btn-primary">{{__('Save changes')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" tabindex="-1" id="whatsapp_modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{__("What's App")}}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.integration') }}" method="post">
                    @csrf
                    <input type="hidden" name="data_type" value="whatsapp">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="#">{{__("What's App Mobile Number With Country Code")}}</label>
                            <input type="text"
                                   name="whatsapp_mobile_number"
                                   class="form-control"
                                   value="{{get_static_option("whatsapp_mobile_number")}}"
                            >
                        </div>
                        <div class="form-group">
                            <label for="#">{{__("Initial Message")}}</label>
                            <input type="text"
                                   name="whatsapp_initial_text"
                                   class="form-control"
                                   value="{{get_static_option("whatsapp_initial_text")}}"
                            >
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{__('Close')}}</button>
                        <button type="submit" class="btn btn-primary">{{__('Save changes')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" tabindex="-1" id="adroll_pixels_modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{__("AdRoll Pixels")}}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.integration') }}" method="post">
                    @csrf
                    <input type="hidden" name="data_type" value="adroll_pixels">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="#">{{__("Adroll Adviser ID")}}</label>
                            <input type="text"
                                   name="adroll_adviser_id"
                                   class="form-control"
                                   value="{{$method("adroll_adviser_id")}}"
                            >
                        </div>
                        <div class="form-group">
                            <label for="#">{{__("Adroll Publisher ID")}}</label>
                            <input type="text"
                                   name="adroll_publisher_id"
                                   class="form-control"
                                   value="{{$method("adroll_publisher_id")}}">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{__('Close')}}</button>
                        <button type="submit" class="btn btn-primary">{{__('Save changes')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" tabindex="-1" id="facebook_pixels_modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{__("Facebook Pixels")}}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.integration') }}" method="post">
                    @csrf
                    <input type="hidden" name="data_type" value="facebook_pixels">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="#">{{__("Facebook Pixels ID")}}</label>
                            <input type="text"
                                   name="facebook_pixels_id"
                                   class="form-control"
                                   value="{{$method("facebook_pixels_id")}}"
                            >
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{__('Close')}}</button>
                        <button type="submit" class="btn btn-primary">{{__('Save changes')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" id="google_analytics_modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{__("Google Analytics GT4")}}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.integration') }}" method="post">
                    @csrf
                    <input type="hidden" name="data_type" value="google_analytics">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="#">{{__("Google Analytics GT4 ID")}}</label>
                            <input type="text"
                                   name="google_analytics_gt4_ID"
                                   class="form-control"
                                   value="{{$method("google_analytics_gt4_ID")}}"
                            >
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{__('Close')}}</button>
                        <button type="submit" class="btn btn-primary">{{__('Save changes')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" id="google_tag_manager_modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{__("Google Tag Manager")}}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.integration') }}" method="post">
                    @csrf
                    <input type="hidden" name="data_type" value="google_tag_manager">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="#">{{__("Google Tag Manager ID")}}</label>
                            <input type="text"
                                   name="google_tag_manager_ID"
                                   class="form-control"
                                   value="{{$method("google_tag_manager_ID")}}"
                            >
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{__('Close')}}</button>
                        <button type="submit" class="btn btn-primary">{{__('Save changes')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" id="google_captcha_modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{__("Google Captcha V3")}}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.integration') }}" method="post">
                    @csrf
                    <input type="hidden" name="data_type" value="google_captcha_v3">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="#">{{__("Google Captcha V3 Site Key")}}</label>
                            <input type="text"
                                   name="site_google_captcha_v3_site_key"
                                   class="form-control"
                                   value="{{$method("site_google_captcha_v3_site_key")}}"
                            >
                        </div>

                        <div class="form-group">
                            <label for="#">{{__("Google Captcha V3 Secret Key")}}</label>
                            <input type="text"
                                   name="site_google_captcha_v3_secret_key"
                                   class="form-control"
                                   value="{{$method("site_google_captcha_v3_secret_key")}}"
                            >
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{__('Close')}}</button>
                        <button type="submit" class="btn btn-primary">{{__('Save changes')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" tabindex="-1" id="instagram_modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{__("Instagram")}}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.integration') }}" method="post">
                    @csrf
                    <input type="hidden" name="data_type" value="instagram">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="#">{{__("Instagram Access Token")}}</label>
                            <input type="text"
                                   name="instagram_access_token"
                                   class="form-control"
                                   value="{{get_static_option("instagram_access_token")}}"
                            >
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{__('Close')}}</button>
                        <button type="submit" class="btn btn-primary">{{__('Save changes')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
@section('script')
    <x-sweet-alert.sweet-alert2-js />
    <script>
        (function ($) {
            "use strict";

            $(document).on("click", '.pl_active_deactive', function (e) {
                e.preventDefault();
                var el = $(this);
                Swal.fire({
                    title: '{{__("Are you sure?")}}',
                    text: '{{__("you will able revert your decision anytime")}}',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: "{{__('Yes!')}}",
                    cancelButtonText: "{{__('Cancel')}}",

                }).then((result) => {
                    if (result.isConfirmed) {
                        //todo: ajax call
                        let optionName = el.data('option');
                        let status = el.data('status');

                        $.ajax({
                            method:"POST",
                            url:"{{ route("admin.integration.activation") }}",
                            data:{ option_name: optionName, status: status },
                            success:function(res){
                                    if (res.type === 'success') {
                                        if(res.status == 'on'){
                                            toastr_success_js("{{ __('Successfully Deactivated') }}")
                                        }else{
                                            toastr_success_js("{{ __('Successfully Activated') }}")
                                        }
                                        location.reload();
                                    }
                            }
                        })
                    }
                });
            })

        })(jQuery);

        //toastr warning
        function toastr_success_js(msg){
            Command: toastr["success"](msg, "Success !")
            toastr.options = {
                "closeButton": true,
                "debug": false,
                "newestOnTop": false,
                "progressBar": true,
                "positionClass": "toast-top-right",
                "preventDuplicates": false,
                "onclick": null,
                "showDuration": "300",
                "hideDuration": "1000",
                "timeOut": "5000",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            }
        }
    </script>
@endsection
