@extends('backend.layout.master')
@section('title', __('Database Upgrade'))
@section('style')
@endsection

@section('content')
    <div class="dashboard__body">
        <div class="row">
            <div class="col-lg-12">
                <div class="customMarkup__single">
                    <div class="customMarkup__single__item">
                        <h4 class="customMarkup__single__title">{{ __('Database Upgrade') }}</h4>
                        <x-validation.error/>
                        <div class="customMarkup__single__inner mt-4">
                            <form action="{{route('admin.general.settings.database.upgrade')}}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="single-input mb-3">
                                    <button class="btn btn-primary mt-4 clear-cache-submit-btn" data-value="cache">{{__('Database Upgrade')}}</button>
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
                    $(this).html('<i class="fas fa-spinner fa-spin"></i> {{__("Proccesing")}}')
                });
            });
        })(jQuery);
    </script>
@endsection
