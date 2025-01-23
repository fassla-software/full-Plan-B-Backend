<!DOCTYPE html>
<html lang="{{get_user_lang()}}" dir="{{get_user_lang_direction()}}">
<head>
    <title>{{ __('Order Invoice') }}</title>
    @php
        $site_favicon = get_attachment_image_by_id(get_static_option('site_favicon'),"full",false);
    @endphp
    @if ($site_favicon)
        <link rel="icon" href="{{$site_favicon['img_url'] ?? ''}}" sizes="40x40" type="icon/png">
    @endif
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

    <style type="text/css" media="screen">
        html {
            font-family: sans-serif;
            line-height: 1.15;
            margin: 0;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
            font-weight: 400;
            line-height: 1.5;
            color: #212529;
            text-align: left;
            background-color: #fff;
            font-size: 10px;
            margin: 36pt;
        }

        h4 {
            margin-top: 0;
            margin-bottom: 0.5rem;
        }

        p {
            margin-top: 0;
            margin-bottom: 1rem;
        }

        strong {
            font-weight: bolder;
        }

        img {
            vertical-align: middle;
            border-style: none;
        }

        table {
            border-collapse: collapse;
        }

        th {
            text-align: inherit;
        }

        h4, .h4 {
            margin-bottom: 0.5rem;
            font-weight: 500;
            line-height: 1.2;
        }

        h4, .h4 {
            font-size: 1.5rem;
        }

        .table {
            width: 100%;
            margin-bottom: 1rem;
            color: #212529;
        }

        .table th,
        .table td {
            padding: 0.75rem;
            vertical-align: top;
        }

        .table.table-items td {
            border-top: 1px solid #dee2e6;
        }

        .table thead th {
            vertical-align: bottom;
            border-bottom: 2px solid #dee2e6;
        }

        .mt-5 {
            margin-top: 3rem !important;
        }

        .pr-0,
        .px-0 {
            padding-right: 0 !important;
        }

        .pl-0,
        .px-0 {
            padding-left: 0 !important;
        }

        .text-right {
            text-align: right !important;
        }

        .text-center {
            text-align: center !important;
        }

        .text-uppercase {
            text-transform: uppercase !important;
        }
        * {
            font-family: "DejaVu Sans";
        }
        body, h1, h2, h3, h4, h5, h6, table, th, tr, td, p, div {
            line-height: 1.1;
        }
        .party-header {
            font-size: 1.5rem;
            font-weight: 400;
        }
        .total-amount {
            font-size: 12px;
            font-weight: 700;
        }
        .border-0 {
            border: none !important;
        }
        .cool-gray {
            color: #6B7280;
        }
        .invoice-logo img{
            width: 200px;
            height: 40px;
        }

    </style>
</head>

<body>
{{-- Header --}}
@if(get_static_option('site_logo'))
    <div class="invoice-logo">
        {!! render_image_markup_by_attachment_id(get_static_option('site_logo')) !!}
    </div>
@endif

<table class="table mt-5">
    <tbody>
    <tr>
        <td class="border-0 pl-0" width="70%">
            <h4 class="text-uppercase">
                <strong>{{ __('Order Invoice') }}</strong>
            </h4>
        </td>
        <td class="border-0 pl-0">
            <p>{{ __('Order ID') }} <strong>#000{{ $order->id }}</strong></p>
            <p>{{ __('Invoice Date') }}: <strong>{{ \Carbon\Carbon::now()->toDateString() }}</strong></p>
        </td>
    </tr>
    </tbody>
</table>

{{-- Seller - Buyer --}}
<table class="table">
    <thead>
    <tr>
        <th class="border-0 pl-0 party-header" width="48.5%">
            {{ __('Freelancer') }}
        </th>
        <th class="border-0" width="3%"></th>
        <th class="border-0 pl-0 party-header">
            {{ __('Client') }}
        </th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td class="px-0">
            @if($order?->freelancer->fullname)
                <p class="seller-name">
                    <strong>{{ $order?->freelancer->fullname }}</strong>
                </p>
            @endif

            @if($order?->freelancer->email)
                <p class="seller-address">
                    {{ __('Email') }}: {{ $order?->freelancer->email }}
                </p>
            @endif

            @if($order?->freelancer->phone)
                <p class="seller-phone">
                    {{ __('Phone') }}: {{ $order->freelancer->phone }}
                </p>
            @endif
        </td>
        <td class="border-0"></td>
        <td class="px-0">
            @if($order?->user->fullname)
                <p class="buyer-name">
                    <strong>{{ $order?->user->fullname }}</strong>
                </p>
            @endif

            @if($order?->user->email)
                <p class="seller-address">
                    {{ __('Email') }}: {{ $order?->freelancer->email }}
                </p>
            @endif

            @if($order?->user->phone)
                <p class="buyer-phone">
                    {{ __('Phone') }}: {{ $order->user->phone }}
                </p>
            @endif
        </td>
    </tr>
    </tbody>
</table>

{{-- Table --}}
<table class="table table-items">
    <thead>
    <tr>
        <th scope="col" class="border-0 pl-0">{{ __('Description') }}</th>
        <th scope="col" class="border-0 pl-0">{{ __('Quantity') }}</th>
        <th scope="col" class="border-0 pl-0">{{ __('Price') }}</th>
        <th scope="col" class="text-right border-0 pr-0">{{ __('Sub total') }}</th>
    </tr>
    </thead>
    <tbody>
    {{-- Items --}}
        <tr>
            <td class="pl-0">
                    <p class="cool-gray">
                        {{ __('Order Date:') }} {{ $order->created_at->toFormattedDateString() }} <br>
                        {{ __('Payment Gateway:') }}
                        @if($order->payment_gateway == 'manual_payment')
                            {{ ucfirst(str_replace('_',' ',$order->payment_gateway)) }}
                        @else
                            {{ $order->payment_gateway == 'authorize_dot_net' ? __('Authorize.Net') : ucfirst($order->payment_gateway) }}
                        @endif
                    </p>
            </td>
            <td class="pl-0">1</td>
            <td class="pl-0">{{ float_amount_with_currency_symbol($order->price - ($order->transaction_amount + $order->commission_amount)) }}</td>
            <td class="text-right">{{ float_amount_with_currency_symbol($order->price - ($order->transaction_amount + $order->commission_amount)) }}</td>
        </tr>
    {{-- Summary --}}
        <tr>
            <td colspan="2" class="border-0"></td>
            <td class="text-left pl-0">{{ __('Transaction fee') }}</td>
            <td class="text-right pr-0">{{ float_amount_with_currency_symbol($order->transaction_amount) }}</td>
        </tr>
    <tr>
        <td colspan="2" class="border-0"></td>
        <td class="text-left pl-0">{{ __('Commission amount') }}</td>
        <td class="text-right pr-0">{{ float_amount_with_currency_symbol($order->commission_amount) }}</td>
    </tr>
    <tr>
        <td colspan="2" class="border-0"></td>
        <td class="text-left pl-0">{{ __('Total amount') }}</td>
        <td class="text-right pr-0 total-amount">{{ float_amount_with_currency_symbol($order->price) }}</td>
    </tr>
    </tbody>
</table>

<br>
    <p>
        {{ __('Amount in words') }}: {{ \Terbilang::make($order->price) }}
    </p>
@if($order->description)
    <p>
        {{ __('Notes') }}: {{ __(Str::limit($order->description,300)) ?? '' }}
    </p>
@endif
</body>
</html>
