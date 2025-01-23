@extends('frontend.layout.master')
@section('site_title', __('Time Tracker'))
@section('style')
    <style>
        .warning-wrapper {
            max-width: 630px;
            margin-inline: auto;
        }
    </style>
@endsection
@section('content')
    <main>
        <x-frontend.category.category />
        <x-breadcrumb.user-profile-breadcrumb :title="__('Time Tracker')" :innerTitle="__('Time Tracker')" />
        <!-- Profile Details area Starts -->
        <div class="profile-area pat-100 pab-100 section-bg-2">
            <div class="container">
                <div class="warning-wrapper">
                    <x-notice.general-notice :description="__('Please stop the time tracker before submitting your task.')" />
                    <x-validation.error />
                </div>
                <div class="timeTracker-wrapper">
                    <div class="timeTracker-item">
                        <div class="timeTracker-box">
                            <h2 id="time" class="timeTracker-box-title text-center" title="DD:HH:MM:SS">00:00:00</h2>
                            <div class="timeTracker-box-flex mt-4">
                                <div class="timeTracker-box-single">
                                    <span id="start" class="timeTracker-box-play"></span>
                                    <span id="pause" class="timeTracker-box-pause"></span>
                                </div>
                            </div>
                        </div>

{{--                        <button id="capture">Capture Screen</button>--}}
                        <canvas id="canvas" style="display: none;"></canvas>
                        <input type="hidden" name="order_id_for_screenshot" value="{{ $order->id }}">


                        <form action="{{ route('freelancer.order.time.tracker',$order->id) }}" method="post">
                            @csrf
                            <input type="hidden" name="notes" id="notes">
                            <input type="hidden" name="start_time" id="start_time">
                            <input type="hidden" name="work_hour" id="work_hour">

                            <div class="timeTracker-item-contents center-text">
                                <h4 class="timeTracker-item-contents-title">{{ $order?->job->title }}</h4>
                                <div class="timeTracker-item-contents-flex mt-3">
                                    <span class="timeTracker-item-contents-para">{{ __('Estimated Hour:') }} <strong>{{ $order?->job->estimated_hours }} {{ __('hrs') }} </strong></span>
                                    <span class="timeTracker-item-contents-para">{{ __('Client:') }} <strong>{{ $order?->job?->job_creator->fullname }}</strong></span>
                                </div>
                                <div class="btn-wrapper flex-btn mt-4">
                                    <a href="javascript:void(0)"
                                       class="btn-profile btn-outline-gray add_manual_time"
                                       data-bs-target="#timeModal"
                                       data-bs-toggle="modal"
                                    >{{ __('Restore Work Hour') }}</a>
                                    <a href="javascript:void(0)"
                                       class="btn-profile btn-outline-gray"
                                       data-bs-target="#noteModal"
                                       data-bs-toggle="modal"
                                    >{{ __('Add Notes') }}</a>
                                    <button type="submit" class="btn-profile btn-bg-1">{{ __('Submit Work') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- Profile Details area end -->

        @include('frontend.user.freelancer.order.time-tracker.note-modal')
        @include('frontend.user.freelancer.order.time-tracker.time-modal')

    </main>
@endsection

@section('script')
    <x-sweet-alert.sweet-alert2-js />
    @include('frontend.user.freelancer.order.time-tracker.time-tracker-js')
@endsection
