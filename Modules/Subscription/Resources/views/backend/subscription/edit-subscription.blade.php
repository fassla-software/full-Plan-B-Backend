@extends('backend.layout.master')
@section('title', __('Edit Subscriptions'))
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
                            <h4 class="customMarkup__single__title">{{ __('Edit Subscriptions') }}</h4>
                        </div>
                        <div class="customMarkup__single__inner mt-4">
                            <x-validation.error/>
                            <form action="{{route('admin.subscription.edit',$subscription_details->id ?? '')}}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="single-input mb-3">
                                    <label class="label-title">{{ __('Subscription Type') }}</label>
                                    <select name="type" id="type" class="form-control">
                                        <option value="">{{ __('Select Type') }}</option>
                                        @foreach($all_types as $type)
                                            <option value="{{ $type->id }}" {{ $subscription_details->subscription_type_id == $type->id ? 'selected' : '' }}>{{ $type->type }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <x-form.text :title="__('Title')" :type="__('text')" :name="'title'" :id="'title'" :value="$subscription_details->title ?? ''" :placeholder="__('Enter title')"/>
                                <x-form.text :title="__('Price')" :type="__('number')" :name="'price'" :id="'price'" :value="$subscription_details->price ?? ''" :placeholder="__('Enter price')"/>
                                <x-form.text :title="__('Connect')" :type="__('number')" :name="'limit'" :id="'limit'" :divClass="'mb-0'" :value="$subscription_details->limit ?? ''" :placeholder="__('Enter limit')"/>
                                <div class="img-wrap">
                                    {!! render_image_markup_by_attachment_id($subscription_details->logo,'','thumb') ?? '' !!}
                                </div>
                                <input type="hidden" name="logo" value="{{$subscription_details->logo}}">
                                <button type="button" class="btn btn-info media_upload_form_btn"
                                        data-btntitle="{{__('Select Image')}}"
                                        data-modaltitle="{{__('Upload Image')}}" data-bs-toggle="modal"
                                        data-bs-target="#media_upload_modal">
                                    {{__('Upload Image')}}
                                </button>

                                <div class="single-input mt-3">
                                    <div id="features">
                                        @foreach($subscription_details->features as $feature)
                                            <div class="attr single-input-feature-attr">
                                                <input name="feature[]" class="feature form-control" type="text" value="{{ $feature->feature }}" placeholder="{{ __('Enter feature') }}">
                                                <div class="checkbox-inline">
                                                    <input name="status[]" type="checkbox" class="single-input-feature-checkbox check-input" {{ $feature->status == 'on' ? 'checked' : '' }}>
                                                </div>
                                                <button class="btn btn-danger btn-sm remove_row" type="button"><i class="fas fa-times"></i></button>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <button class="btn-profile btn-bg-1 add_new_row_for_edit mt-3" type="button"><i class="fas fa-plus"></i>{{ __('Add Features') }}</button>
                                <br>
                                <x-btn.submit :title="__('Update')" :class="'btn-profile btn-bg-1 mt-4 pr-4 pl-4 validate_subscription_type'" />
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
