<div class="myJob-wrapper-single-contents">
    <h4 class="myJob-wrapper-single-title">
        <a href="javascript:void(0)"># {{ $id }} - {{ $title }}</a>
    </h4>
    <div class="myJob-wrapper-single-priceCompleted mt-3">
        <div class="myJob-wrapper-single-price d-flex align-content-center gap-2">
            <span class="single-project-content-price ">{{ float_amount_with_currency_symbol($price) }}</span>
            @if($status === 1)
                <span class="myJob-wrapper-single-fixed active">{{ __('Active') }}</span>
            @else
                @if($status === 0)
                    <span class="myJob-wrapper-single-fixed warning">{{ __('Pending') }}</span>
                    @if(Auth::guard('web')->check() && Auth::guard('web')->user()->user_type == 1)
                    <x-status.table.status-change
                       :title="__('Active this milestone')"
                       :class="'btn myJob-wrapper-single-fixed danger active_this_milestone'"
                       :url="route('client.order.milestone.active',$id)"/>
                    @endif
                @endif
                @if($status === 2)
                    <span class="myJob-wrapper-single-fixed success disabled">{{ __('Complete') }}</span>
                @endif
                @if($status === 3)
                    <span class="myJob-wrapper-single-fixed danger">{{ __('Cancel') }}</span>
                @endif
                @if($status === 4)
                    <span class="myJob-wrapper-single-fixed active">{{ __('Deliver') }}</span>
                @endif
            @endif
        </div>
    </div>
    <span class="myJob-wrapper-single-date mt-3"><strong>{{ __('Delivery Time:') }}</strong> {{ $deadline ?? '' }}</span>
    <p class="myJob-wrapper-single-para mt-3">{{ $description }}</p>

    @if($status === 4)
        @if(Auth::guard('web')->check() && Auth::guard('web')->user()->user_type == 1 && Session::get('user_role') != 'freelancer')
            @php
                $urlType = empty($id) ? 'order' : 'milestone';
            @endphp
            <x-status.table.status-change
                    :title="__('Accept Milestone')"
                    :class="'btn-profile btn-bg-1 accept_and_pay mt-3'"
                    :url="route('client.order.milestone.approve',[$id ?? $orderID, $urlType])"/>
        @endif
    @endif
    @if($status === 1)
        @if(Auth::guard('web')->check() && Auth::guard('web')->user()->user_type == 2)
            <a href="javascript:void(0)"
               class="btn-profile btn-bg-1 order_submit mt-3"
               data-bs-toggle="modal"
               data-bs-target="#orderSubmitModal"
               data-order_id="{{ $orderID }}"
               data-order_milestone_id="{{ $id }}"
               data-client_id="{{ $clientID }}"
            >
                {{ __('Submit') }}
            </a>
        @endif
        @if(Auth::guard('web')->check() && Auth::guard('web')->user()->user_type == 1 && Session::get('user_role') == 'freelancer')
            <a href="javascript:void(0)"
               class="btn-profile btn-bg-1 order_submit mt-3"
               data-bs-toggle="modal"
               data-bs-target="#orderSubmitModal"
               data-order_id="{{ $orderID }}"
               data-order_milestone_id="{{ $id }}"
               data-client_id="{{ $clientID }}"
            >
                {{ __('Submit') }}
            </a>
        @endif
    @endif
</div>
