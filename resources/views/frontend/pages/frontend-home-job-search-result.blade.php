@if ($projects_or_jobs->count() >= 1)
    @if ($search_type == 'project')
        <div class="global-search-result-inner">
            @foreach ($projects_or_jobs as $project)
                <a href="{{ route('project.details', ['username' => $project?->project_creator?->username, 'slug' => $project->slug]) }}"
                   class="global-search-result-inner-item">
                    <div class="global-search-result-inner-item-thumb">
                        <img src="{{ asset('assets/uploads/project/' . $project->image) ?? '' }}"
                             alt="{{ $project->image ?? '' }}">
                    </div>
                    <div class="global-search-result-inner-item-contents">
                        <h6 class="global-search-result-inner-title">{{ $project->title }}</h6>
                        <span class="global-search-result-inner-contents mt-1">
                            <span
                                    class="global-search-result-inner-price">{{ float_amount_with_currency_symbol($project->basic_regular_charge) }}</span>
                        </span>
                    </div>
                </a>
            @endforeach
        </div>
    @endif
    @if ($search_type == 'job')
        <div class="global-search-result-inner">
            @foreach ($projects_or_jobs as $job)
                <div class="global-search-result-inner-item global-job-item">
                    <div class="global-search-result-inner-item-contents">
                        <h6 class="global-search-result-inner-title">
                            <a href="{{ route('job.details', ['username' => $job?->job_creator?->username, 'slug' => $job->slug]) }}">{{ $job->title }}</a>
                        </h6>
                        <span class="global-search-result-inner-contents mt-1">
                            <span class="global-search-result-inner-price">{{ float_amount_with_currency_symbol($job->budget) }}</span>
                        </span>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    @if ($search_type == 'talent')
        <div class="global-search-result-inner">
            @foreach ($projects_or_jobs as $talent)
                <a href="{{ route('freelancer.profile.details', $talent->username) }}"
                   class="global-search-result-inner-item">
                    <div class="global-search-result-inner-item-thumb">
                        @if($talent->image)
                            <img src="{{ asset('assets/uploads/profile/' . $talent->image) ?? '' }}" alt="{{ $talent->image ?? '' }}">
                        @else
                            <img src="{{ asset('assets/static/img/author/author.jpg') }}" alt="talent-image">
                        @endif
                    </div>
                    <div class="global-search-result-inner-item-contents">
                        <h6 class="global-search-result-inner-title">{{ $talent->fullname }}</h6>
                        <span class="global-search-result-inner-contents mt-1">
                            <span class="global-search-result-inner-price">{{ $talent?->user_introduction->title }}</span>
                        </span>
                    </div>
                </a>
            @endforeach
        </div>
    @endif
@else
    <div class="">
        <p class="text-danger">{{ __('Nothing found') }}</p>
    </div>
@endif
