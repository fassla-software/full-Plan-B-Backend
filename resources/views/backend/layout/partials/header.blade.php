<!DOCTYPE html>
<html lang="{{get_user_lang()}}" dir="{{get_user_lang_direction()}}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title','')</title>
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
    <!-- bootstrap -->

    <!-- All Plugin Css -->
    <link rel="stylesheet" href="{{ asset('assets/common/css/all_plugin.css') }}">
    <!-- Toastr Css -->
    <link rel="stylesheet" href="{{ asset('assets/common/css/toastr.min.css') }}">
    <!-- Dashboard Stylesheet -->
    <link rel="stylesheet" href="{{ asset('assets/backend/css/admin_dashboard.css') }}">
    @if(get_user_lang_direction() == 'rtl')
        <link rel="stylesheet" href="{{ asset('assets/backend/css/admin-rtl.css') }}">
    @endif
    @include('frontend.layout.partials.root-style')
    @yield('style')

</head>
<body>
