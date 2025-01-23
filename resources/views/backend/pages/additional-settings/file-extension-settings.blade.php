@extends('backend.layout.master')
@section('title', __('Extension Select'))
@section("style")
    <x-select2.select2-css/>
    <style>
        span.select2-search.select2-search--inline {
            display: none;
        }
    </style>
@endsection


@section('content')
    <div class="dashboard__body">
        <div class="row">
            <div class="col-lg-12">
                <div class="customMarkup__single">
                    <div class="customMarkup__single__item">
                        <h4 class="customMarkup__single__title">{{ __('Extension Select') }}</h4>
                        <x-validation.error />
                        <div class="customMarkup__single__inner mt-4">
                            <form action="{{route("admin.file.extension.settings")}}" method="post">
                                @csrf
                                <div class="checkbox-wrapper">
                                    <div class="permission-group-wrapper">
                                        <div class="row g-4">
                                            <?php
                                                $extensions = ['png','jpg','jpeg','gif','pdf','doc','docx','txt','csv','xlsx','xls','ppt','pptx','zip','rar','7z'];
                                                $file_sizes = ['1024','2048','3072','4096','5120','10240','20480','30720','40960','51200','102400','204800','307200','409600','512000','1048576','2097152'];
                                            ?>
                                            <div class="single-input">
                                                <label class="label-title">{{ __('Select Extension') }}</label>
                                                <select name="file_extensions[]" id="file_extensions" class="form-control extension_select2" multiple>
                                                    <option value="">{{ __('Select Extension') }}</option>
                                                    @foreach($extensions as $extension)
                                                        <option value="{{ $extension }}" {{ in_array($extension,$file_extensions) ? 'selected' : '' }}>{{ $extension}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="single-input">
                                                <label class="label-title">{{ __('Max Upload File Size') }}</label>
                                                <select name="max_upload_size" id="max_upload_size" class="form-control">
                                                    <option value="">{{ __('Select Extension') }}</option>
                                                    @foreach($file_sizes as $size)
                                                        <option value="{{ $size }}" {{ get_static_option('max_upload_size') === $size ? 'selected' : '' }}>
                                                            {{ ($size / 1024) <= 1023 ? ($size / 1024) . ' MB' : ($size / (1024 * 1024)) . ' GB' }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="btn-wrapper mt-4">
                                    <button type="submit" class="btn btn-primary">{{ __("Submit Now") }}</button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section("script")
    <x-select2.select2-js/>
    <script>
        $('.extension_select2').select2();
    </script>
@endsection