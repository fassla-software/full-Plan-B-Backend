@extends('backend.layout.master')

@section('title', __('Third Party Scripts'))

@section('style')

@endsection

@section('content')
    <div class="dashboard__body">
        <div class="row">
            <div class="col-lg-12">
                <div class="customMarkup__single">
                    <div class="customMarkup__single__item">
                        <h4 class="customMarkup__single__title">{{ __('Third Party Scripts') }}</h4>
                        <x-validation.error/>
                        <div class="customMarkup__single__inner mt-4">
                            <form action="{{route('admin.general.settings.third.party.script')}}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="single-input mb-3">
                                    <label for="site_third_party_tracking_code" class="label-title">{{__('Third Party Api Code')}}</label>
                                    <textarea name="site_third_party_tracking_code" id="site_third_party_tracking_code" cols="30" rows="5" class="form-control">{{get_static_option('site_third_party_tracking_code')}}</textarea>
                                    <p>{{__('this code will be load before </head> tag')}}</p>
                                </div>
                                <button type="submit" id="update" class="btn btn-primary mt-4 pr-4 pl-4">{{__('Update Changes')}}</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <x-media.markup/>
@endsection

@section('script')

@endsection
