
<!DOCTYPE html>
<html lang="{{get_user_lang()}}" dir="{{get_user_lang_direction()}}">

<head>
    {!! renderHeadStartHooks() !!}
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- favicon -->
    @php
        $site_favicon = get_attachment_image_by_id(get_static_option('site_favicon'),"full",false);
    @endphp
    @if (!empty($site_favicon))
        <link rel="icon" href="{{$site_favicon['img_url'] ?? ''}}" sizes="40x40" type="icon/png">
    @endif
    {!! load_google_fonts() !!}

    <!-- bootstrap -->
    <link rel="stylesheet" href="{{ asset('assets/common/css/bootstrap.min.css') }}">
    <!-- Animate Css -->
    <link rel="stylesheet" href="{{ asset('assets/frontend/css/animate.css') }}">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="{{ asset('assets/frontend/css/slick.css') }}">
    <!-- All Plugin Css -->
    <link rel="stylesheet" href="{{ asset('assets/common/css/all_plugin.css') }}">
    <!-- Toastr Css -->
    <link rel="stylesheet" href="{{ asset('assets/common/css/toastr.min.css') }}">
    <!-- Helper Css -->
    <link rel="stylesheet" href="{{ asset('assets/frontend/css/helpers.css')}}">
    <!-- Main Stylesheet -->
    <link rel="stylesheet" href="{{ asset('assets/frontend/css/magnific-popup.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/frontend/css/style.css') }}">
    @if(get_user_lang_direction() == 'rtl')
        <link rel="stylesheet" href="{{ asset('assets/frontend/css/xilancer-rtl.css') }}">
    @endif
    @include('frontend.layout.partials.root-style')
    <!-- page css -->
    @yield('style')

    @if(request()->routeIs('homepage'))
        <title>{{get_static_option('site_title')}} - {{get_static_option('site_tag_line')}}</title>

        {!! render_site_meta() !!}

    @elseif( request()->routeIs('frontend.dynamic.page') && $page_type === 'page' )

        {!! render_site_title(optional($page_post)->title ) !!}
        {!! render_site_meta() !!}

    @else
        <title>@yield('site_title')</title>
        @if(View::hasSection('meta_title'))
            <meta name="title" content="@yield('meta_title')">
        @endif
        @if(View::hasSection('meta_description'))
            <meta name="description" content="@yield('meta_description')">
        @endif
    @endif
@php

    $custom_css = '';
    if (file_exists('assets/frontend/css/dynamic-style.css')) {
        $custom_css = file_get_contents('assets/frontend/css/dynamic-style.css');
    }
    @endphp
    @if(!empty($custom_css))
        <link rel="stylesheet" href="{{asset('assets/frontend/css/dynamic-style.css')}}">
    @endif
    {!! renderHeadEndHooks() !!}
</head>

<body>
{!! renderBodyStartHooks() !!}