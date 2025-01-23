@extends('backend.layout.master')
@section('title', __('Withdraw Gateway Settings'))
@section('style')
    <x-data-table.data-table-css />
    <style>
        .w-90 {width: 90%;}
        .w-20 {width: 20%;}
    </style>
@endsection
@section('content')
    <div class="dashboard__body">
        <div class="row">
            <div class="col-lg-7">
                <x-notice.general-notice
                        :description="__('Notice: Users can select any of the below-listed payment methods for withdrawal, those are active.')"
                />
                <div class="customMarkup__single">
                    <div class="customMarkup__single__item">
                        <div class="customMarkup__single__item__flex">
                            <h4 class="customMarkup__single__title">{{ __('Withdraw Payment Gateways') }}</h4>
                        </div>
                        <x-validation.error />
                        <div class="customMarkup__single__inner mt-4">
                            <div class="custom_table style-04 search_result">
                                <table class="DataTable_activation">
                                    <thead>
                                        <tr>
                                            <th>{{ __('SL NO') }}</th>
                                            <th>{{ __('Gateway Name') }}</th>
                                            <th>{{ __('Gateway Filed') }}</th>
                                            <th>{{ __('Gateway Status') }}</th>
                                            <th>{{ __('Action') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($gateways as $gateway)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ ucfirst($gateway->name) }}</td>
                                                <td>{{ implode(' , ', unserialize($gateway->field)) }}</td>
                                                <td>
                                                    <x-status.table.gateway-status :status="$gateway->status" />
                                                </td>
                                                <td>
                                                    <x-status.table.select-action :title="__('Select Action')"/>
                                                    <ul class="dropdown-menu status_dropdown__list">
                                                        @can('withdraw-payment-gateway-edit')
                                                        <li class="status_dropdown__item">
                                                            <a class="btn dropdown-item status_dropdown__list__link edit_gateway_modal update-gateway"
                                                               data-bs-toggle="modal"
                                                               data-bs-target="#edit-gateway-modal"
                                                               data-name="{{ ucfirst($gateway->name) }}"
                                                               data-id="{{ $gateway->id }}"
                                                               data-blog-filed="{{ json_encode(unserialize($gateway->field)) }}">
                                                                {{ __('Edit Gateway') }}
                                                            </a>
                                                        </li>
                                                        @endcan
                                                        @can('withdraw-payment-gateway-delete')
                                                        <li class="status_dropdown__item"><x-popup.delete-popup :title="__('Delete Gateway')" :url="route('admin.wallet.withdraw.gateway.delete',$gateway->id)"/></li>
                                                        @endcan
                                                        @can('withdraw-payment-status-change')
                                                        <li class="status_dropdown__item"><x-status.table.status-change :title="__('Change Status')" :url="route('admin.wallet.withdraw.gateway.status',$gateway->id)"/></li>
                                                       @endcan
                                                    </ul>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <!-- Table End -->
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="dashboard__card card__two">
                        <div class="dashboard__card__header">
                            <h4 class="dashboard__card__title mb-5">{{ __('Withdraw Payment Gateway Create') }}</h4>
                        </div>
                        <div class="dashboard__card__body custom__form customMarkup__single">
                            <x-validation.error />
                            <form method="POST" action="{{ route('admin.wallet.withdraw.gateway.create') }}">
                                @csrf
                                <div class="single-input mb-3">
                                    <label class="label-title"> {{ __('Payment Gateway Name') }}</label>
                                    <input class="form-control" name="name" placeholder="{{ __("Write gateway name...") }}">
                                </div>

                                <div class="dashboard__card card__two">
                                    <div class="dashboard__card__header">
                                        <h4 class="dashboard__card__title">{{ __('Gateway required filed.') }}</h4>
                                    </div>
                                    <div class="dashboard__card__body">
                                        <div class="form-group row">
                                            <div class="w-90 d-flex align-items-center">
                                                <input class="form-control" name="field[]" placeholder="{{ __('Write filed name...') }}">
                                            </div>
                                            <div
                                                class="col-md-1 d-flex flex-column align-items-center justify-content-center pb-2 gap-2">
                                                <button type="button" class="btn btn-info btn-sm gateway-filed-add">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                                <button type="button" class="btn btn-danger btn-sm gateway-filed-remove">
                                                    <i class="fas fa-minus"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                @can('withdraw-payment-gateway-add')
                                <div class="form-group mt-3">
                                    <button class="btn-profile btn-bg-1">{{ __('Create Gateway') }}</button>
                                </div>
                                @endcan
                            </form>
                        </div>
                    </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="edit-gateway-modal" tabindex="-1" aria-labelledby="edit-gateway-modalLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form class="" method="POST" action="{{ route('admin.wallet.withdraw.gateway.update') }}">
                    @csrf
                    <input type="hidden" value="" name="id" />
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">{{ __("Update wallet withdraw gateway") }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @csrf
                        <div class="single-input mb-3">
                            <label class="label-title"> {{ __('Payment Gateway Name') }}</label>
                            <input class="form-control" name="name" placeholder="{{ __("Write gateway name...") }}">
                        </div>
                        <div class="card">
                            <div class="card-header">
                                <h4 class="title">{{ __('Gateway required filed.') }}</h4>
                            </div>
                            <div class="card-body gateway-filed-body">

                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __("Close") }}</button>
                        <button type="submit" class="btn-profile btn-bg-1">{{ __("Update") }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <x-sweet-alert.sweet-alert2-js />
    <script>
        $(document).on("click", ".update-gateway", function() {
            let fileds = JSON.parse($(this).attr("data-blog-filed"));
            $("#edit-gateway-modal input[name='name']").val($(this).attr("data-name"))
            $("#edit-gateway-modal input[name='id']").val($(this).attr("data-id"))

            if (fileds.length > 0) {
                let list_filed = "";

                fileds.forEach(function(value, index, array) {
                    list_filed += `
                        <div class="form-group row">
                            <div class="w-90 d-flex align-items-center">
                                <input class="form-control" value="${value}" name="field[]" placeholder="Write filed name...">
                            </div>
                            <div class="col-md-1 d-flex flex-column align-items-center justify-content-center pb-2 gap-2">
                                <button type="button" class="btn btn-info btn-sm gateway-filed-add">
                                    <i class="fas fa-plus"></i>
                                </button>
                                <button type="button" class="btn btn-danger btn-sm gateway-filed-remove">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                    `;
                })

                $(".gateway-filed-body").html(list_filed);
            } else {
                $(".gateway-filed-body").html(`<div class="form-group row">
                    <div class="w-90 d-flex align-items-center">
                        <input class="form-control" name="field[]" placeholder="Write filed name...">
                    </div>
                    <div class="col-md-1 d-flex flex-column align-items-center justify-content-center pb-2 gap-2">
                        <button type="button" class="btn btn-info btn-sm gateway-filed-add">
                            <i class="fas fa-plus"></i>
                        </button>
                        <button type="button" class="btn btn-danger btn-sm gateway-filed-remove">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>`);
            }
        });

        $(document).on("click", ".gateway-filed-add", function() {
            let elem = $(this).parent().parent();
            elem.parent().append(elem.clone());
        });

        $(document).on("click", ".gateway-filed-remove", function() {
            if ($(".gateway-filed-remove").length == 1) {
                return null;
            }
            let elem = $(this).parent().parent().fadeOut(250, () => {
                $(this).parent().parent().remove();
            });
        });

    </script>
@endsection
