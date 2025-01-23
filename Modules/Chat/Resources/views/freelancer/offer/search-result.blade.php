@if($offers->total() < 1)
    <div class="myOrder-single bg-white padding-20 radius-10">
        <div class="myOrder-single-item">
            <h4 class="text-danger">{{ __('No Offers Found') }}</h4>
        </div>
    </div>
@else
    @foreach($offers as $offer)
        <div class="myOrder-single bg-white padding-20 radius-10">
            <div class="myOrder-single-item">
                <div class="myOrder-single-flex">
                    <div class="myOrder-single-content">
                        <span class="myOrder-single-content-id">#000{{ $offer->id }}</span>
                        <div class="myOrder-single-content-btn flex-btn mt-3">
                            @php
                                $offer_order = \App\Models\Order::where('identity',$offer->id)->where('is_project_job','offer')->where('payment_status','complete')->first();
                            @endphp
                            @if($offer_order)
                                <span class="job-progress">{{ __('Accepted') }}</span>
                            @else
                                <span class="pending-approval">{{ __('Pending') }}</span>
                                @if($offer->status == 0)
                                    <a href="#" class="pending-approval delete_offer" data-offer-id="{{ $offer->id }}"> {{__('Delete')}} </a>
                                @endif
                            @endif
                            <span class="custom-order">{{__("Custom Offer")}}</span>
                        </div>
                    </div>
                    <span class="myOrder-single-content-time">{{ $offer->created_at->diffForHumans() }} </span>
                </div>
            </div>
            <div class="myOrder-single-item">
                <div class="myOrder-single-block">
                    <div class="myOrder-single-block-item">
                        <div class="myOrder-single-block-item-content">
                            <span class="myOrder-single-block-subtitle">{{ __('Offer Price') }}</span>
                            <h6 class="myOrder-single-block-title mt-2">{{ float_amount_with_currency_symbol($offer->price) }}
                            </h6>
                        </div>
                    </div>
                    @if($offer->deadline)
                        <div class="myOrder-single-block-item">
                            <div class="myOrder-single-block-item-content">
                                <span class="myOrder-single-block-subtitle">{{ __('Delivery Time') }}</span> <br>
                                <h6 class="myOrder_single__block__title mt-2">
                                    {{ $offer->deadline }}
                                </h6>
                            </div>
                        </div>
                        @else
                        <div class="myOrder-single-block-item">
                            <div class="myOrder-single-block-item-content">
                                <span class="myOrder-single-block-subtitle">{{ __('Delivery Time') }}</span> <br>
                                <h6 class="myOrder_single__block__title mt-2">
                                    {{__('By Milestone')}}
                                </h6>
                            </div>
                        </div>
                    @endif
                    <div class="myOrder-single-block-item">
                        <div class="myOrder-single-block-item-content">
                            <span class="myOrder-single-block-subtitle">{{ __('Create Date') }}</span><br>
                            <h6 class="myOrder_single__block__title mt-2">
                                {{ $offer->created_at->toFormattedDateString() ?? '' }}
                            </h6>
                        </div>
                    </div>
                    <div class="myOrder-single-block-item">
                        <div class="myOrder-single-block-item-author">
                            <x-order.profile-image :image="$offer?->client->image" />
                        </div>
                        <x-order.name-rating :firstName="$offer?->client->first_name" :lastName="$offer?->client->last_name" :userId="$offer?->client->id" :orderRating="''" :userType="$offer?->client->user_type ?? ''" :isIdentityVerified="$offer?->client?->user_verified_status" />
                    </div>
                </div>
                <p class="mt-4">{!! Str::limit($offer->description,250 ?? '') !!}</p>
            </div>
            <div class="myOrder-single-item">
                <div class="myOrder-single-flex flex-between">
                    <div class="btn-wrapper flex-btn">
                        <a href="{{ route('freelancer.offer.details',$offer->id) }}" class="btn-profile btn-bg-1">{{ __('View Offer') }}</a>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
    <x-pagination.laravel-paginate :allData="$offers" />
@endif
