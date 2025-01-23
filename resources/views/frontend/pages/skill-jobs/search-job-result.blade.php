@if(!empty($jobs))
<div class="shop-contents-wrapper-right">
    <div class="row g-4">
          @foreach ($jobs as $job)
              @if($job->job_creator?->username)
                <div class="col-lg-12">
                <div class="categoryWrap-wrapper-item jobDetails-padding">
                    <div class="categoryWrap-wrapper-item-inner">
                        <div class="categoryWrap-wrapper-item-top">
                            <div class="categoryWrap-wrapper-item-top-left">
                                <a
                                    href="{{ route('job.details', ['username' => $job->job_creator?->username, 'slug' => $job->slug]) }}">
                                    <h4 class="single-jobs-title">{{ $job->title }}
                                    </h4>
                                </a>
                                <p class="single-jobs-date">
                                    {{ $job->created_at->toFormattedDateString() ?? '' }} -
                                    <span>{{ ucfirst(__($job->level)) ?? '' }}</span>
                                </p>
                            </div>
                            <div class="categoryWrap-wrapper-item-top-right">
                                <div class="categoryWrap-wrapper-item-top-right-image jobbookmark">
                                    <x-frontend.bookmark :identity="$job->id" :type="'job'" />
                                </div>
                            </div>
                        </div>
                        <div class="categoryWrap-wrapper-item-contents">
                            <div class="obFilter-wrapper-item-contents-flex flex-between">
                                @if($job->type == 'hourly')
                                    <h3 class="single-jobs-price">
                                        {{ float_amount_with_currency_symbol($job->hourly_rate) }}
                                        <span class="single-jobs-price-fixed">
                                        {{ ucfirst(__($job->type)) }}</span>
                                    </h3>
                                @else
                                <h3 class="single-jobs-price">
                                    {{ float_amount_with_currency_symbol($job->budget) }}
                                    <span class="single-jobs-price-fixed">
                                        {{ ucfirst(__($job->type)) }}</span>
                                </h3>
                                @endif
                            </div>
                            <p class="single-jobs-para mt-4">{!! Str::limit(strip_tags($job->description), 150) !!}</p>
                            <div class="single-jobs-tag mt-4">
                                @foreach ($job->job_skills as $skill)
                                    <a href="{{ route('skill.jobs', $skill->skill) }}"
                                        class="single-jobs-tag-link">{{ $skill->skill ?? '' }}</a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="categoryWrap-wrapper-item-bottom">
                        <ul class="categoryWrap-wrapper-item-bottom-list">
                            <li class="categoryWrap-wrapper-item-bottom-list-item">
                                <span class="item-icon">
                                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M9.99844 11.8084C8.22344 11.8084 6.77344 10.3667 6.77344 8.58337C6.77344 6.80003 8.22344 5.3667 9.99844 5.3667C11.7734 5.3667 13.2234 6.80837 13.2234 8.5917C13.2234 10.375 11.7734 11.8084 9.99844 11.8084ZM9.99844 6.6167C8.9151 6.6167 8.02344 7.50003 8.02344 8.5917C8.02344 9.68337 8.90677 10.5667 9.99844 10.5667C11.0901 10.5667 11.9734 9.68337 11.9734 8.5917C11.9734 7.50003 11.0818 6.6167 9.99844 6.6167Z"
                                            fill="#475467" />
                                        <path
                                            d="M10.0014 18.9667C8.76803 18.9667 7.52637 18.5001 6.5597 17.5751C4.10137 15.2084 1.3847 11.4334 2.4097 6.94175C3.3347 2.86675 6.89303 1.04175 10.0014 1.04175C10.0014 1.04175 10.0014 1.04175 10.0097 1.04175C13.118 1.04175 16.6764 2.86675 17.6014 6.95008C18.618 11.4417 15.9014 15.2084 13.443 17.5751C12.4764 18.5001 11.2347 18.9667 10.0014 18.9667ZM10.0014 2.29175C7.57637 2.29175 4.4597 3.58341 3.6347 7.21675C2.7347 11.1417 5.20137 14.5251 7.4347 16.6667C8.87637 18.0584 11.1347 18.0584 12.5764 16.6667C14.8014 14.5251 17.268 11.1417 16.3847 7.21675C15.5514 3.58341 12.4264 2.29175 10.0014 2.29175Z"
                                            fill="#475467" />
                                    </svg>
                                </span>
                                <span class="item-para">{{ $job->job_creator?->user_country?->country }}</span>
                            </li>
                            <li class="categoryWrap-wrapper-item-bottom-list-item">
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
                                <span class="item-para">{{ __('Proposal:') }} {{ $job->job_proposals_count }}</span>
                            </li>
                            @if(moduleExists('HourlyJob'))
                            <li class="categoryWrap-wrapper-item-bottom-list-item">
                                <span class="item-icon">
                                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M11.2513 7.70825H1.66797C1.3263 7.70825 1.04297 7.42492 1.04297 7.08325C1.04297 6.74159 1.3263 6.45825 1.66797 6.45825H11.2513C11.593 6.45825 11.8763 6.74159 11.8763 7.08325C11.8763 7.42492 11.593 7.70825 11.2513 7.70825Z"
                                            fill="#475467" />
                                        <path
                                            d="M6.66667 14.375H5C4.65833 14.375 4.375 14.0917 4.375 13.75C4.375 13.4083 4.65833 13.125 5 13.125H6.66667C7.00833 13.125 7.29167 13.4083 7.29167 13.75C7.29167 14.0917 7.00833 14.375 6.66667 14.375Z"
                                            fill="#475467" />
                                        <path
                                            d="M12.0833 14.375H8.75C8.40833 14.375 8.125 14.0917 8.125 13.75C8.125 13.4083 8.40833 13.125 8.75 13.125H12.0833C12.425 13.125 12.7083 13.4083 12.7083 13.75C12.7083 14.0917 12.425 14.375 12.0833 14.375Z"
                                            fill="#475467" />
                                        <path
                                            d="M14.6346 17.7084H5.36797C2.0513 17.7084 1.04297 16.7084 1.04297 13.4251V6.57508C1.04297 3.29175 2.0513 2.29175 5.36797 2.29175H11.2513C11.593 2.29175 11.8763 2.57508 11.8763 2.91675C11.8763 3.25841 11.593 3.54175 11.2513 3.54175H5.36797C2.7513 3.54175 2.29297 3.99175 2.29297 6.57508V13.4167C2.29297 16.0001 2.7513 16.4501 5.36797 16.4501H14.6263C17.243 16.4501 17.7013 16.0001 17.7013 13.4167V10.0167C17.7013 9.67508 17.9846 9.39175 18.3263 9.39175C18.668 9.39175 18.9513 9.67508 18.9513 10.0167V13.4167C18.9596 16.7084 17.9513 17.7084 14.6346 17.7084Z"
                                            fill="#475467" />
                                        <path
                                            d="M14.4237 7.45003C14.2654 7.45003 14.107 7.3917 13.982 7.2667C13.7404 7.02503 13.7404 6.62503 13.982 6.38337L17.2237 3.1417C17.4654 2.90003 17.8654 2.90003 18.107 3.1417C18.3487 3.38337 18.3487 3.78337 18.107 4.02503L14.8654 7.2667C14.7404 7.3917 14.582 7.45003 14.4237 7.45003Z"
                                            fill="#475467" />
                                        <path
                                            d="M17.6576 7.45003C17.4992 7.45003 17.3409 7.3917 17.2159 7.2667L13.9742 4.02503C13.7326 3.78337 13.7326 3.38337 13.9742 3.1417C14.2159 2.90003 14.6159 2.90003 14.8576 3.1417L18.0992 6.38337C18.3409 6.62503 18.3409 7.02503 18.0992 7.2667C17.9826 7.3917 17.8242 7.45003 17.6576 7.45003Z"
                                            fill="#475467" />
                                    </svg>
                                </span>
                                @if($job->job_creator?->user_wallet?->balance >= ($job->hourly_rate * $job->estimated_hours) )
                                    <span class="item-para">{{  __('Verified') }}</span>
                                @else
                                    <span class="item-para">{{ __('Not Verified') }}</span>
                                @endif
                            </li>
                            @endif
                            <li class="categoryWrap-wrapper-item-bottom-list-item">
                                <span class="item-icon">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M12 22.75C6.07 22.75 1.25 17.93 1.25 12C1.25 6.07 6.07 1.25 12 1.25C17.93 1.25 22.75 6.07 22.75 12C22.75 17.93 17.93 22.75 12 22.75ZM12 2.75C6.9 2.75 2.75 6.9 2.75 12C2.75 17.1 6.9 21.25 12 21.25C17.1 21.25 21.25 17.1 21.25 12C21.25 6.9 17.1 2.75 12 2.75Z"
                                            fill="#667085" />
                                        <path
                                            d="M15.7106 15.93C15.5806 15.93 15.4506 15.9 15.3306 15.82L12.2306 13.97C11.4606 13.51 10.8906 12.5 10.8906 11.61V7.50999C10.8906 7.09999 11.2306 6.75999 11.6406 6.75999C12.0506 6.75999 12.3906 7.09999 12.3906 7.50999V11.61C12.3906 11.97 12.6906 12.5 13.0006 12.68L16.1006 14.53C16.4606 14.74 16.5706 15.2 16.3606 15.56C16.2106 15.8 15.9606 15.93 15.7106 15.93Z"
                                            fill="#667085" />
                                    </svg>
                                </span>
                                <span class="item-para">{{ ucfirst(__($job->duration)) ?? '' }}</span>
                            </li>
                            @if(moduleExists('HourlyJob'))
                                @if($job->type == 'hourly')
                                    <li class="categoryWrap-wrapper-item-bottom-list-item">
                                    <span class="item-icon">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                             xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                    d="M12 22.75C6.07 22.75 1.25 17.93 1.25 12C1.25 6.07 6.07 1.25 12 1.25C17.93 1.25 22.75 6.07 22.75 12C22.75 17.93 17.93 22.75 12 22.75ZM12 2.75C6.9 2.75 2.75 6.9 2.75 12C2.75 17.1 6.9 21.25 12 21.25C17.1 21.25 21.25 17.1 21.25 12C21.25 6.9 17.1 2.75 12 2.75Z"
                                                    fill="#667085" />
                                            <path
                                                    d="M15.7106 15.93C15.5806 15.93 15.4506 15.9 15.3306 15.82L12.2306 13.97C11.4606 13.51 10.8906 12.5 10.8906 11.61V7.50999C10.8906 7.09999 11.2306 6.75999 11.6406 6.75999C12.0506 6.75999 12.3906 7.09999 12.3906 7.50999V11.61C12.3906 11.97 12.6906 12.5 13.0006 12.68L16.1006 14.53C16.4606 14.74 16.5706 15.2 16.3606 15.56C16.2106 15.8 15.9606 15.93 15.7106 15.93Z"
                                                    fill="#667085" />
                                        </svg>
                                    </span>
                                    <span class="item-para">{{ __('Estimated Hours:') }} {{ ucfirst(__($job->estimated_hours)) ?? '' }}</span>
                                    </li>
                                @endif
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
            @endif
        @endforeach
    </div>
</div>

<x-pagination.laravel-paginate :allData="$jobs ?? ''" />

@else
    <div class="congratulation-area section-bg-2 pat-100 pab-100">
        <div class="container">
            <div class="congratulation-wrapper">
                <div class="congratulation-contents center-text">
                    <div class="congratulation-contents-icon bg-danger wow  zoomIn animated" data-wow-delay=".5s" style="visibility: visible; animation-delay: 0.5s; animation-name: zoomIn;">
                        <i class="fas fa-times"></i>
                    </div>
                    <h4 class="congratulation-contents-title"> {{ __('OPPS!') }} </h4>
                    <p class="congratulation-contents-para">{{ __('Nothing') }} <strong>{{ __('Found') }}</strong> </p>
                </div>
            </div>
        </div>
    </div>
@endif
