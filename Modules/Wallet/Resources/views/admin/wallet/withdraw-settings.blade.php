@extends('backend.layout.master')
@section('title', __('Withdraw Amount Settings'))
@section('content')
    <div class="dashboard__body">
        <div class="row">
            <div class="col-lg-6">
                <x-notice.general-notice
                    :description="__('Notice: Withdraw amount settings allow you to define the minimum and maximum amounts that can be withdrawn. For example, if the minimum is set to 50, a user can only make a withdrawal request if their balance is equal to or exceeds 50.')"
                    :description1="__('Notice: For example, if you set the maximum withdrawal amount to 100, a user will not be able to withdraw more than 100 in each request.')"
                />
                <div class="customMarkup__single">
                    <div class="customMarkup__single__item">
                        <div class="customMarkup__single__item__flex">
                            <h4 class="customMarkup__single__title">{{ __('Withdraw Amount Settings') }}</h4>
                        </div>
                        <x-validation.error />
                        <div class="customMarkup__single__inner mt-4">
                            <form action="{{route('admin.wallet.withdraw.settings')}}" method="POST">
                                @csrf
                                <x-form.text
                                    :title="__('Minimum amount')"
                                    :type="__('text')"
                                    :name="'minimum_withdraw_amount'"
                                    :id="'minimum_withdraw_amount'"
                                    :value="get_static_option('minimum_withdraw_amount') ?? ''"
                                />
                                <x-form.text
                                    :title="__('Maximum amount')"
                                    :type="__('text')"
                                    :name="'maximum_withdraw_amount'"
                                    :id="'maximum_withdraw_amount'"
                                    :value="get_static_option('maximum_withdraw_amount') ?? ''"
                                />
                                @can('withdraw-settings-update')
                                <x-btn.submit :title="__('Update')" :class="'btn-profile btn-bg-1 mt-4 pr-4 pl-4 update_info'" />
                                @endcan
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
