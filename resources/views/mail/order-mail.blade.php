<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{get_static_option('site_title').' '. __('Mail')}}</title>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        *{font-family: 'Open Sans', sans-serif;}
        .mail-container {max-width: 650px;margin: 0 auto;text-align: center;background-color: #f2f2f2;padding: 40px 0;}
        .inner-wrap {background-color: #fff;margin: 40px;padding: 30px 20px;text-align: left;box-shadow: 0 0 20px 0 rgba(0,0,0,0.01);}
        .inner-wrap p {font-size: 16px;line-height: 26px;color: #656565;margin: 0;}
        .message-wrap p {font-size: 14px;line-height: 26px;}
        table {margin: 0 auto;}
        table {border-collapse: collapse;width: 100%;}
        table td, table th {border: 1px solid #ddd;padding: 8px;}
        table tr:nth-child(even){background-color: #f2f2f2;}
        table tr:hover {background-color: #ddd;}
        table th {padding-top: 12px;padding-bottom: 12px;text-align: left;}
        .logo-wrapper img{max-width: 200px;}
        .btn-profile {
            color: #666;
            font-size: 16px;
            font-weight: 500;
            display: inline-block;
            border-radius: 5px;
            text-align: center;
            cursor: pointer;
            line-height: 24px;
            padding: 7px 20px;
            white-space: nowrap;
            -webkit-transition: all 0.3s ease-in;
            transition: all 0.3s ease-in;
        }
        @media only screen and (max-width: 575.98px) {.btn-profile {padding: 6px 20px;font-size: 15px}}
        @media only screen and (max-width: 375px) {.btn-profile {padding: 5px 15px;font-size: 14px}}
        .btn-profile.btn-bg-1 {background-color: #6176F6;color: #fff}
        .btn-profile.btn-bg-1:hover {background-color: #4758C7}
        .btn-wrapper {text-align: center; margin-top:20px}
        .btn-wrapper a {text-decoration: none}

    </style>
</head>
<body>
<?php $order_details = \App\Models\Order::where('id',$order_id)->first(); ?>
<div class="mail-container">
    <div class="logo-wrapper">
        <a href="{{url('/')}}">
            {!! render_image_markup_by_attachment_id(get_static_option('site_logo')) !!}
        </a>
    </div>
    <div class="inner-wrap">
        <div class="congratulation-wrapper">
            <div class="congratulation-contents center-text">
                <h4 class="congratulation-contents-title"> {{ __('Success!') }} </h4>
                @if($type == 'admin')
                    <p>{{ __('Hello, admin a new order has been created by') }} {{ $order_details->user?->username }} <br>
                @endif
                @if($type == 'freelancer')
                    @if($order_details->is_project_job == 'project')
                     <p>{{ __('Hello')}} {{ $order_details->project?->project_creator?->username }} {{ __('you have a new order created by') }} {{ $order_details->user?->username }} <br>
                    @endif
                @endif
                @if($type == 'client')
                    <p>{{ __('Hello')}} {{ $order_details->user?->username }} {{ __('your order successfully placed') }}<br>
                @endif
                {{ __('Order has been created at:') .$order_details->created_at?->toFormattedDateString().','. ucwords(str_replace("_", " ", $order_details->payment_gateway)) }}
                </p>
                <br>
                <table class="table text-start">
                    <tbody>
                    <tr>
                        @if($order_details->is_project_job == 'project')
                        <th>{{ __('Item') }}</th>
                        <td>{{  $order_details->project?->title }}</td>
                       @endif
                    </tr>
                    <tr>
                        <th>{{ __('ID') }}</th>
                        <td>{{  $order_details->id }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('Price') }}</th>
                        <td>{{  float_amount_with_currency_symbol($order_details->price)}}</td>
                    </tr>
                    <tr>
                        <th>{{ __('Revision') }}</th>
                        <td>{{  $order_details->revision }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('Delivery') }}</th>
                        <td>{{  $order_details->delivery_time }} {{ __('days') }}</td>
                    </tr>
                    </tbody>
                </table>

                <div class="btn-wrapper">
                    <a href="{{ route('homepage') }}" class="btn-profile btn-bg-1">{{ __('Back to Home') }}</a>
                </div>
            </div>
        </div>
    </div>
    <footer>
        {!! get_footer_copyright_text() !!}
    </footer>
</div>

</body>
</html>
