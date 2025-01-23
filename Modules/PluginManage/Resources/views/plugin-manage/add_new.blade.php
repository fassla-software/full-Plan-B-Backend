@extends('backend.layout.master')
@section('title')
    {{ __('Add New Plugin') }}
@endsection

@section('style')
    <style>
        .padding-30{
            padding: 30px;
        }
        .form-group.plugin-upload-field {
            margin-top: 60px;
        }

        .form-group.plugin-upload-field label {
            font-size: 16px;
            font-weight: 700;
            margin-bottom: 35px;
        }

        .form-group.plugin-upload-field small {
            font-size: 12px;
            margin-top: 11px;
        }

    </style>
@endsection
@section('content')
    <div class="dashboard__body">
        <div class="row">
            <div class="col-md-12">
                <div class="recent-order-wrapper dashboard-table bg-white padding-30">
                    <div class="header-wrap">
                        <h4 class="header-title mb-5">{{__("Add New Plugin")}}</h4>
                        <x-notice.general-notice :description="__('Notice: Upload new plugin from here. if you have a plugin already but you have uploaded that plugin file again, it will override existing plugins files')" />
                    </div>

                    <x-validation.error/>
                    <form action="{{route("admin.plugin.manage.new")}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group plugin-upload-field">
                            <label for="#">{{__("Upload Plugin File")}}</label>
                            <input type="file" name="plugin_file" accept=".zip">
                            <small class="d-block">{{__("only zip file accepted")}}</small>
                        </div>
                        <button type="submit" class="btn btn-primary me-2 mt-5">{{__("Submit")}}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        (function ($){
            "use strict";


        })(jQuery);
    </script>
@endsection
