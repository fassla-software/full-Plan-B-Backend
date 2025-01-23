@if($orders->total() < 1)
    <h4 class="text-danger">{{ __('Nothing Found') }}</h4>
@else
    @foreach($orders as $order)
        @php $rating =  \App\Models\Rating::select('id','order_id','rating')->where('order_id',$order->id)->where('sender_type',2)->first(); @endphp

        <div class="myOrder-single bg-white padding-20 radius-10">
            <div class="myOrder-single-item">
                <div class="myOrder-single-flex">
                    <div class="myOrder-single-content">
                        <span class="myOrder-single-content-id">#000{{ $order->id }}</span>
                        <h4 class="myOrder-single-content-title mt-2">
                            @if($order->is_project_job == 'project')
                                <a href="{{ route('client.order.details',$order->id) }}"> {{ $order?->project->title ?? '' }} </a>
                            @elseif($order->is_project_job == 'job')
                                <a href="{{ route('client.order.details',$order->id)  }}">{{ $order?->job->title ?? '' }}</a>
                            @else
                               {{ __('Custom order')}}
                            @endif
                        </h4>
                        <div class="myOrder-single-content-btn flex-btn mt-3">
                            <x-order.order-status :status="$order->status" />
                            <x-order.is-custom :isCustom="$order->is_project_job" />
                            <x-order.payment-verify :paymentVerifyCheck="$order" />
                        </div>
                    </div>
                    <span class="myOrder-single-content-time">{{ $order->created_at->diffForHumans() }} </span>
                </div>
            </div>
            <div class="myOrder-single-item">
                <div class="myOrder-single-block">
                    <div class="myOrder-single-block-item">
                        <div class="myOrder-single-block-item-content">
                            @if($order->is_fixed_hourly == 'hourly')
                                <span class="myOrder-single-block-subtitle">{{ __('Hourly rate') }}</span>
                                <h6 class="myOrder-single-block-title mt-2">{{ float_amount_with_currency_symbol($order?->job->hourly_rate) }}</h6>
                            @else
                                <span class="myOrder-single-block-subtitle">{{ __('Order budget') }}</span>
                                <h6 class="myOrder-single-block-title mt-2">{{ float_amount_with_currency_symbol($order->price) }}
                                    <x-order.is-funded :isFunded="$order->payment_status" :paymentGateway="$order->payment_gateway"/>
                                </h6>
                            @endif
                        </div>
                    </div>
                    @if($order->delivery_time)
                    <div class="myOrder-single-block-item">
                        <div class="myOrder-single-block-item-content">
                            <span class="myOrder-single-block-subtitle">{{ __('Delivery Time') }}</span> <br>
                            <x-order.deadline :deadline="$order->delivery_time ?? '' " />
                        </div>
                    </div>
                    @endif
                    <div class="myOrder-single-block-item">
                        <div class="myOrder-single-block-item-author">
                            <x-order.profile-image :image="$order?->freelancer->image" />
                        </div>
                        <x-order.name-rating :firstName="$order?->freelancer->first_name" :lastName="$order?->freelancer->last_name" :userId="$order?->freelancer->id" :orderRating="$rating->rating ?? '' " :userType="$order?->freelancer->user_type" :isIdentityVerified="$order?->freelancer?->user_verified_status" />
                    </div>
                </div>
            </div>
            <div class="myOrder-single-item">
                <div class="myOrder-single-flex flex-between">
                    <div class="btn-wrapper flex-btn">
                        <a href="{{ route('client.order.details',$order->id) }}" class="btn-profile btn-bg-1">{{ __('View Order') }}</a>
                        @if($order->status == 3)
                            @if($order?->user?->is_suspend !=1)
                                <a href="{{ route('client.order.rating',$order->id) }}" class="btn-profile btn-outline-gray">{{ __('Submit Review') }}</a>
                                <a href="{{ route('client.order.invoice.generate',$order->id) }}" class="btn-profile btn-outline-gray">{{ __('Invoice') }}</a>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endforeach
    <x-pagination.laravel-paginate :allData="$orders" />
@endif
