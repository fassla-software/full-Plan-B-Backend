@extends('frontend.layout.master')
@section('site_title',__('Job Details'))
@section('style')
    <x-summernote.summernote-css/>
    <x-select2.select2-css/>
    <style>
        .disabled-link {
            background-color: #ccc !important;
            pointer-events: none;
            cursor: default;
        }
    </style>
@endsection
@section('content')
    <main>
        <x-breadcrumb.user-profile-breadcrumb :title="__('Client Job Details')" :innerTitle="__('Client Job Details')"/>
        <!-- Profile Details area Starts -->
        <div class="profile-area pat-100 pab-100 section-bg-2">
            <div class="container">
                <div class="row gy-4 justify-content-center">
                    <div class="col-xl-8 col-lg-9">
                         <div class="profile-wrapper">
                            <div class="myJob-wrapper">
                                <div class="myJob-wrapper-single">
                                    <div class="myJob-wrapper-single-flex flex-between align-items-center">
                                        <div class="myJob-wrapper-single-contents">
                                            <div class="flex-btn">
                                                <span class="myJob-wrapper-single-id job_open_close_location">#000{{ $job_details->id }}</span>
                                                <div class="flex-btn">
                                                    <span class="myJob-wrapper-single-fixed">{{ ucfirst($job_details->type) }}</span>
                                                    @if($job_details->on_off == 0)
                                                        <span class="myJob-wrapper-single-fixed closed">{{ __('Closed') }}</span>
                                                    @else
                                                        <span class="myJob-wrapper-single-fixed active">{{ __('Open') }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                            <h4 class="myJob-wrapper-single-title mt-3"><a href="javascript:void(0)">{{ $job_details->title }}</a></h4>
                                            <div class="myJob-wrapper-single-list mt-3">
                                                @if($job_details->current_status == 1)
                                                    <span class="job-progress">{{ __('Job in Progress') }}</span>
                                                @endif
                                                @if($job_details->current_status == 2)
                                                    <span class="job-progress">{{ __('Complete') }}</span>
                                                @endif
                                                @if($job_details->on_off == 1)
                                                    <span class="job_publicPrivate_view">{{ __('Public') }}</span>
                                                @else
                                                    <span class="job_publicPrivate_view">{{ __('Only Me') }}</span>
                                                @endif
                                                <span class="myJob-wrapper-single-list-para">{{ $job_details->created_at->toFormattedDateString() ?? '' }} - <a href="javascript:void(0)">{{ ucfirst($job_details->level ?? '') }} </a></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="profile-border-top">
                                        <ul class="jobFilter-wrapper-item-bottom-list">
                                            <li class="jobFilter-wrapper-item-bottom-list-item">
                                                <span class="item-icon">
                                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <mask id="mask0_6628_15470" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="0" y="0" width="24" height="24">
                                                        <rect width="24" height="24" fill="#D9D9D9"/>
                                                        </mask>
                                                        <g mask="url(#mask0_6628_15470)">
                                                        <path d="M17 21.75H7C6.27227 21.7448 5.57584 21.4534 5.06124 20.9388C4.54665 20.4242 4.25524 19.7277 4.25 19V7C4.25524 6.27227 4.54665 5.57584 5.06124 5.06124C5.57584 4.54665 6.27227 4.25524 7 4.25H8.35C8.47868 3.79814 8.72347 3.38786 9.06 3.06C9.31379 2.80347 9.61591 2.5998 9.9489 2.46077C10.2819 2.32174 10.6391 2.2501 11 2.25H13C13.3609 2.2501 13.7181 2.32174 14.0511 2.46077C14.3841 2.5998 14.6862 2.80347 14.94 3.06C15.2765 3.38786 15.5213 3.79814 15.65 4.25H17C17.7277 4.25524 18.4242 4.54665 18.9388 5.06124C19.4534 5.57584 19.7448 6.27227 19.75 7V19C19.7448 19.7277 19.4534 20.4242 18.9388 20.9388C18.4242 21.4534 17.7277 21.7448 17 21.75ZM7 5.75C6.66929 5.75261 6.35286 5.88515 6.11901 6.11901C5.88515 6.35286 5.75261 6.66929 5.75 7V19C5.75261 19.3307 5.88515 19.6471 6.11901 19.881C6.35286 20.1149 6.66929 20.2474 7 20.25H17C17.3307 20.2474 17.6471 20.1149 17.881 19.881C18.1149 19.6471 18.2474 19.3307 18.25 19V7C18.2474 6.66929 18.1149 6.35286 17.881 6.11901C17.6471 5.88515 17.3307 5.75261 17 5.75H15.65C15.5213 6.20186 15.2765 6.61214 14.94 6.94C14.6862 7.19653 14.3841 7.4002 14.0511 7.53923C13.7181 7.67826 13.3609 7.7499 13 7.75H11C10.6391 7.7499 10.2819 7.67826 9.9489 7.53923C9.61591 7.4002 9.31379 7.19653 9.06 6.94C8.72347 6.61214 8.47868 6.20186 8.35 5.75H7ZM9.75 5C9.75261 5.33071 9.88515 5.64714 10.119 5.88099C10.3529 6.11485 10.6693 6.24739 11 6.25H13C13.2483 6.25334 13.4918 6.18208 13.6991 6.04544C13.9064 5.9088 14.0679 5.71307 14.1627 5.48359C14.2575 5.25412 14.2813 5.00147 14.2309 4.75836C14.1805 4.51524 14.0582 4.29286 13.88 4.12C13.7657 4.00234 13.6289 3.90893 13.4777 3.84535C13.3265 3.78178 13.164 3.74935 13 3.75H11C10.6693 3.75261 10.3529 3.88515 10.119 4.11901C9.88515 4.35286 9.75261 4.66929 9.75 5ZM12 18.75C11.8019 18.7474 11.6126 18.6676 11.4725 18.5275C11.3324 18.3874 11.2526 18.1981 11.25 18V17.75H10C9.80109 17.75 9.61032 17.671 9.46967 17.5303C9.32902 17.3897 9.25 17.1989 9.25 17C9.25 16.8011 9.32902 16.6103 9.46967 16.4697C9.61032 16.329 9.80109 16.25 10 16.25H12.5C12.6989 16.25 12.8897 16.171 13.0303 16.0303C13.171 15.8897 13.25 15.6989 13.25 15.5C13.25 15.3011 13.171 15.1103 13.0303 14.9697C12.8897 14.829 12.6989 14.75 12.5 14.75H11.5C10.9177 14.761 10.3538 14.5457 9.92696 14.1495C9.5001 13.7533 9.24349 13.207 9.21112 12.6255C9.17874 12.044 9.37311 11.4726 9.75335 11.0314C10.1336 10.5903 10.6701 10.3137 11.25 10.26V10C11.25 9.80109 11.329 9.61032 11.4697 9.46967C11.6103 9.32902 11.8011 9.25 12 9.25C12.1989 9.25 12.3897 9.32902 12.5303 9.46967C12.671 9.61032 12.75 9.80109 12.75 10V10.25H14C14.1989 10.25 14.3897 10.329 14.5303 10.4697C14.671 10.6103 14.75 10.8011 14.75 11C14.75 11.1989 14.671 11.3897 14.5303 11.5303C14.3897 11.671 14.1989 11.75 14 11.75H11.5C11.3011 11.75 11.1103 11.829 10.9697 11.9697C10.829 12.1103 10.75 12.3011 10.75 12.5C10.75 12.6989 10.829 12.8897 10.9697 13.0303C11.1103 13.171 11.3011 13.25 11.5 13.25H12.5C12.7957 13.248 13.0888 13.3054 13.3619 13.4188C13.635 13.5322 13.8826 13.6992 14.09 13.91C14.5114 14.3319 14.748 14.9037 14.748 15.5C14.748 16.0963 14.5114 16.6681 14.09 17.09C13.732 17.4552 13.2584 17.6849 12.75 17.74V18C12.7474 18.1981 12.6676 18.3874 12.5275 18.5275C12.3874 18.6676 12.1981 18.7474 12 18.75Z" fill="#6176F6"/>
                                                        </g>
                                                    </svg>
                                                </span>
                                                @if($job_details->type == 'hourly')
                                                    <span class="item-para">{{ __("Hourly Rate") }}: <strong>{{ float_amount_with_currency_symbol($job_details->hourly_rate) }}</strong></span>
                                                @else
                                                    <span class="item-para">{{ __("Budget") }}: <strong>{{ float_amount_with_currency_symbol($job_details->budget) }}</strong></span>
                                                @endif
                                            </li>
                                            <li class="jobFilter-wrapper-item-bottom-list-item">
                                                <span class="item-icon">
                                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M12.12 13.5305C12.1 13.5305 12.07 13.5305 12.05 13.5305C12.02 13.5305 11.98 13.5305 11.95 13.5305C9.67998 13.4605 7.97998 11.6905 7.97998 9.51047C7.97998 7.29047 9.78998 5.48047 12.01 5.48047C14.23 5.48047 16.04 7.29047 16.04 9.51047C16.03 11.7005 14.32 13.4605 12.15 13.5305C12.13 13.5305 12.13 13.5305 12.12 13.5305ZM12 6.97047C10.6 6.97047 9.46998 8.11047 9.46998 9.50047C9.46998 10.8705 10.54 11.9805 11.9 12.0305C11.93 12.0205 12.03 12.0205 12.13 12.0305C13.47 11.9605 14.52 10.8605 14.53 9.50047C14.53 8.11047 13.4 6.97047 12 6.97047Z" fill="#667085"></path>
                                                        <path d="M12.0001 22.7503C9.31008 22.7503 6.74008 21.7503 4.75008 19.9303C4.57008 19.7703 4.49008 19.5303 4.51008 19.3003C4.64008 18.1103 5.38008 17.0003 6.61008 16.1803C9.59008 14.2003 14.4201 14.2003 17.3901 16.1803C18.6201 17.0103 19.3601 18.1103 19.4901 19.3003C19.5201 19.5403 19.4301 19.7703 19.2501 19.9303C17.2601 21.7503 14.6901 22.7503 12.0001 22.7503ZM6.08008 19.1003C7.74008 20.4903 9.83008 21.2503 12.0001 21.2503C14.1701 21.2503 16.2601 20.4903 17.9201 19.1003C17.7401 18.4903 17.2601 17.9003 16.5501 17.4203C14.0901 15.7803 9.92008 15.7803 7.44008 17.4203C6.73008 17.9003 6.26008 18.4903 6.08008 19.1003Z" fill="#667085"></path>
                                                        <path d="M12 22.75C6.07 22.75 1.25 17.93 1.25 12C1.25 6.07 6.07 1.25 12 1.25C17.93 1.25 22.75 6.07 22.75 12C22.75 17.93 17.93 22.75 12 22.75ZM12 2.75C6.9 2.75 2.75 6.9 2.75 12C2.75 17.1 6.9 21.25 12 21.25C17.1 21.25 21.25 17.1 21.25 12C21.25 6.9 17.1 2.75 12 2.75Z" fill="#667085"></path>
                                                    </svg>
                                                </span>
                                                <span class="item-para">{{ __('Hired persons') }} <strong>{{ $hired_freelancer_count }}</strong></span>
                                            </li>
                                            <li class="jobFilter-wrapper-item-bottom-list-item">
                                                <span class="item-icon">
                                                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                                         xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                                d="M14.6836 8.0166H10.3086C9.96693 8.0166 9.68359 7.73327 9.68359 7.3916C9.68359 7.04993 9.96693 6.7666 10.3086 6.7666H14.6836C15.0253 6.7666 15.3086 7.04993 15.3086 7.3916C15.3086 7.73327 15.0336 8.0166 14.6836 8.0166Z"
                                                                fill="#475467" />
                                                        <path
                                                                d="M5.93151 8.65002C5.77318 8.65002 5.61484 8.59168 5.48984 8.46668L4.86484 7.84168C4.62318 7.60002 4.62318 7.20002 4.86484 6.95835C5.10651 6.71668 5.50651 6.71668 5.74818 6.95835L5.93151 7.14168L7.36484 5.70835C7.60651 5.46668 8.00651 5.46668 8.24818 5.70835C8.48984 5.95002 8.48984 6.35002 8.24818 6.59168L6.37318 8.46668C6.25651 8.58335 6.09818 8.65002 5.93151 8.65002Z"
                                                                fill="#475467" />
                                                        <path
                                                                d="M14.6836 13.8501H10.3086C9.96693 13.8501 9.68359 13.5668 9.68359 13.2251C9.68359 12.8834 9.96693 12.6001 10.3086 12.6001H14.6836C15.0253 12.6001 15.3086 12.8834 15.3086 13.2251C15.3086 13.5668 15.0336 13.8501 14.6836 13.8501Z"
                                                                fill="#475467" />
                                                        <path
                                                                d="M5.93151 14.4833C5.77318 14.4833 5.61484 14.4249 5.48984 14.2999L4.86484 13.6749C4.62318 13.4333 4.62318 13.0333 4.86484 12.7916C5.10651 12.5499 5.50651 12.5499 5.74818 12.7916L5.93151 12.9749L7.36484 11.5416C7.60651 11.2999 8.00651 11.2999 8.24818 11.5416C8.48984 11.7833 8.48984 12.1833 8.24818 12.4249L6.37318 14.2999C6.25651 14.4166 6.09818 14.4833 5.93151 14.4833Z"
                                                                fill="#475467" />
                                                        <path
                                                                d="M12.5013 18.9584H7.5013C2.9763 18.9584 1.04297 17.0251 1.04297 12.5001V7.50008C1.04297 2.97508 2.9763 1.04175 7.5013 1.04175H12.5013C17.0263 1.04175 18.9596 2.97508 18.9596 7.50008V12.5001C18.9596 17.0251 17.0263 18.9584 12.5013 18.9584ZM7.5013 2.29175C3.65964 2.29175 2.29297 3.65841 2.29297 7.50008V12.5001C2.29297 16.3417 3.65964 17.7084 7.5013 17.7084H12.5013C16.343 17.7084 17.7096 16.3417 17.7096 12.5001V7.50008C17.7096 3.65841 16.343 2.29175 12.5013 2.29175H7.5013Z"
                                                                fill="#475467" />
                                                    </svg>
                                                </span>
                                                <span class="item-para">{{ __('Proposals') }} <strong>{{ $job_details?->job_proposals?->count() }}</strong></span>
                                            </li>
                                            <li class="jobFilter-wrapper-item-bottom-list-item">
                                                <span class="item-icon">
                                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <mask id="path-1-inside-1_6543_8789" fill="white">
                                                        <path d="M8 5.75C7.59 5.75 7.25 5.41 7.25 5V2C7.25 1.59 7.59 1.25 8 1.25C8.41 1.25 8.75 1.59 8.75 2V5C8.75 5.41 8.41 5.75 8 5.75Z"/>
                                                        </mask>
                                                        <path d="M8 4.25C8.41843 4.25 8.75 4.58157 8.75 5H5.75C5.75 6.23843 6.76157 7.25 8 7.25V4.25ZM8.75 5V2H5.75V5H8.75ZM8.75 2C8.75 2.41843 8.41843 2.75 8 2.75V-0.25C6.76157 -0.25 5.75 0.761573 5.75 2H8.75ZM8 2.75C7.58157 2.75 7.25 2.41843 7.25 2H10.25C10.25 0.761573 9.23843 -0.25 8 -0.25V2.75ZM7.25 2V5H10.25V2H7.25ZM7.25 5C7.25 4.58157 7.58157 4.25 8 4.25V7.25C9.23843 7.25 10.25 6.23843 10.25 5H7.25Z" fill="#667085" mask="url(#path-1-inside-1_6543_8789)"/>
                                                        <path d="M16 5.75C15.59 5.75 15.25 5.41 15.25 5V2C15.25 1.59 15.59 1.25 16 1.25C16.41 1.25 16.75 1.59 16.75 2V5C16.75 5.41 16.41 5.75 16 5.75Z" fill="#667085"/>
                                                        <path d="M8.5 14.5003C8.37 14.5003 8.24 14.4703 8.12 14.4203C7.99 14.3703 7.89 14.3003 7.79 14.2103C7.61 14.0203 7.5 13.7703 7.5 13.5003C7.5 13.3703 7.53 13.2403 7.58 13.1203C7.63 13.0003 7.7 12.8903 7.79 12.7903C7.89 12.7003 7.99 12.6303 8.12 12.5803C8.48 12.4303 8.93 12.5103 9.21 12.7903C9.39 12.9803 9.5 13.2403 9.5 13.5003C9.5 13.5603 9.49 13.6303 9.48 13.7003C9.47 13.7603 9.45 13.8203 9.42 13.8803C9.4 13.9403 9.37 14.0003 9.33 14.0603C9.3 14.1103 9.25 14.1603 9.21 14.2103C9.02 14.3903 8.76 14.5003 8.5 14.5003Z" fill="#667085"/>
                                                        <path d="M12 14.4999C11.87 14.4999 11.74 14.4699 11.62 14.4199C11.49 14.3699 11.39 14.2999 11.29 14.2099C11.11 14.0199 11 13.7699 11 13.4999C11 13.3699 11.03 13.2399 11.08 13.1199C11.13 12.9999 11.2 12.8899 11.29 12.7899C11.39 12.6999 11.49 12.6299 11.62 12.5799C11.98 12.4199 12.43 12.5099 12.71 12.7899C12.89 12.9799 13 13.2399 13 13.4999C13 13.5599 12.99 13.6299 12.98 13.6999C12.97 13.7599 12.95 13.8199 12.92 13.8799C12.9 13.9399 12.87 13.9999 12.83 14.0599C12.8 14.1099 12.75 14.1599 12.71 14.2099C12.52 14.3899 12.26 14.4999 12 14.4999Z" fill="#667085"/>
                                                        <path d="M15.5 14.4999C15.37 14.4999 15.24 14.4699 15.12 14.4199C14.99 14.3699 14.89 14.2999 14.79 14.2099C14.75 14.1599 14.71 14.1099 14.67 14.0599C14.63 13.9999 14.6 13.9399 14.58 13.8799C14.55 13.8199 14.53 13.7599 14.52 13.6999C14.51 13.6299 14.5 13.5599 14.5 13.4999C14.5 13.2399 14.61 12.9799 14.79 12.7899C14.89 12.6999 14.99 12.6299 15.12 12.5799C15.49 12.4199 15.93 12.5099 16.21 12.7899C16.39 12.9799 16.5 13.2399 16.5 13.4999C16.5 13.5599 16.49 13.6299 16.48 13.6999C16.47 13.7599 16.45 13.8199 16.42 13.8799C16.4 13.9399 16.37 13.9999 16.33 14.0599C16.3 14.1099 16.25 14.1599 16.21 14.2099C16.02 14.3899 15.76 14.4999 15.5 14.4999Z" fill="#667085"/>
                                                        <path d="M8.5 18.0002C8.37 18.0002 8.24 17.9702 8.12 17.9202C8 17.8702 7.89 17.8002 7.79 17.7102C7.61 17.5202 7.5 17.2602 7.5 17.0002C7.5 16.8702 7.53 16.7402 7.58 16.6202C7.63 16.4902 7.7 16.3802 7.79 16.2902C8.16 15.9202 8.84 15.9202 9.21 16.2902C9.39 16.4802 9.5 16.7402 9.5 17.0002C9.5 17.2602 9.39 17.5202 9.21 17.7102C9.02 17.8902 8.76 18.0002 8.5 18.0002Z" fill="#667085"/>
                                                        <path d="M12 18.0002C11.74 18.0002 11.48 17.8902 11.29 17.7102C11.11 17.5202 11 17.2602 11 17.0002C11 16.8702 11.03 16.7402 11.08 16.6202C11.13 16.4902 11.2 16.3802 11.29 16.2902C11.66 15.9202 12.34 15.9202 12.71 16.2902C12.8 16.3802 12.87 16.4902 12.92 16.6202C12.97 16.7402 13 16.8702 13 17.0002C13 17.2602 12.89 17.5202 12.71 17.7102C12.52 17.8902 12.26 18.0002 12 18.0002Z" fill="#667085"/>
                                                        <path d="M15.5 17.9999C15.24 17.9999 14.98 17.8899 14.79 17.7099C14.7 17.6199 14.63 17.5099 14.58 17.3799C14.53 17.2599 14.5 17.1299 14.5 16.9999C14.5 16.8699 14.53 16.7399 14.58 16.6199C14.63 16.4899 14.7 16.3799 14.79 16.2899C15.02 16.0599 15.37 15.9499 15.69 16.0199C15.76 16.0299 15.82 16.0499 15.88 16.0799C15.94 16.0999 16 16.1299 16.06 16.1699C16.11 16.1999 16.16 16.2499 16.21 16.2899C16.39 16.4799 16.5 16.7399 16.5 16.9999C16.5 17.2599 16.39 17.5199 16.21 17.7099C16.02 17.8899 15.76 17.9999 15.5 17.9999Z" fill="#667085"/>
                                                        <path d="M20.5 9.83984H3.5C3.09 9.83984 2.75 9.49984 2.75 9.08984C2.75 8.67984 3.09 8.33984 3.5 8.33984H20.5C20.91 8.33984 21.25 8.67984 21.25 9.08984C21.25 9.49984 20.91 9.83984 20.5 9.83984Z" fill="#667085"/>
                                                        <path d="M16 22.75H8C4.35 22.75 2.25 20.65 2.25 17V8.5C2.25 4.85 4.35 2.75 8 2.75H16C19.65 2.75 21.75 4.85 21.75 8.5V17C21.75 20.65 19.65 22.75 16 22.75ZM8 4.25C5.14 4.25 3.75 5.64 3.75 8.5V17C3.75 19.86 5.14 21.25 8 21.25H16C18.86 21.25 20.25 19.86 20.25 17V8.5C20.25 5.64 18.86 4.25 16 4.25H8Z" fill="#667085"/>
                                                    </svg>
                                                </span>

                                                @if($job_details->type == 'hourly')
                                                     <span class="item-para">{{ __("Estimated Hours") }}: <strong>{{ $job_details->estimated_hours }}</strong></span>
                                                @else
                                                    <span class="item-para">{{ __('Job created') }} <strong>{{ $job_details->created_at->toFormattedDateString() }}</strong></span>
                                               @endif
                                             </li>
                                        </ul>
                                    </div>
                                    <div class="profile-border-top">
                                        <ul class="jobFilter-wrapper-item-contents-tag">
                                            @foreach($job_details->job_skills as $skill)
                                                <li class="categoryWrap-wrapper-item-contents-tag-list">
                                                    <a href="javascript:void(0)">{{ $skill->skill ?? '' }}</a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                                <div class="myJob-wrapper-single">
                                    <div class="myJob-wrapper-single-header">
                                        <p class="myJob-wrapper-single-title">{!! $job_details->description !!}</p>
                                    </div>
                                    <div class="profile-border-top">
                                        <div class="flex-between job_open_close_btn_location">
                                            <a href="javascript:void(0)" class="job_open_close" data-job-id="{{ $job_details->id }}" data-job-on-off="{{ $job_details->on_off }}">
                                                @if($job_details->on_off == 0)
                                                    <span class="btn-profile btn-outline-1">{{ __('Open Job') }}</span>
                                                @else
                                                    <span class="btn-profile btn-outline-cancel">{{ __('Close Job') }}</span>
                                                @endif
                                            </a>
                                            @if(moduleExists('SecurityManage'))
                                                @if(Auth::guard('web')->user()->freeze_job == 'freeze')
                                                    <a href="#" class="btn-profile btn-og-1 @if(Auth::guard('web')->user()->freeze_job == 'freeze') disabled-link @endif">
                                                        <i class="fa-sharp fa-regular fa-pen-to-square"></i>{{ __('Edit Job') }}
                                                    </a>
                                                @else
                                                    <a href="{{ route('client.job.edit',$job_details->id) }}" class="btn-profile btn-bg-1"> <i class="fa-sharp fa-regular fa-pen-to-square"></i>{{ __('Edit Job') }}</a>
                                                @endif
                                            @else
                                                <a href="{{ route('client.job.edit',$job_details->id) }}" class="btn-profile btn-bg-1"> <i class="fa-sharp fa-regular fa-pen-to-square"></i>{{ __('Edit Job') }}</a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="myJob-tabs mt-5">
                                <ul class="tabs">
                                    <li data-val="all" class="active filter_proposal"> {{ __('Proposals') }} ({{ $job_details->job_proposals->count() ?? '' }}) </li>
                                    <li data-val="hired" class="filter_proposal"> {{ __('Hired') }} ({{ $hired_freelancer_count }}) </li>
                                    <li data-val="shortlisted" class="filter_proposal"> {{ __('Shortlisted') }} ({{ $short_listed_freelancer_count }}) </li>
                                    <li data-val="interviewing" class="filter_proposal"> {{ __('Interviewed') }} ({{ $interviewed_freelancer_count }}) </li>
                                    <input type="hidden" value="{{ $job_details->id }}" id="Job_id_for_filter_jobs">
                                </ul>
                                <div class="filter_job_proposal_result">
                                    @include('frontend.user.client.job.job-details.proposals')
                                </div>
                                </di>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-7">
                        <div class="jobFilter-wrapper sticky_top">
                             <div class="jobFilter-wrapper-item">
                                    <div class="jobFilter-wrapper-item-header profile-border-bottom">
                                        <h4 class="profile-wrapper-item-title"> {{ __('Activities') }} </h4>
                                    </div>
                                    <div class="jobFilter-activities">
                                        <ul class="jobFilter-activities-list">
                                            @php
                                                $activity_count = 0;
                                            @endphp
                                            @foreach ($job_details?->job_proposals as $proposal)
                                                @if ($proposal->is_interview_take == 1)
                                                    @php
                                                        $activity_count++;
                                                    @endphp
                                                    <li class="jobFilter-activities-list-item">
                                                        <h6 class="jobFilter-activities-list-title">
                                                            {{ __('Interviewed') }}  <strong>{{ $proposal?->freelancer?->fullname }}</strong>
                                                        </h6>
                                                        <p class="jobFilter-activities-list-para">
                                                            {{ $proposal->updated_at?->toFormattedDateString() }}</p>
                                                    </li>
                                                @endif
                                                @if ($proposal?->is_interview_take == 0 && $proposal?->is_short_listed == 1)
                                                        @php
                                                            $activity_count++;
                                                        @endphp
                                                    <li class="jobFilter-activities-list-item">
                                                        <h6 class="jobFilter-activities-list-title">
                                                            {{ __('Shortlisted') }}
                                                            <strong>{{ $proposal?->freelancer?->fullname }}</strong>
                                                        </h6>
                                                        <p class="jobFilter-activities-list-para">
                                                            {{ $proposal->updated_at?->toFormattedDateString() }}
                                                        </p>
                                                    </li>
                                                @endif
                                            @endforeach
                                            @if($activity_count === 0)
                                                    <h6 class="jobFilter-activities-list-title">
                                                        {{ __('No Activities') }}
                                                    </h6>
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Profile Details area end -->

            <!-- Send Offer Modal area starts -->
            <div class="popup-overlay"></div>
            <div class="popup-fixed interview-popup">
                <div class="popup-contents">
                    <span class="popup-contents-close popup-close"> <i class="fas fa-times"></i> </span>
                    <h2 class="popup-contents-title">{{ __('Take Interview') }}</h2>
                    <div class="popup-contents-interview profile-border-top">
                        <div class="myJob-wrapper-single-contents">
                            <span class="myJob-wrapper-single-id">#000{{ $job_details->id }} </span>
                            <h4 class="myJob-wrapper-single-title mt-3"><a href="javascript:void(0)">{{ $job_details->title }}</a></h4>
                            <div class="myJob-wrapper-single-list mt-3">
                                <span class="myJob-wrapper-single-list-para">{{ $job_details->created_at->diffForHumans() }} - <a href="javascript:void(0)">{{ ucfirst($job_details->level) }} </a></span>
                            </div>
                        </div>
                    </div>
                    <div class="popup-contents-btn flex-between profile-border-top w-100 margin-0">

                        <div class="popup-contents-interview-form custom-form w-100">
                            <form action="{{ route('client.message.send') }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="freelancer_id" id="freelancer_id">
                                <input type="hidden" name="from_user" id="from_user" value="{{ $job_details->user_id }}">
                                <input type="hidden" name="job_id" id="job_id" value="{{ $job_details->id }}">
                                <input type="hidden" name="type" id="type" value="job">
                                <input type="hidden" name="proposal_id" id="proposal_id_for_check_interview" value="job">
                                <div class="single-input mt-0">
                                    <label for="messages" class="label-title">{{ __('Write a Message') }}</label>
                                    <textarea name="interview_message" id="interview_message" cols="30" rows="2" class="form-message" placeholder="{{ __('E.g.I would you like to invite yo...') }}"></textarea>
                                </div>
                                <div class="btn-wrapper flex-btn gap-2 mt-3">
                                    <div class="btn-wrapper">
                                        <a href="javascript:void(0)" class="btn-profile btn-outline-gray btn-hover-danger popup-close"> {{ __('Cancel') }} </a>
                                    </div>
                                    <button type="submit" class="btn-profile btn-bg-1"><i class="fa-regular fa-comments"></i> {{ __('Send Message') }}</button>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
            <!-- Send Offer Modal area ends -->

            <!-- update rate and hours -->
            <div class="modal fade" id="RateAndHoursModal" tabindex="-1" aria-labelledby="RateAndHoursModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <form action="{{ route('client.job.hourly.rate') }}" method="post">
                        @csrf
                        <div class="modal-content">
                            <div class="modal-header">
                                <h3 class="modal-title" id="RateAndHoursModalLabel"> {{ __('Hourly Rate & Estimated Hours') }} </h3>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="job_id" value="{{ $job_details->id }}">
                                <div class="single-input">
                                    <label class="label-title mb-2">{{ __('Hourly Rate') }}</label>
                                    <input name="hourly_rate" class="form-control" value="{{ $job_details->hourly_rate }}">
                                </div>
                                <div class="single-input mt-2">
                                    <label class="label-title mb-2">{{ __('Estimated Hours') }}</label>
                                    <input name="estimated_hour" class="form-control" value="{{ $job_details->estimated_hours }}">
                                </div>
                            </div>
                            <div class="modal-footer flex-column">
                                <div class="d-flex flex-wrap gap-3">
                                    <button type="submit" class="btn-profile btn-bg-1">{{ __('Update') }}</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
    </main>
@endsection

@section('script')
    @include('frontend.user.client.job.job-details.proposal-js')
    <x-summernote.summernote-js/>
    <x-select2.select2-js/>
    <x-sweet-alert.sweet-alert2-js/>
@endsection
