@extends('backend.layout.master')
@section('title', __('Withdraw Requests'))
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
            <div class="col-lg-12">
                <div class="customMarkup__single">
                    <div class="customMarkup__single__item">
                        <div class="customMarkup__single__item__flex">
                            <h4 class="customMarkup__single__title">{{ __('Withdraw Requests') }}</h4>
                        </div>
                        <x-validation.error />
                        <div class="customMarkup__single__inner mt-4">
                            <div class="custom_table style-04 search_result">
                                @include('wallet::admin.withdraw.search-result')
                            </div>
                            <!-- Table End -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="edit-request-modal" tabindex="-1" aria-labelledby="edit-gateway-modalLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form class="" method="POST" action="{{ route('admin.wallet.withdraw.request.update') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" value="" name="request_id" />
                    <input type="hidden" value="" name="request_status" />
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">{{ __("Update Request Status") }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @csrf
                        <div class="single-input mb-3">
                            <label class="label-title"> {{ __('Select Status') }}</label>
                            <select name="status" id="status" class="form-control">
                                <option value="">{{ __('Select') }}</option>
                                <option value="1">{{ __('Pending') }}</option>
                                <option value="4">{{ __('Processing') }}</option>
                                <option value="2">{{ __('Complete') }}</option>
                                <option value="3">{{ __('Cancel') }}</option>
                            </select>
                        </div>
                        <div class="single-input mb-3">
                            <label class="label-title"> {{ __('Choose File') }}</label>
                            <input type="file" class="form-control" name="image">
                        </div>
                        <div class="single-input mb-3">
                            <label class="label-title"> {{ __('Note') }}</label>
                            <textarea class="form-control" name="note" rows="5"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __("Close") }}</button>
                        <button type="submit" class="btn-profile btn-bg-1 update_request_status">{{ __("Update") }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <x-sweet-alert.sweet-alert2-js />
    <script>
       $(document).on('click','.update-request',function(){
           let request_id = $(this).data('id');
           let request_status = $(this).data('status');

           $("input[name='request_id']").val(request_id);
           $("input[name='request_status']").val(request_status);
       })

       $(document).on('click','.update_request_status',function(){
           let status = $('#status').val();
           if(status == ''){
               toastr_warning_js("{{ __('Please select status') }}")
               return false;
           }
       })

       //pagination
       $(document).on('click', '.pagination a', function(e){
           e.preventDefault();
           let page = $(this).attr('href').split('page=')[1];
           histories(page);
       });
       function histories(page){
           $.ajax({
               url:"{{ route('admin.wallet.withdraw.paginate.data').'?page='}}" + page,
               success:function(res){
                   $('.search_result').html(res);
               }
           });
       }

       // toastr warning
       function toastr_warning_js(msg){
           Command: toastr["warning"](msg, "Warning !")
           toastr.options = {
               "closeButton": true,
               "debug": false,
               "newestOnTop": false,
               "progressBar": true,
               "positionClass": "toast-top-right",
               "preventDuplicates": false,
               "onclick": null,
               "showDuration": "300",
               "hideDuration": "1000",
               "timeOut": "5000",
               "extendedTimeOut": "1000",
               "showEasing": "swing",
               "hideEasing": "linear",
               "showMethod": "fadeIn",
               "hideMethod": "fadeOut"
           }
       }
    </script>


@endsection
