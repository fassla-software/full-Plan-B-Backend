@if($orders->total() < 1)
    <div class="myOrder-single bg-white padding-20 radius-10">
        <div class="myOrder-single-item">
            <h4 class="text-danger">{{ __('No Order') }}</h4>
        </div>
    </div>
@else
    @foreach($orders as $order)
        @php $rating =  \App\Models\Rating::select('id','order_id','rating')->where('order_id',$order->id)->where('sender_type',1)->first(); @endphp

        <div class="myOrder-single bg-white padding-20 radius-10">
            <div class="myOrder-single-item">
                <div class="myOrder-single-flex">
                    <div class="myOrder-single-content">
                        <span class="myOrder-single-content-id">#000{{ $order->id }}</span>
                        <h4 class="myOrder-single-content-title mt-2">
                            @if($order->is_project_job == 'project')
                                <a href="{{ route('freelancer.order.details',$order->id) }}"> {{ $order?->project->title ?? '' }} </a>
                            @elseif($order->is_project_job == 'job')
                                <a href="{{ route('freelancer.order.details',$order->id) }}">{{ $order?->job->title ?? '' }}</a>
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
                                    <x-order.is-funded :isFunded="$order->payment_status" :paymentGateway="$order->payment_gateway" />
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
                            <x-order.profile-image :image="$order?->user->image" :loadFrom="$order?->user->load_from" />
                        </div>
                        <x-order.name-rating :firstName="$order?->user->first_name" :lastName="$order?->user->last_name" :userId="$order?->user->id" :orderRating="$rating->rating ?? '' " :userType="$order?->user?->user_type" :isIdentityVerified="$order?->user?->user_verified_status" />
                    </div>
                </div>
            </div>
            <div class="myOrder-single-item">
                <div class="myOrder-single-flex flex-between">
                    <div class="btn-wrapper">
                        @if($order->status == 0)
                            <x-status.table.status-change :title="__('Decline Order')" :class="'btn-profile btn-outline-cancel decline_and_change_order_status'" :value="__('decline')" :url="route('freelancer.order.decline',$order->id)"/>
                        @endif
                    </div>

                    <div class="btn-wrapper flex-btn">
                        @if($order->status == 0)
                            <x-status.table.status-change :title="__('Accept Order')" :class="'btn-profile btn-outline-gray accept_and_change_order_status'" :url="route('freelancer.order.accept',$order->id)"/>
                        @else
                            @if($order->status != 5 && $order->status != 4 && $order->status != 3 && $order->status != 7)
                                <x-status.table.status-change :title="__('Cancel Order')" :class="'btn-profile btn-outline-cancel cancel_and_change_order_status'" :value="__('cancel')" :url="route('freelancer.order.decline',$order->id)"/>
                            @endif
                        @endif
                        @if($order->status == 3)
                            @if($order?->freelancer?->is_suspend !=1)
                                <a href="{{ route('freelancer.order.invoice.generate',$order->id) }}" class="btn-profile btn-outline-gray">{{ __('Invoice') }}</a>
                                <a href="{{ route('freelancer.order.rating',$order->id) }}" class="btn-profile btn-outline-gray">{{ __('Submit Review') }}</a>
                            @endif
                        @endif
                            <a href="{{ route('freelancer.order.details',$order->id) }}" class="btn-profile btn-bg-1">{{ __('View Order') }}</a>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
    <x-pagination.laravel-paginate :allData="$orders" />
@endif
