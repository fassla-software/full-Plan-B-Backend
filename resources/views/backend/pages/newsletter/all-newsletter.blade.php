@extends('backend.layout.master')
@section('title', __('All Newsletter Subscriber'))
@section('style')
    <x-data-table.data-table-css />
    <x-summernote.summernote-css />
    <style>
        .w-90 {width: 90%;}

        .w-20 {width: 20%;}
    </style>
@endsection
@section('content')
    <div class="dashboard__body">
        <div class="row">
            <div class="col-lg-8">
                <div class="customMarkup__single">
                    <div class="customMarkup__single__item">
                        <div class="customMarkup__single__item__flex">
                            <h4 class="customMarkup__single__title">{{ __('All Newsletter Subscriber') }}</h4>
                        </div>
                        <div class="customMarkup__single__inner mt-4">
                            <div class="custom_table style-04 search_result">
                                @include('backend.pages.newsletter.search-result')
                            </div>
                            <!-- Table End -->
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="customMarkup__single">
                    <div class="customMarkup__single__item">
                        <div class="customMarkup__single__item__flex">
                            <h4 class="customMarkup__single__title">{{ __('Add New Subscriber') }}</h4>
                        </div>
                        <x-validation.error />
                        <div class="customMarkup__single__inner mt-4">
                            <div class="custom_table style-04">
                                <form action="{{ route('admin.newsletter.email.add') }}" method="post">
                                    @csrf
                                    <div class="form-group">
                                        <label for="email" class="label-title">{{__('Email')}}</label>
                                        <input type="text" class="form-control"  id="email" name="email" placeholder="{{__('Email')}}">
                                    </div>
                                    <div class="form-group mt-5">
                                        <button id="submit" type="submit" class="btn-profile btn-bg-1">{{__('Submit')}}</button>
                                    </div>
                                </form>
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
                 <form class="" method="POST" action="{{ route('admin.newsletter.email.send') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" value="" name="email" id="send_email" />
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">{{ __("Send Email") }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @csrf
                        <div class="single-input mb-3">
                            <label class="label-title"> {{ __('Subject') }}</label>
                            <input type="text" class="form-control" name="subject" id="subject">
                        </div>
                        <div class="single-input mb-3">
                            <label class="label-title"> {{ __('Message') }}</label>
                            <textarea class="form-control summernote" name="message" id="message" rows="5"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __("Close") }}</button>
                        <button type="submit" class="btn-profile btn-bg-1 send_email_to_subscriber">{{ __("Send") }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <x-sweet-alert.sweet-alert2-js />
    <x-summernote.summernote-js />
    <script>
       $(document).on('click','.send_email_to_userr',function(){
           let newsletter_id = $(this).data('id');
           let newsletter_email = $(this).data('email');

           $('#send_email').val(newsletter_email);
       })

       $(document).on('click','.send_email_to_subscriber',function(){
           let subject = $('#subject').val();
           let message = $('#message').val();

           if(subject == '' || message == ''){
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
               url:"{{ route('admin.newsletter.email.paginate.data').'?page='}}" + page,
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
