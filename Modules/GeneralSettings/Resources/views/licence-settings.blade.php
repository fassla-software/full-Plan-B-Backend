@extends('backend.layout.master')
@section('title', __('Licence Settings'))
@section('style')
@endsection

@section('content')
    <div class="dashboard__body">
        <div class="row">
            <div class="col-lg-12">
                <div class="customMarkup__single">
                    <div class="customMarkup__single__item">
                        <h4 class="customMarkup__single__title">{{ __('Licence Settings') }}</h4>
                        <x-validation.error/>
                        <div class="customMarkup__single__inner mt-4">
                            <form action="{{route('admin.general.settings.licence')}}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="single-input mb-3">
                                    <label for="site_gdpr_cookie_title" class="label-title">{{__('Purchase Key')}}</label>
                                    <input type="text" name="item_purchase_key"  class="form-control" value="{{get_static_option('item_purchase_key')}}" id="item_purchase_key">
                                </div>
                                <button type="submit" id="update" class="btn btn-primary mt-4 pr-4 pl-4">{{__('Update Changes')}}</button>
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
            });
        })(jQuery);
    </script>
@endsection
