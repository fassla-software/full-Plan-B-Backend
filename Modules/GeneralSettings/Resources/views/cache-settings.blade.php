@extends('backend.layout.master')
@section('title', __('Cache Settings'))
@section('style')
@endsection

@section('content')
    <div class="dashboard__body">
        <div class="row">
            <div class="col-lg-12">
                <div class="customMarkup__single">
                    <div class="customMarkup__single__item">
                        <h4 class="customMarkup__single__title">{{ __('Cache Settings') }}</h4>
                        <x-validation.error/>
                        <div class="customMarkup__single__inner mt-4">
                            <form action="{{route('admin.general.settings.cache')}}" method="POST" id="cache_settings_form" enctype="multipart/form-data">
                                @csrf
                                <div class="single-input mb-3">
                                    <input type="hidden" name="cache_type" id="cache_type" class="form-control">
                                    <button class="btn btn-primary mt-4 pr-4 pl-4 clear-cache-submit-btn" id="view" data-value="view">{{__('Clear View Cache')}}</button><br>
                                    <button class="btn btn-info mt-4 pr-4 pl-4 clear-cache-submit-btn" id="route" data-value="route">{{__('Clear Route Cache')}}</button><br>
                                    <button class="btn btn-dark mt-4 pr-4 pl-4 clear-cache-submit-btn" id="config" data-value="config">{{__('Clear Configure Cache')}}</button><br>
                                    <button class="btn btn-success mt-4 pr-4 pl-4 clear-cache-submit-btn" id="clear" data-value="cache">{{__('Clear Cache')}}</button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        (function($) {
            "use strict";
            $(document).ready(function (){
                $(document).on('click','.clear-cache-submit-btn',function(e){
                    e.preventDefault();
                    $(this).addClass("disabled")
                    $(this).html('<i class="fas fa-spinner fa-spin mr-1"></i> {{__("Cleaning Cache")}}');
                    $('#cache_type').val($(this).data('value'));
                    $('#cache_settings_form').trigger('submit');
                });
            });
        })(jQuery);
    </script>
@endsection
