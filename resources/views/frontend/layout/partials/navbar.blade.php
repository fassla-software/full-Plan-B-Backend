@php
    $navbar_variant = !is_null(get_navbar_style()) ? get_navbar_style() : '02';
@endphp
@include('frontend.layout.partials.navbar-variant.navbar-'. $navbar_variant)

