<div class="shop-contents-wrapper-right">
    <div class="row g-4">
        @foreach ($jobs as $job)
            <div class="col-lg-12">
                <div class="categoryWrap-wrapper-item jobDetails-padding">
                    <div class="categoryWrap-wrapper-item-inner">
                        <div class="categoryWrap-wrapper-item-top">
                            <div class="categoryWrap-wrapper-item-top-left">
                                <a
                                    href="{{ route('job.details', ['username' => $job->job_creator?->username, 'slug' => $job->slug]) }}">
                                    <h4 class="single-jobs-title categoryWrap-wrapper-item-title">{{ $job->title }}
                                    </h4>
                                </a>
                                <p class="single-jobs-date">
                                    {{ $job->created_at->toFormattedDateString() ?? '' }} -
                                    <span>{{ ucfirst($job->level) ?? '' }}</span>
                                </p>
                            </div>
                            <div class="categoryWrap-wrapper-item-top-right">
                                <div class="categoryWrap-wrapper-item-top-right-image jobbookmark">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M14.5 11.4H9.5C9.09 11.4 8.75 11.06 8.75 10.65C8.75 10.24 9.09 9.9 9.5 9.9H14.5C14.91 9.9 15.25 10.24 15.25 10.65C15.25 11.06 14.91 11.4 14.5 11.4Z"
                                            fill="#667085"></path>
                                        <path
                                            d="M12 13.96C11.59 13.96 11.25 13.62 11.25 13.21V8.21C11.25 7.8 11.59 7.46 12 7.46C12.41 7.46 12.75 7.8 12.75 8.21V13.21C12.75 13.62 12.41 13.96 12 13.96Z"
                                            fill="#667085"></path>
                                        <path
                                            d="M19.0703 22.75C18.5603 22.75 18.0003 22.6 17.4603 22.29L12.5803 19.58C12.2903 19.42 11.7203 19.42 11.4303 19.58L6.55031 22.29C5.56031 22.84 4.55031 22.9 3.78031 22.44C3.01031 21.99 2.57031 21.08 2.57031 19.95V5.86C2.57031 3.32 4.64031 1.25 7.18031 1.25H16.8303C19.3703 1.25 21.4403 3.32 21.4403 5.86V19.95C21.4403 21.08 21.0003 21.99 20.2303 22.44C19.8803 22.65 19.4803 22.75 19.0703 22.75ZM12.0003 17.96C12.4703 17.96 12.9303 18.06 13.3003 18.27L18.1803 20.98C18.6903 21.27 19.1603 21.33 19.4603 21.15C19.7603 20.97 19.9303 20.54 19.9303 19.95V5.86C19.9303 4.15 18.5303 2.75 16.8203 2.75H7.18031C5.47031 2.75 4.07031 4.15 4.07031 5.86V19.95C4.07031 20.54 4.24031 20.98 4.54031 21.15C4.84031 21.32 5.31031 21.27 5.82031 20.98L10.7003 18.27C11.0703 18.06 11.5303 17.96 12.0003 17.96Z"
                                            fill="#667085"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                        <div class="categoryWrap-wrapper-item-contents mt-4">
                            <div class="obFilter-wrapper-item-contents-flex flex-between">
                                <h3 class="single-jobs-price">
                                    {{ float_amount_with_currency_symbol($job->budget) }}
                                    <span class="single-jobs-price-fixed">
                                        {{ __(ucfirst($job->type)) }}</span>
                                </h3>
                            </div>
                            <p class="single-jobs-para mt-4">{!! Str::limit(strip_tags($job->description), 150) !!}</p>
                            <div class="single-jobs-tag mt-4">
                                @foreach ($job->job_skills as $skill)
                                    <a href="javascript:void(0)"
                                        class="single-jobs-tag-link">{{ $skill->skill ?? '' }}</a></li>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="categoryWrap-wrapper-item-bottom">
                        <ul class="categoryWrap-wrapper-item-bottom-list">
                            <li class="categoryWrap-wrapper-item-bottom-list-item">
                                <span class="item-icon">
                                    <i class="fa-solid fa-location-dot"></i>
                                </span>
                                <span class="item-para">{{ $job->job_creator?->user_country?->country }}</span>
                            </li>
                            <li class="categoryWrap-wrapper-item-bottom-list-item"><span class="item-icon"><i
                                        class="fa-solid fa-list-check"></i></span> <span class="item-para">Proposal:
                                    15</span> </li>
                            <li class="categoryWrap-wrapper-item-bottom-list-item">
                                <span class="item-icon"><i class="fas fa-user"></i></span>
                                <span
                                    class="item-para">{{ $job->job_creator?->user_verified_status == 1 ? 'Verified' : 'Not Verified' }}</span>
                            </li>
                            <li class="categoryWrap-wrapper-item-bottom-list-item">
                                <span class="item-icon"><i class="fa-regular fa-clock"></i></span>
                                <span class="item-para">{{ ucfirst($job->duration) ?? '' }}</span>
                            </li>
                            <li class="categoryWrap-wrapper-item-bottom-list-item">
                                <span class="categoryWrap-wrapper-item-contents-review"><i class="fas fa-star"></i> 4.9
                                </span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

{!! $jobs->links() !!}
