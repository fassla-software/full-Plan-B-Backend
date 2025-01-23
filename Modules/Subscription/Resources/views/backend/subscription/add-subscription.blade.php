@extends('backend.layout.master')
@section('title', __('Add Subscription'))
@section('style')
    <style>
        .attr.single-input-feature-attr:not(:first-child) {
            margin-top: 15px;
        }
        .attr.single-input-feature-attr {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .single-input-feature-attr .checkbox-inline .check-input {
            height: 30px;
            width: 30px;
            margin-top: 0px;
            border-radius: 3px;
        }
        .single-input-feature-attr .checkbox-inline .check-input::after {
            font-size: 13px;
        }
    </style>
    <x-media.css />
@endsection
@section('content')
    <div class="dashboard__body">
        <div class="row">
            <div class="col-lg-6">
                <div class="customMarkup__single">
                    <div class="customMarkup__single__item">
                        <div class="customMarkup__single__item__flex">
                            <h4 class="customMarkup__single__title">{{ __('Add New Subscription') }}</h4>
                        </div>
                        <div class="customMarkup__single__inner mt-4">
                            <x-validation.error/>
                            <form action="{{route('admin.subscription.add')}}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="single-input mb-3">
                                    <label class="label-title">{{ __('Subscription Type') }}</label>
                                    <select name="type" id="type" class="form-control">
                                        <option value="">{{ __('Select Type') }}</option>
                                        @foreach($all_types as $type)
                                            <option value="{{ $type->id }}">{{ $type->type }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <x-form.text :title="__('Title')" :type="__('text')" :name="'title'" :id="'title'" :value="old('title', '')" :placeholder="__('Enter title')"/>
                                <x-form.text :title="__('Price')" :type="__('number')" :name="'price'" :id="'price'" :value="old('price', '')" :placeholder="__('Enter price')"/>
                                <x-form.text :title="__('Connect')" :type="__('number')" :name="'limit'" :id="'limit'" :divClass="'mb-0'" :value="old('limit', '')" :placeholder="__('Enter limit')"/>

                                <x-backend.image :title="__('')" :name="'logo'" :dimentions="'50x50'"/>
                                <div class="single-input">
                                    <div id="features">
                                        <div class="attr single-input-feature-attr">
                                            <input name="feature[]" class="feature form-control" type="text" placeholder="{{ __('Enter feature') }}">
                                            <div class="checkbox-inline">
                                                <input name="status[]" type="checkbox" class="required-entry single-input-feature-checkbox check-input">
                                            </div>
                                            <button class="btn btn-danger btn-sm remove" type="button"><i class="fas fa-times"></i></button>
                                        </div>
                                    </div>
                                    <button class="btn-profile btn-bg-1 add mt-3" type="button"><i class="fas fa-plus"></i>{{ __('Add Features') }}</button>
                                </div>
                                <br>
                                <x-btn.submit :title="__('Save')" :class="'btn-profile btn-bg-1 mt-4 pr-4 pl-4 validate_subscription_type'" />
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <x-media.markup />
@endsection

@section('script')
    <x-media.js />
    @include('subscription::backend.subscription.subscription-js')
@endsection
