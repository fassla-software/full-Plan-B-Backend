@extends('backend.layout.master')
@section('title', __('Check Update'))
@section('style')
    <x-media.css/>
@endsection
@section('content')
    <div class="dashboard__body">
        <div class="row">
            <div class="col-12">
                <div class="customMarkup__single">
                    <div class="customMarkup__single__item">
                        <div class="customMarkup__single__item__flex">
                            <h4 class="customMarkup__single__title">{{ __('Check Update') }}</h4>
                        </div>
                        <x-validation.error />
                        <button type="button" class="btn btn-primary mt-4 pr-4 pl-4" id="click_for_check_update">
                            <i class="las la-spinner la-spin d-none"></i>
                            {{__('Click to check For Update')}}
                        </button>
                        <div id="update_notice_wrapper" class="d-none text-start mt-2"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script>
        (function($){
            "use strict";
            $(document).ready(function() {
                //todo write code
                $("body").on("click","#update_download_and_run_update",function (e){
                    e.preventDefault();

                    var el = $(this);
                    el.children().removeClass('d-none');

                    if(el.attr("disabled") != undefined && el.attr("disabled") === "disabled"){
                        return;
                    }
                    el.attr("disabled",true);
                    $.ajax({
                        url: el.attr("data-action"),
                        type: "POST",
                        data: {
                            _token : "{{csrf_token()}}",
                            version: el.attr("data-version")
                        },
                        success: function (data){
                            el.children().addClass('d-none');
                            if(data.msg != undefined && data.msg != ""){
                                el.text(data.msg).removeClass("btn-warning").addClass("btn-"+data.type);
                            }
                        },
                        error: function (error) {
                            console.log(error)
                        }
                    });

                });


                $(document).on("click","#click_for_check_update",function (e){
                    e.preventDefault();
                    var el = $(this);
                    el.children().removeClass('d-none');
                    el.attr("disabled",true);
                    $.ajax({
                        url: "{{route('admin.version.check')}}",
                        type: "GET",
                        success: function (data){
                            el.children().addClass('d-none');
                            if(data.markup != ""){
                                $("#update_notice_wrapper").append(data.markup);
                            }else if(data.msg != ""){
                                $("#update_notice_wrapper").append("<div class='alert alert-"+data.type+"'>"+data.msg+"</div>");

                                if(data.type == 'danger'){
                                    toastr_warning_js("{{ __('Update failed, please contact support for further assistance') }}")
                                }

                                if(data.type == 'success'){
                                    toastr_success_js("{{ __('system upgrade success') }}")
                                }

                            }
                            $("#update_notice_wrapper").removeClass('d-none');
                            el.hide();
                        },
                        error: function (error) {
                            console.log(error)
                        }
                    });
                });

            });
        }(jQuery));
    </script>
@endsection
