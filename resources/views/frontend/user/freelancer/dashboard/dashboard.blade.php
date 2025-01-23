@extends('frontend.layout.master')
@section('site_title',__('Dashboard'))
@section('style')
    <style>
     .total_balance{background-color: #e3e1ff !important;}
     .single-profile-settings-header {
         justify-content: space-between;
         gap: 10px;
         flex-wrap: wrap;
         align-items: center;
         .btn-profile {
             padding-left: 10px;
             padding-right: 10px;
         }
     }
    </style>
@endsection

@section('content')
    <main>
        <x-breadcrumb.user-profile-breadcrumb :title="__('Dashboard')" :innerTitle="__('Dashboard')"/>
        <!-- Profile Settings area Starts -->
        <div class="responsive-overlay"></div>
        <div class="profile-settings-area pat-100 pab-100 section-bg-2">
            <div class="container">
                <div class="row g-4">
                    @include('frontend.user.layout.partials.sidebar')
                    <div class="col-xl-9 col-lg-8">
                        <div class="profile-settings-wrapper">

                            <div class="single-profile-settings">
                                <div class="single-profile-settings-header d-flex">
                                    <div class="single-profile-settings-header-flex">
                                        <x-form.form-title :title="__('Dashboard Info')" :class="'single-profile-settings-header-title'" />
                                    </div>
                                    @if(get_static_option('profile_switch_enable_disable') != 'disable')
                                    <div class="switcher">
                                        <div>
                                            <select class="btn-profile btn-bg-1" onchange="switchProfile(this.value)">
                                                @if(Session::get('user_role') == 'client')
                                                    <option value="client" selected @if(Session::get('user_role') == 'client') class="d-none" @endif>{{ __('Acting as Client') }}</option>
                                                    <option value="freelancer">{{ __('Switch to Freelancer') }}</option>
                                                @elseif(Session::get('user_role') == 'freelancer')
                                                    <option value="freelancer" selected @if(Session::get('user_role') == 'freelancer') class="d-none" @endif>{{ __('Acting as Freelancer') }}</option>
                                                    <option value="client">{{ __('Switch to Client') }}</option>
                                                @else
                                                    <option value="freelancer" selected>{{ __('Acting as Freelancer') }}</option>
                                                    <option value="client">{{ __('Switch to Client') }}</option>
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                                <div class="single-profile-settings-inner profile-border-top">
                                    <div class="row">

                                        <div class="col-xxl-3 col-lg-6 col-sm-6 col-md-4">
                                            <div class="myJob-wrapper-single-balance total_balance">
                                                <div class="myJob-wrapper-single-balance-contents">
                                                    <div class="myJob-wrapper-single-balance-price d-flex gap-2 justify-content-between">
                                                        <h4 class="contract_single__balance-price">{{ float_amount_with_currency_symbol($total_wallet_balance) ?? 0.0 }}</h4>
                                                    </div>
                                                    <p class="myJob-wrapper-single-balance-para">{{ __('Wallet Balance') }}</p>
                                                </div>
                                            </div>
                                        </div>

                                        @if(get_static_option('project_enable_disable') != 'disable')
                                        <div class="col-xxl-3 col-lg-6 col-sm-6 col-md-4">
                                            <a href="{{ route('freelancer.profile.details', Auth::guard('web')->user()->username) }}">
                                                <div class="myJob-wrapper-single-balance">
                                                    <div class="myJob-wrapper-single-balance-contents">
                                                        <div class="myJob-wrapper-single-balance-price d-flex gap-2 justify-content-between">
                                                            <h4 class="contract_single__balance-price">{{ $total_project ?? 0 }}</h4>
                                                        </div>
                                                        <p class="myJob-wrapper-single-balance-para">{{ __('Total Projects') }}</p>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                       @endif
                                        <div class="col-xxl-3 col-lg-6 col-sm-6 col-md-4">
                                            <div class="myJob-wrapper-single-balance">
                                                <div class="myJob-wrapper-single-balance-contents">
                                                    <div class="myJob-wrapper-single-balance-price d-flex gap-2 justify-content-between">
                                                        <h4 class="contract_single__balance-price">{{ $complete_order ?? 0 }}</h4>
                                                    </div>
                                                    <p class="myJob-wrapper-single-balance-para">{{ __('Complete Order') }}</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xxl-3 col-lg-6 col-sm-6 col-md-4">
                                            <div class="myJob-wrapper-single-balance">
                                                <div class="myJob-wrapper-single-balance-contents">
                                                    <div class="myJob-wrapper-single-balance-price d-flex gap-2 justify-content-between">
                                                        <h4 class="contract_single__balance-price">{{ $active_order ?? 0 }}</h4>
                                                    </div>
                                                    <p class="myJob-wrapper-single-balance-para">{{ __('Active Order') }}</p>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            {{--//latest orders--}}
                            <div class="single-profile-settings">
                                <div class="single-profile-settings-header">
                                    <div class="single-profile-settings-header-flex">
                                        <x-form.form-title :title="__('Latest Orders')" :class="'single-profile-settings-header-title'" />
                                        <a href="{{ route('freelancer.order.all') }}" class="btn-profile btn-bg-1"> {{ __('All Orders') }} </a>
                                    </div>
                                </div>
                                <div class="single-profile-settings-inner profile-border-top">
                                    <div class="custom_table style-04">
                                        <table>
                                            <thead>
                                            <tr>
                                                <th>{{ __('Budget') }}</th>
                                                <th>{{ __('Delivery Time') }}</th>
                                                <th>{{ __('Payment Status') }}</th>
                                                <th>{{ __('Create Date') }}</th>
                                                <th>{{ __('Order Details') }}</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($latest_orders as $order)
                                                <tr>
                                                    <td>{{ float_amount_with_currency_symbol($order->price) ?? '' }}</td>
                                                    <td>{{ __($order->delivery_time) ?? '' }}</td>
                                                    <td>{{ __($order->payment_status) ?? '' }}</td>
                                                    <td>{{ $order->created_at->toFormattedDateString() }}</td>
                                                    <td><a href="{{ route('freelancer.order.details',$order->id) }}" class="btn-profile btn-bg-1">{{ __('Order Details') }}</a></td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            {{--my projects--}}
                            @if(get_static_option('project_enable_disable') != 'disable')
                                <div class="single-profile-settings">
                                    <div class="single-profile-settings-header">
                                        <div class="single-profile-settings-header-flex">
                                            <x-form.form-title :title="__('Latest Projects')" :class="'single-profile-settings-header-title'" />
                                            <a href="{{ route('freelancer.profile.details', Auth::guard('web')->user()->username) }}" class="btn-profile btn-bg-1"> {{ __('All Projects') }} </a>
                                        </div>
                                    </div>
                                    <div class="single-profile-settings-inner profile-border-top">
                                        <div class="custom_table style-04">
                                            <table>
                                                <thead>
                                                <tr>
                                                    <th>{{ __('Title') }}</th>
                                                    <th>{{ __('Action') }}</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($my_projects as $project)
                                                    <tr>
                                                        <td>{{ $project->title }}</td>
                                                        <td>
                                                            <a href="{{ route('freelancer.project.edit',$project->id) }}" class="btn-profile btn-bg-1 edit_info_show_hide"> {{ __('Edit Project') }} </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            @endif

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Profile Settings area end -->
    </main>
@endsection

@section('script')
    <script>
        function switchProfile(role) {
            $.ajax({
                url:"{{ route('freelancer.switch.profile')}}",
                method:'post',
                data:{role:role},
                success:function(res){
                    if(res.status=='success'){
                        if(res.user_role == 'freelancer'){
                            window.location.href = "{{ route('freelancer.dashboard') }}";
                        }
                        if(res.user_role == 'client'){
                            window.location.href = "{{ route('client.dashboard') }}";
                        }
                        // location.reload();
                    }
                }
            });
        }
    </script>
@endsection
