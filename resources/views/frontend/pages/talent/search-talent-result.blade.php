<div class="shop-contents-wrapper-right">
    <div class="row g-4">
            <div class="col-lg-12">
                <div class="categoryWrap-wrapper-item">
                    <div class="row g-4">
                        @php $current_date = \Carbon\Carbon::now()->toDateTimeString() @endphp
                        @foreach ($talents as $talent)
                            <div class="col-xxl-4 col-md-6">
                                <div class="single-freelancer center-text radius-20">
                                    <div class="single-freelancer-author">
                                        @if(moduleExists('PromoteFreelancer'))
                                            @if($talent->pro_expire_date >= $current_date  && $talent->is_pro === 'yes')
                                                @if($is_pro == 1)
                                                   {{--set is_pro value in session and get from profile details controller for click count--}}
                                                    @php Session::put('is_pro',$is_pro) @endphp
                                                <div class="single-project-content-review pro-profile-badge">
                                                    <div class="pro-icon-background">
                                                        <i class="fas fa-check"></i>
                                                    </div>
                                                    <small>{{ __('Pro') }}</small>
                                                </div>
                                                @endif
                                            @endif
                                        @endif
                                        <div class="single-freelancer-author-thumb mb-2">
                                            @if ($talent->image)
                                                <a href="{{ route('freelancer.profile.details', $talent->username) }}">
                                                    @if(cloudStorageExist() && in_array(Storage::getDefaultDriver(), ['s3', 'cloudFlareR2', 'wasabi']))
                                                        <img src="{{ render_frontend_cloud_image_if_module_exists( 'profile/'. $talent->image, load_from: $talent->load_from) }}" alt="{{ $talent->first_name }}">
                                                    @else
                                                    <img src="{{ asset('assets/uploads/profile/' . $talent->image) }}"
                                                         alt="{{ $talent->first_name }}">
                                                    @endif
                                                </a>
                                                @if(moduleExists('FreelancerLevel'))
                                                    <div class="freelancer-level-badge">
                                                        {!! freelancer_level($talent->id,'talent') ?? '' !!}
                                                    </div>
                                                @endif
                                            @else
                                                <a href="{{ route('freelancer.profile.details', $talent->username) }}">
                                                    <img src="{{ asset('assets/static/img/author/author.jpg') }}"
                                                         alt="{{ __('AuthorImg') }}">
                                                </a>
                                                @if(moduleExists('FreelancerLevel'))
                                                    <div class="freelancer-level-badge">
                                                        {!! freelancer_level($talent->id,'talent') ?? '' !!}
                                                    </div>
                                                @endif
                                            @endif
                                        </div>
                                        <x-status.user-active-inactive-check :userID="$talent->id" />
                                        <h4 class="single-freelancer-author-name mt-2">
                                            <a href="{{ route('freelancer.profile.details', $talent->username) }}">
                                                {{ $talent->full_name }}
                                                @if($talent->user_verified_status == 1) <i class="fas fa-circle-check"></i>@endif
                                            </a>
                                        </h4>
                                        <span class="single-freelancer-author-para mt-2">
                                            {{ $talent?->user_introduction?->title ?? '' }}
                                        </span>
                                        {!! freelancer_rating_for_profile_details_page($talent->id) !!}
                                    </div>
                                    <div class="single-freelancer-bottom">
                                        <div class="btn-wrapper">
                                            <a href="{{ route('freelancer.profile.details', $talent->username) }}" class="cmn-btn btn-bg-gray btn-small w-100 radius-5"> {{ __('View Profile') }} </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
    </div>
</div>

<x-pagination.laravel-paginate :allData="$talents" />
