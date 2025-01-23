@extends('backend.layout.master')
@section('title', __('Order Details'))

@section('style')
    <style>
        .myOrder-single-content-para , .show_order_submit_description{white-space: pre-line}
        .pay-now-single-contents-work-flex {
            display: -webkit-box;
            display: -ms-flexbox;
             display: flex;
             -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
            -webkit-box-pack: justify;
            -ms-flex-pack: justify;
            justify-content: space-between;
            -ms-flex-wrap: wrap;
            flex-wrap: wrap;
            gap: 20px;
        }
        .fa-star, .single-project-content-review-rating{
            color: var(--secondary-color);
        }
        .rating_profile_details {
            display: flex;
            align-items: center;
            gap: 5px;
        }
        [data-star] {
            text-align:left;
            font-style:normal;
            display:inline-block;
            position: relative;
            unicode-bidi: bidi-override;
        }
        [data-star]::before {
            display:block;
            content: "\f005" "\f005" "\f005" "\f005" "\f005";
            width: 100%;
            font-family: "Font Awesome 6 Free";
            font-weight: 900;
            font-size: 15px;;
            color: var(--body-color);
        }
        [data-star]::after {
            white-space:nowrap;
            position:absolute;
            top:0;
            left:0;
            content: "\f005" "\f005" "\f005" "\f005" "\f005";
            width: 100%;
            font-family: "Font Awesome 6 Free";
            font-weight: 900;
            font-size: 15px;;
            width: 0;
            color: var(--secondary-color);
            overflow:hidden;
            height:100%;
        }

        [data-star^="0.1"]::after{width:2%}
        [data-star^="0.2"]::after{width:4%}
        [data-star^="0.3"]::after{width:6%}
        [data-star^="0.4"]::after{width:8%}
        [data-star^="0.5"]::after{width:10%}
        [data-star^="0.6"]::after{width:12%}
        [data-star^="0.7"]::after{width:14%}
        [data-star^="0.8"]::after{width:16%}
        [data-star^="0.9"]::after{width:18%}
        [data-star^="1"]::after{width:20%}
        [data-star^="1.1"]::after{width:22%}
        [data-star^="1.2"]::after{width:24%}
        [data-star^="1.3"]::after{width:26%}
        [data-star^="1.4"]::after{width:28%}
        [data-star^="1.5"]::after{width:30%}
        [data-star^="1.6"]::after{width:32%}
        [data-star^="1.7"]::after{width:34%}
        [data-star^="1.8"]::after{width:36%}
        [data-star^="1.9"]::after{width:38%}
        [data-star^="2"]::after{width:40%}
        [data-star^="2.1"]::after{width:42%}
        [data-star^="2.2"]::after{width:44%}
        [data-star^="2.3"]::after{width:46%}
        [data-star^="2.4"]::after{width:48%}
        [data-star^="2.5"]::after{width:50%}
        [data-star^="2.6"]::after{width:52%}
        [data-star^="2.7"]::after{width:54%}
        [data-star^="2.8"]::after{width:56%}
        [data-star^="2.9"]::after{width:58%}
        [data-star^="3"]::after{width:60%}
        [data-star^="3.1"]::after{width:62%}
        [data-star^="3.2"]::after{width:64%}
        [data-star^="3.3"]::after{width:66%}
        [data-star^="3.4"]::after{width:68%}
        [data-star^="3.5"]::after{width:70%}
        [data-star^="3.6"]::after{width:72%}
        [data-star^="3.7"]::after{width:74%}
        [data-star^="3.8"]::after{width:76%}
        [data-star^="3.9"]::after{width:78%}
        [data-star^="4"]::after{width:80%}
        [data-star^="4.1"]::after{width:82%}
        [data-star^="4.2"]::after{width:84%}
        [data-star^="4.3"]::after{width:86%}
        [data-star^="4.4"]::after{width:88%}
        [data-star^="4.5"]::after{width:90%}
        [data-star^="4.6"]::after{width:92%}
        [data-star^="4.7"]::after{width:94%}
        [data-star^="4.8"]::after{width:96%}
        [data-star^="4.9"]::after{width:98%}
        [data-star^="5"]::after{width:100%}
    </style>
@endsection
@section('content')
    <div class="dashboard__body">
        <div class="myOrder_single bg-white padding-20 radius-10">
            <div class="row g-4">
                <div class="col-md-12">
                    <x-notice.general-notice :description="__('Notice: If a completed order is canceled by the admin, the order amount will be credited to the client wallet and deducted from the freelancer wallet.')" :description1="__('Notice: If an order is in the queue and funded, an admin has the authority to decline it. If an admin decline a queued order, the order amount will be credited back to the client wallet.')" />
                </div>

                @php
                    $mile_stones = \App\Models\OrderMilestone::where('order_id',$order_details->id)->get();
                    $payable_amount = \App\Models\OrderMilestone::where('order_id',$order_details->id)->where('status','!=',3)->sum('price');
                @endphp

                <div class="col-xl-3 col-lg-6 col-sm-6 col-md-4">
                    <div class="contract_single__balance hover-question">
                        <div class="contract_single__balance-flex flex-between">
                            <div class="contract_single__balance-contents">
                                @if($order_details->status == 3)
                                    <h4 class="contract_single__balance-price">{{ float_amount_with_currency_symbol($order_details->payable_amount) }}</h4>
                                @else
                                    <h4 class="contract_single__balance-price">{{ site_currency_symbol() }} 0</h4>
                                @endif
                                <p class="contract_single__balance-para">{{ __('Total Paid') }}</p>
                            </div>
                            <span class="contract_single__balance-icon">
                                <i class="fa-solid fa-question"></i>
                                <small class="hover-active-content">{{ __('Total paid means how much amount freelancer get for this order.') }}</small>
                            </span>
                        </div>
                    </div>
                </div>

                @if($order_details->is_fixed_hourly == 'hourly' && $order_details->status != 3)
                    <div class="col-xl-3 col-lg-6 col-sm-6 col-md-4">
                        <div class="contract_single__balance hover-question">
                            <div class="contract_single__balance-flex flex-between">
                                <div class="contract_single__balance-contents">
                                    <h4 class="contract_single__balance-price">{{ float_amount_with_currency_symbol($order_details?->job->hourly_rate) }}</h4>
                                    <p class="contract_single__balance-para">{{ __('Hourly Rate') }}</p>
                                </div>
                                <span class="contract_single__balance-icon">
                                <i class="fa-solid fa-question"></i>
                                <small class="hover-active-content">{{ __('Hourly rate means how much amount client will pay for each hour after complete the order.') }}</small>
                            </span>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="col-xl-3 col-lg-6 col-sm-6 col-md-4">
                    <div class="contract_single__balance hover-question">
                        <div class="contract_single__balance-flex flex-between">
                            <div class="contract_single__balance-contents">
                                @if($mile_stones->count() > 0)
                                    @if($order_details->status !=3)
                                        @php
                                            $earnings = \App\Models\OrderMilestone::where('order_id', $order_details->id)
                                                ->where('status', 2)
                                                ->sum('price');
                                        @endphp
                                        <h4 class="contract_single__balance-price">{{ float_amount_with_currency_symbol($payable_amount - $earnings) }} </h4>
                                    @else
                                        <h4 class="contract_single__balance-price">{{ site_currency_symbol() }} 0</h4>
                                    @endif
                                @else
                                    @if($order_details->status != 3 && $order_details->status != 4 && $order_details->payment_status != '')
                                        <h4 class="contract_single__balance-price">{{ float_amount_with_currency_symbol($order_details->payable_amount) }}</h4>
                                    @else
                                        <h4 class="contract_single__balance-price">{{ site_currency_symbol() }} 0</h4>
                                    @endif
                                @endif

                                <p class="contract_single__balance-para">{{ __('Pending Amount') }}</p>
                            </div>
                            <span class="contract_single__balance-icon">
                                <i class="fa-solid fa-question"></i>
                                <small class="hover-active-content">{{ __('Pending amount means how much amount freelancer will get after complete this order.') }}</small>
                            </span>
                        </div>
                    </div>
                </div>
                @endif

                @if($order_details->is_fixed_hourly == 'hourly' && $order_details->status != 3)
                    <div class="col-xl-3 col-lg-6 col-sm-6 col-md-4">
                        <div class="contract_single__balance hover-question">
                            <div class="contract_single__balance-flex flex-between">
                                <div class="contract_single__balance-contents">
                                    <h4 class="contract_single__balance-price">{{ $order_details?->job->estimated_hours }}</h4>
                                    <p class="contract_single__balance-para">{{ __('Estimated Hours') }}</p>
                                </div>
                                <span class="contract_single__balance-icon">
                                <i class="fa-solid fa-question"></i>
                                <small class="hover-active-content">{{ __('Estimated hours refer to the approximate time a client can set for completing the order. The client can adjust this time before accepting the order.') }}</small>
                            </span>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="col-xl-3 col-lg-6 col-sm-6 col-md-4">
                    <div class="contract_single__balance hover-question">
                        <div class="contract_single__balance-flex flex-between">
                            <div class="contract_single__balance-contents">
                                <h4 class="contract_single__balance-price">{{ float_amount_with_currency_symbol($order_details->commission_amount) }}</h4>
                                <p class="contract_single__balance-para">{{ __('Commission Amount') }}</p>
                            </div>
                            <span class="contract_single__balance-icon">
                                <i class="fa-solid fa-question"></i>
                                <small class="hover-active-content">{{ __('Commission amount means how much amount admin will get from this order.') }}</small>
                            </span>
                        </div>
                    </div>
                </div>
                @endif

                @if($order_details->is_fixed_hourly == 'hourly' && $order_details->status != 3)
                    <div class="col-xl-3 col-lg-6 col-sm-6 col-md-4">
                        <div class="contract_single__balance hover-question">
                            <div class="contract_single__balance-flex flex-between">
                                <div class="contract_single__balance-contents">
                                    <h4 class="contract_single__balance-price">{{ float_amount_with_currency_symbol($order_details->price) }}</h4>
                                    <p class="contract_single__balance-para">{{ __('Approximate  Budget') }}</p>
                                </div>
                                <span class="contract_single__balance-icon">
                                <i class="fa-solid fa-question"></i>
                                <small class="hover-active-content">{{ __('The approximate budget indicates the expected payment for this order. This amount may vary depending on the rate and the estimated working hours.') }}</small>
                            </span>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="col-xl-3 col-lg-6 col-sm-6 col-md-4">
                    <div class="contract_single__balance hover-question">
                        <div class="contract_single__balance-flex flex-between">
                            <div class="contract_single__balance-contents">
                                <h4 class="contract_single__balance-price">{{ float_amount_with_currency_symbol($order_details->transaction_amount) }}</h4>
                                <p class="contract_single__balance-para">{{ __('Transaction Amount') }}</p>
                            </div>
                            <span class="contract_single__balance-icon">
                                <i class="fa-solid fa-question"></i>
                                <small class="hover-active-content">{{ __('Transaction amount means how much amount user will pay for each transaction.') }}</small>
                            </span>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
        <div class="dashboard__right__flex gap-4">
            <div class="dashboard__jobFilter responsive-lg">
                <div class="shop-sidebar-content">
                    <div class="shop-close-content radius-10">
                        <div class="single-shop-left user-details-manage bg-white radius-10">
                            <h6 class="user-details-manage-title">
                                <span class="user-details-manage-thumb">
                                    @if($order_details?->user->image)
                                        @if(cloudStorageExist() && in_array(Storage::getDefaultDriver(), ['s3', 'cloudFlareR2', 'wasabi']))
                                            <img src="{{ render_frontend_cloud_image_if_module_exists( 'profile/'. $order_details?->user->image, load_from: $order_details?->user->load_from ?? '') }}" alt="{{ __('profile img') }}">
                                        @else
                                            <img src="{{ asset('assets/uploads/profile/'.$order_details?->user->image) }}" alt="{{ __('Client Image') }}">
                                        @endif
                                    @else
                                    <a href="javascript:void(0)"><img src="{{ asset('assets/static/img/author/author.jpg') }}" alt="{{ __('AuthorImg') }}"></a>
                                    @endif
                                </span> {{ __('Client Details') }}
                            </h6>
                            <x-order.user-details
                                :firstName="$order_details?->user->first_name"
                                :lastName="$order_details?->user->last_name"
                                :email="$order_details?->user->email"
                                :phone="$order_details?->user->phone"
                                :country=" $order_details->user?->user_country?->country"
                                :state=" $order_details->user?->user_state?->state"
                                :city=" $order_details->user?->user_city?->city"
                            />
                        </div>

                        <div class="single-shop-left bg-white radius-10 mt-5">
                            <h6 class="user-details-manage-title">
                                <span class="user-details-manage-thumb">
                                    @if($order_details?->freelancer->image)
                                        @if(cloudStorageExist() && in_array(Storage::getDefaultDriver(), ['s3', 'cloudFlareR2', 'wasabi']))
                                            <img src="{{ render_frontend_cloud_image_if_module_exists( 'profile/'. $order_details?->freelancer->image, load_from: $order_details?->freelancer->load_from ?? '') }}" alt="{{ __('profile img') }}">
                                        @else
                                            <img src="{{ asset('assets/uploads/profile/'.$order_details?->freelancer->image) }}" alt="{{ __('Client Image') }}">
                                        @endif
                                    @else
                                    <a href="javascript:void(0)"><img src="{{ asset('assets/static/img/author/author.jpg') }}" alt="{{ __('AuthorImg') }}"></a>
                                    @endif
                                </span>
                                {{ __('Freelancer Details') }}
                            </h6>
                            <x-order.user-details
                                :firstName="$order_details?->freelancer->first_name"
                                :lastName="$order_details?->freelancer->last_name"
                                :email="$order_details?->freelancer->email"
                                :phone="$order_details?->freelancer->phone"
                                :country=" $order_details->freelancer?->user_country?->country"
                                :state=" $order_details->freelancer?->user_state?->state"
                                :city=" $order_details->freelancer?->user_city?->city"
                            />
                            <div class="btn-wrapper mt-4">
                                <a href="{{ route('freelancer.profile.details',$order_details->freelancer?->username) }}" target="_blank" class="btn-profile btn-bg-1">{{ __('View Profile') }}</a>
                            </div>
                        </div>

                        @if($order_details->rating)
                            @foreach($order_details->rating as $rating)
                                @if($rating->sender_type == 1)
                                <div class="single-shop-left bg-white radius-10 mt-5">
                                    <h6 class="user-details-manage-title">{{ __('Client Rating') }}
                                        <div class="single-project-content-review">
                                            <span class="single-project-content-review-icon"> <i class="fa-solid fa-star"></i> </span>
                                            <span class="single-project-content-review-rating">{{ $rating->rating }} </span>
                                        </div>
                                    </h6>

                                    <div class="user-details-manage-list mt-4">
                                        <?php $rating_details = \App\Models\RatingDetails::where('rating_id',$rating->id)->get() ?>
                                        @foreach($rating_details as $details)
                                            <div class="rating_profile_details">
                                                <div class="rating_profile_details_icon">
                                                    <i data-star="{{ $details->rating }}"></i>
                                                </div>
                                                <span class="rating_profile_details-para">({{ ucfirst($details->type) }})</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                @endif
                                @if($rating->sender_type == 2)
                                    <div class="single-shop-left bg-white radius-10 mt-5">
                                        <h6 class="user-details-manage-title">{{ __('Freelancer Rating') }}
                                            <div class="single-project-content-review">
                                                <span class="single-project-content-review-icon"> <i class="fa-solid fa-star"></i> </span>
                                                <span class="single-project-content-review-rating">{{ $rating->rating }} </span>
                                            </div>
                                        </h6>

                                        <div class="user-details-manage-list mt-4">
                                            <?php $rating_details = \App\Models\RatingDetails::where('rating_id',$rating->id)->get() ?>
                                            @foreach($rating_details as $details)
                                                <div class="rating_profile_details">
                                                    <div class="rating_profile_details_icon">
                                                        <i data-star="{{ $details->rating }}"></i>
                                                    </div>
                                                    <span class="rating_profile_details-para">({{ ucfirst($details->type) }})</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        @endif

                    </div>
                </div>
            </div>
            <div class="dashboard__inner">
                <div>
                    <div class="myOrder_single bg-white padding-20 radius-10">
                        <div class="myOrder_single__item">
                            <div class="myOrder_single__flex">
                                <div class="myOrder_single__content">
                                    <span class="myOrder_single__content__id">#000{{ $order_details->id }}</span>
                                    <h4 class="myOrder-single-content-title mt-2">
                                        @if($order_details->is_project_job == 'project')
                                            <a target="_blank" href="{{ route('project.details', ['username' => $order_details?->project?->project_creator?->username, 'slug' => $order_details?->project->slug]) }}"> {{ $order_details?->project->title ?? '' }} </a>
                                        @elseif($order_details->is_project_job == 'job')
                                            <a target="_blank" href="{{ route('job.details', ['username' => $order_details?->job?->job_creator?->username, 'slug' => $order_details?->job->slug]) }}">{{ $order_details?->job->title ?? '' }}</a>
                                        @else
                                            {{ __('Custom order')}}
                                        @endif
                                    </h4>
                                    <div class="myOrder_single__content__btn flex-btn gap-2 mt-3">
                                        @if($order_details->status === 0)
                                            <span class="pending-approval active">{{ __('Queue Order') }}</span>
                                        @endif
                                        @if($order_details->status === 1)
                                            <span class="pending-approval active">{{ __('Active Order') }}</span>
                                        @endif
                                        @if($order_details->status === 2)
                                            <span class="pending-approval active">{{ __('Deliver Order') }}</span>
                                        @endif
                                        @if($order_details->status === 3)
                                            <span class="pending-approval active">{{ __('Complete Order') }}</span>
                                        @endif
                                        @if($order_details->status === 4)
                                            <span class="pending-approval active">{{ __('Cancel Order') }}</span>
                                        @endif
                                        @if($order_details->status === 5)
                                            <span class="pending-approval active">{{ __('Decline Order') }}</span>
                                        @endif
                                        @if($order_details->status === 7)
                                            <span class="pending-approval active">{{ __('Hold Order') }}</span>
                                        @endif
                                        @if($order_details->is_project_job === 'offer')
                                            <a href="javascript:void(0)" class="pending-approval secondary-color">{{ __('Custom Order') }}</a>
                                        @endif
                                    </div>
                                </div>
                                <span class="myOrder_single__content__time">{{ $order_details->created_at->diffForHumans() }} </span>
                            </div>
                        </div>
                        <div class="myOrder_single__item">
                            <div class="myOrder_single__block">
                                <div class="myOrder_single__block__item">
                                    <div class="myOrder_single__block__item__content">
                                        @if($order_details->is_fixed_hourly == 'hourly')
                                            <span class="myOrder_single__block__subtitle">{{ __('Type') }}</span>
                                            <h6 class="myOrder_single__block__title mt-2">{{ ucfirst($order_details->is_fixed_hourly) }}
                                        @else
                                            <span class="myOrder_single__block__subtitle">{{ __('Order budget') }}</span>
                                            <h6 class="myOrder_single__block__title mt-2">{{ float_amount_with_currency_symbol($order_details->price) }}
                                        @endif

                                        @if($order_details->is_fixed_hourly != 'hourly')
                                            @if($order_details->payment_status == 'complete')
                                                <span class="order-funded-btn">{{ __('Order Funded') }}</span>
                                            @else
                                                @if($order_details->payment_gateway != 'manual_payment' && $order_details->payment_status == 'pending')
                                                    <span class="order-funded-btn">{{ __('Payment Failed') }}</span>
                                                @else
                                                    <span class="order-funded-btn">{{ __('Not Funded') }}</span>
                                                @endif
                                            @endif
                                        @endif
                                        </h6>
                                    </div>
                                </div>
                                @if($order_details->revision > 0 )
                                <div class="myOrder_single__block__item">
                                    <div class="myOrder_single__block__item__content">
                                        <span class="myOrder_single__block__subtitle">{{ __('Revision') }}</span>
                                        <h6 class="myOrder_single__block__title mt-2">{{ $order_details->revision }}</h6>
                                    </div>
                                </div>
                                    <div class="myOrder_single__block__item">
                                        <div class="myOrder_single__block__item__content">
                                            <span class="myOrder_single__block__subtitle">{{ __('Revision Left') }}</span>
                                            <h6 class="myOrder_single__block__title mt-2">{{ $order_details->revision_left }}</h6>
                                        </div>
                                    </div>
                                @endif
                                @if($order_details?->order_mile_stones->count() == 0)
                                <div class="myOrder_single__block__item">
                                    <div class="myOrder_single__block__item__content">
                                        <span class="myOrder_single__block__subtitle">{{ __('Delivery Time') }}</span>
                                        <h6 class="myOrder_single__block__title mt-2">{{ $order_details->delivery_time }} </h6>
                                    </div>
                                </div>
                                @endif
                                <div class="myOrder_single__block__item">
                                    <div class="myOrder_single__block__item__author">
                                        <a href="javascript:void(0)">
                                            @if($order_details?->user->image)
                                                @if(cloudStorageExist() && in_array(Storage::getDefaultDriver(), ['s3', 'cloudFlareR2', 'wasabi']))
                                                    <img src="{{ render_frontend_cloud_image_if_module_exists( 'profile/'. $order_details?->user?->image, load_from: $order_details?->user?->load_from ?? '') }}" alt="{{ __('profile img') }}">
                                                @else
                                                    <img src="{{ asset('assets/uploads/profile/'.$order_details?->user->image) }}" alt="{{ __('Client Image') }}">
                                                @endif
                                            @else
                                                <a href="javascript:void(0)"><img src="{{ asset('assets/static/img/author/author.jpg') }}" alt="{{ __('AuthorImg') }}"></a>
                                            @endif
                                        </a>
                                    </div>
                                    <div class="myOrder_single__block__item__content">
                                        <span class="myOrder_single__block__subtitle">{{ __('Customer') }}</span>
                                        <h6 class="myOrder_single__block__title mt-2">{{ $order_details?->user->username }} </h6>
                                    </div>
                                </div>

                                @if($order_details->status != 3)
                                    <div class="myOrder_single__block__item">
                                        <div class="myOrder_single__block__item__content">
                                            <span class="myOrder_single__block__subtitle">{{ __('Payment') }}</span> <br>
                                            <x-order.payment-verify :paymentVerifyCheck="$order_details" />
                                        </div>
                                    </div>
                                @endif

                            </div>
                        </div>
                        <div class="myOrder_single__item">
                            <div class="myOrder_single__flex flex-between">
                                <a href="javascript:void(0)"
                                   class="btn-profile btn-outline-gray check_live_chat_message"
                                   data-bs-target="#checkChatModal"
                                   data-bs-toggle="modal"
                                >
                                    <i class="fa-regular fa-comments"></i>
                                    {{ __('Check Chat') }}
                                </a>

                                @php
                                    $check_order_has_report_by_client = \App\Models\Report::where('client_id',$order_details->user_id)
                                    ->where('order_id',$order_details->id)
                                    ->where('reporter','client')
                                    ->first();
                                @endphp

                                @if(!empty($check_order_has_report_by_client) && $check_order_has_report_by_client->status == 0)
                                    <a tabindex="0" class="btn-profile btn-bg-cancel swal_status_change_button_for_cancel_order">{{ __('Cancel Order') }}</a>
                                    <form method='post' action="{{route('admin.order.status',$order_details->id)}}" class="d-none">
                                        <input type='hidden' name='_token' value='{{csrf_token()}}'>
                                        <br>
                                        <button type="submit" class="swal_form_submit_btn d-none"></button>
                                    </form>
                                @endif
                                @if($order_details->status == 0 && $order_details->payment_status == 'complete')
                                    <a tabindex="0" class="btn-profile btn-bg-cancel swal_status_change_button_for_decline_order">{{ __('Decline Order') }}</a>
                                    <form method='post' action="{{route('admin.order.decline',$order_details->id)}}" class="d-none">
                                        <input type='hidden' name='_token' value='{{csrf_token()}}'>
                                        <br>
                                        <button type="submit" class="swal_form_submit_btn d-none"></button>
                                    </form>
                                @endif
                                @if(!empty($check_order_has_report_by_client) && $check_order_has_report_by_client->status == 1)
                                    <span tabindex="0" class="btn-profile btn-bg-cancel">{{ __('Cancel By Admin') }}</span>
                                @endif

                                <div class="btn-wrapper flex-btn">
                                    @if($order_details->user?->is_suspend == 1 && $order_details->freelancer?->is_suspend == 1)
                                        <button type="button" class="btn-profile btn-bg-cancel">{{ __('Both user suspended') }}</button>
                                    @else
                                        <a href="{{ route('admin.account.suspend',$order_details->id) }}" class="btn-profile btn-bg-cancel">{{ __('Suspend Account') }}</a>
                                    @endif
                                    @if($order_details->status == 1 || $order_details->status == 2)
                                        <x-status.table.status-change
                                            :title="__('Hold Order')"
                                            :class="'btn-profile btn-bg-1 hold_order_status'"
                                            :url="route('admin.order.hold',$order_details->id)"/>
                                    @endif
                                    @if($order_details->status== 7)
                                        <x-status.table.status-change
                                            :title="__('Unhold Order')"
                                            :class="'btn-profile btn-bg-1 unhold_order_status'"
                                            :url="route('admin.order.unhold',$order_details->id)"/>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @if($mile_stones->count() > 0 || $order_details?->order_submit_history->count() > 0 || $order_details->description != '')
                        <div class="myOrder_single bg-white padding-20 radius-10">
                            <div class="contract_single__header profile-border-bottom">
                                <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                                    @if($mile_stones->count() > 0)
                                        <li class="nav-item" role="presentation">
                                            <button class="btn" data-bs-toggle="pill" data-bs-target="#milestone_display" type="button" role="tab" aria-controls="pills-home" aria-selected="true">Milestones</button>
                                        </li>
                                    @endif
                                    @if($order_details->description)
                                        <li class="nav-item" role="presentation">
                                            <button class="btn" data-bs-toggle="pill" data-bs-target="#description_display" type="button" role="tab" aria-controls="pills-profile" aria-selected="false">Description & Requirements</button>
                                        </li>
                                    @endif
                                    @if($order_details?->order_submit_history->count() > 0)
                                        <li class="nav-item" role="presentation">
                                            <button class="btn" data-bs-toggle="pill" data-bs-target="#work_display" type="button" role="tab" aria-controls="pills-contact" aria-selected="false">Works Submitted</button>
                                        </li>
                                    @endif
                                </ul>
                                <div class="tab-content" id="pills-tabContent">
                                    <div class="tab-pane fade  @if($mile_stones->count() > 0) show active @endif" id="milestone_display" role="tabpanel" aria-labelledby="pills-home-tab">
                                        <div class="contract_single__inner">
                                            <div class="contract_single__milestone">
                                                @foreach($mile_stones as $mile_stone)
                                                    <div class="contract_single__milestone__item">
                                                        <div class="contract_single-flex flex-between align-items-start">
                                                            <div class="contract_single__contents">
                                                                <h4 class="contract_single__title mt-3">{{ $mile_stone->title }}</h4>
                                                                <div class="contract_single__priceCompleted d-flex gap-2 mt-3">
                                                                   <span class="pending-approval secondary-color">{{ float_amount_with_currency_symbol($mile_stone->price) }}</span>
                                                                   @if($mile_stone->status === 1)
                                                                       <span class="fixed-btn not-started">{{ __('Active') }}</span>
                                                                   @else
                                                                       @if($mile_stone->status === 0)
                                                                           <span class="fixed-btn not-started">{{ __('Pending') }}</span>
                                                                       @endif
                                                                       @if($mile_stone->status === 2)
                                                                           <span class="fixed-btn not-started">{{ __('Complete') }}</span>
                                                                       @endif
                                                                       @if($mile_stone->status === 3)
                                                                           <span class="fixed-btn not-started">{{ __('Cancel') }}</span>
                                                                       @endif
                                                                   @endif
                                                                    <span class="job-progress not-started"><strong>{{ __('Revision:') }}</strong> {{ $mile_stone->revision }}</span>
                                                                    <span class="job-progress not-started"><strong>{{ __('Revision Left:') }}</strong> {{ $mile_stone->revision_left }}</span>
                                                                </div>
                                                                <br>
                                                                <span class="contract_single__date__para"><strong>{{ __('Delivery Time:') }}</strong> {{ $mile_stone->deadline }}</span>
                                                                <p class="mt-4">{{ $mile_stone->description }}</p>
                                                            </div>
                                                            <div class="contract_single__right">
                                                                @if($order_details->payment_status == 'complete')
                                                                    <span class="job-progress not-started">{{ __('Funded') }}</span>
                                                                @else
                                                                    <span class="job-progress not-started">{{ __('Not Funded') }}</span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade @if($mile_stones->count() == 0 && $order_details->description !='') show active @endif" id="description_display" role="tabpanel" aria-labelledby="pills-profile-tab">
                                        <p class="myOrder-single-content-para">{{ $order_details->description ?? '' }}</p>
                                    </div>
                                    <div class="tab-pane fade @if($mile_stones->count() == 0 && $order_details->description == '') show active @endif" id="work_display" role="tabpanel" aria-labelledby="pills-contact-tab">
                                        @foreach($order_details->order_submit_history as $history)
                                            <div class="pay-now-single-contents-work">
                                                <div class="pay-now-single-contents-work-flex">
                                                    <div class="pay-now-single-contents-work-item">
                                                        <span class="pay-now-single-contents-work-date">{{ $history->created_at->toFormattedDateString() ?? '' }}</span>
                                                    </div>
                                                    <div class="pay-now-single-contents-work-item">
                                                        <div class="single-refundRequest-item">
                                                            <a href="{{ asset('assets/uploads/attachment/order/'.$history->attachment) }}" download class="single-refundRequest-item-uploads">
                                                                <i class="fa-solid fa-cloud-arrow-down"></i>
                                                                {{ __('Download Attachment') }}
                                                            </a>
                                                        </div>
                                                    </div>
                                                    <div class="pay-now-single-contents-work-item">
                                                        <div class="pay-now-single-contents-work-item-status">
                                                            @if($history->status === 0)
                                                                <span class="milestone-approved ">{{  __('Pending') }}</span>
                                                            @elseif($history->status === 1)
                                                                <span class="myJob-wrapper-single-fixed active">{{ __('Approved') }}</span>
                                                            @elseif($history->status === 2)
                                                                <span class="btn myJob-wrapper-single-fixed danger show_revision_details"
                                                                      data-bs-target="#RevisionDetailsModal"
                                                                      data-bs-toggle="modal"
                                                                      data-revision_id="{{ $history->request_revision->id }}"
                                                                      data-revision_description="{{ $history->request_revision->description }}">
                                                                    {{ __('Revision Details') }}
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="pay-now-single-contents-work-item">
                                                        <div class="pay-now-single-contents-work-item-btn">
                                                            <a href="javascript:void(0)"
                                                               class="pay-now-single-contents-work-viewMore order_submit_description"
                                                               data-description="{{ $history->description }}"
                                                               data-order_milestone_id="{{ $history->order_milestone_id }}"
                                                               data-bs-toggle="modal"
                                                               data-bs-target="#OrderSubmitDescriptionModal">
                                                                {{ __('Description') }}
                                                                <i class="fa-solid fa-angle-right"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@include('backend.pages.orders.order-submit-description')
@include('backend.pages.orders.revision-details')
@include('backend.pages.orders.check-chat-modal')

@section('script')
    <x-sweet-alert.sweet-alert2-js/>
    @include('backend.pages.orders.order-js')
@endsection
