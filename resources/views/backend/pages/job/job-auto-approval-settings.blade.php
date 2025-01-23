@extends('backend.layout.master')
@section('title', __('Job Auto Approval Settings'))
@section('style')
    <x-media.css/>
@endsection
@section('content')
    <div class="dashboard__body">
        <div class="row">
            <div class="col-lg-6">
                <div class="customMarkup__single">
                    <div class="customMarkup__single__item">
                        <div class="customMarkup__single__item__flex">
                            <h4 class="customMarkup__single__title">{{ __('Job Auto Approval Settings') }}</h4>
                        </div>
                        <x-validation.error />
                        <div class="customMarkup__single__inner mt-4">
                            <x-notice.general-notice
                                :class="'mt-5'"
                                :description="__('Notice: Job Auto Approval Settings refer to the automatic publication of a job as soon as a client posts it.')"
                                :description1="__('Notice: However, you have the option to disable the auto approval system. If you choose to do so, you will need to manually activate the job to make it visible to the public.')"
                                :description2="__('Notice: If you do not set any option by default job will publish automatically.')"
                            />
                            <form action="{{route('admin.job.approval.settings')}}" method="POST">
                                @csrf
                                <div class="single-input my-5">
                                    <label class="label-title">{{ __('Job Auto Approval') }}</label>
                                    <select name="job_auto_approval" class="form-control">
                                        <option value="" selected>{{ __('Select One') }}</option>
                                        <option value="yes" @if(get_static_option('job_auto_approval') == 'yes') selected @endif>{{ __('Yes') }}</option>
                                        <option value="no" @if(get_static_option('job_auto_approval') == 'no') selected @endif>{{ __('No') }}</option>
                                    </select>
                                </div>
                                @can('job-auto-approval')
                                <x-btn.submit :title="__('Update')" :class="'btn btn-primary mt-4 pr-4 pl-4'" />
                                @endcan
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <x-media.markup/>
@endsection
