@extends('backend.layout.master')
@section('title', __('All Reports'))
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
                            <h4 class="customMarkup__single__title">{{ __('All Reports') }}</h4>
                        </div>
                        <x-validation.error />
                        <div class="customMarkup__single__inner mt-4">
                            <div class="custom_table style-04 search_result">
                                @include('backend.pages.reports.search-result')
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
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="row">
                  <div class="col-md-6">
                      <div class="modal-header">
                          <h5 class="modal-title">{{ __('Report Title:') }} <span id="report_title"></span></h5>
                      </div>
                      <div class="modal-body">
                          <h5 class="modal-title">{{ __('Report Description:') }}</h5>
                          <div id="report_description"></div>
                      </div>

                  </div>
                    <div class="col-md-6">
                        <form class="" method="POST" action="{{ route('admin.user.report.update') }}" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" value="" name="report_id" />
                            <input type="hidden" value="" name="report_status" />
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">{{ __("Update Report Status") }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                @csrf
                                <div class="single-input mb-3">
                                    <label class="label-title"> {{ __('Select Status') }}</label>
                                    <select name="status" id="status" class="form-control">
                                        <option value="">{{ __('Select') }}</option>
                                        <option value="0">{{ __('In Review') }}</option>
                                        <option value="1">{{ __('Closed') }}</option>
                                        <option value="2">{{ __('Rejected') }}</option>
                                    </select>
                                </div>
                                <div class="single-input mb-3">
                                    <label class="label-title"> {{ __('Note') }}</label>
                                    <textarea class="form-control" name="note" id="note" rows="5"></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __("Close") }}</button>
                                <button type="submit" class="btn-profile btn-bg-1 update_report_status">{{ __("Update") }}</button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>

@endsection

@section('script')
    <x-sweet-alert.sweet-alert2-js />
    <script>
       $(document).on('click','.update-report',function(){
           let report_id = $(this).data('id');
           let report_status = $(this).data('status');
           let note = $(this).data('note');
           let report_title = $(this).data('title');

           let report_description = $(this).data('description');
           let textOnly = $('<div>').html(report_description).text();

           $("input[name='report_id']").val(report_id);
           $('#status').val(report_status);
           $('#note').val(note);
           $('#report_title').text(report_title);
           $('#report_description').text(textOnly);
       })

       $(document).on('click','.update_report_status',function(){
           let status = $('#status').val();
           let note = $('#note').val();

           if(status == '' || note == ''){
               toastr_warning_js("{{ __('Please fill both fields') }}")
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
               url:"{{ route('admin.user.report.paginate.data').'?page='}}" + page,
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
