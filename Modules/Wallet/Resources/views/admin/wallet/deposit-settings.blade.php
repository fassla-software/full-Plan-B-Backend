@extends('backend.layout.master')
@section('title', __('Deposit Amount Settings'))
@section('content')
    <div class="dashboard__body">
        <div class="row">
            <div class="col-lg-6">
                <x-notice.general-notice :description="__('Notice: Deposit amount settings refer to the maximum amount a user can deposit into their wallet at one time. For instance, setting it to 500 means a user can deposit up to a maximum of 500 in each transaction.')" />
                <div class="customMarkup__single">
                    <div class="customMarkup__single__item">
                        <div class="customMarkup__single__item__flex">
                            <h4 class="customMarkup__single__title">{{ __('Deposit Amount Settings') }}</h4>
                        </div>
                        <x-validation.error />
                        <div class="customMarkup__single__inner mt-4">
                            <form action="{{route('admin.wallet.deposit.settings')}}" method="POST">
                                @csrf
                                <x-form.text
                                    :title="__('Deposit Limit')"
                                    :type="__('text')"
                                    :name="'deposit_amount_limitation_for_user'"
                                    :id="'deposit_amount_limitation_for_user'"
                                    :value="get_static_option('deposit_amount_limitation_for_user') ?? ''"
                                />
                                @can('deposit-settings-update')
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
