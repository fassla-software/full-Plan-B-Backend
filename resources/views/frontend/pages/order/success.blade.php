@extends('frontend.layout.master')
@section('site_title',__('Order Details'))
@section('content')
    <div class="congratulation-area section-bg-2 pat-100 pab-100">
        <div class="container">
            <div class="congratulation-wrapper">
                <div class="congratulation-contents center-text">
                    <h4 class="congratulation-contents-title"> {{ __('Success!') }} </h4>
                    <p class="congratulation-contents-para">{{ __('You have successfully placed an order') }}</p>
                    <hr>
                    <table class="table text-start">
                        <tbody>
                            <tr>
                                <th>{{ __('ID') }}</th>
                                <td>#000{{  $order_details->id }}</td>
                            </tr>
                            <tr>
                                @if($order_details->is_fixed_hourly == 'hourly')
                                <tr>
                                    <th>{{ __('Hourly Rate') }}</th>
                                    <td>{{float_amount_with_currency_symbol($order_details?->job->hourly_rate)}}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('Estimated Hours') }}</th>
                                    <td>{{ $order_details?->job->estimated_hours }}</td>
                                </tr>
                                @else
                                <tr>
                                    <th>{{ __('Price') }}</th>
                                    <td>{{  float_amount_with_currency_symbol($order_details->price)}}</td>
                                </tr>
                                @endif
                            <tr>
                                <th>{{ __('Revision') }}</th>
                                <td>{{  $order_details->revision == 1000 ? __('Unlimited') : $order_details->revision }}</td>
                            </tr>
                            <tr>
                                <th>{{ __('Delivery') }}</th>
                                <td>{{  $order_details->delivery_time }}</td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="btn-wrapper mt-4">
                        <a href="{{ route('client.order.details',$order_details->id) }}" class="btn-profile btn-bg-1">{{ __('View Details') }}</a>
                        <a href="{{ route('homepage') }}" class="btn-profile btn-bg-1">{{ __('Back to Home') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

