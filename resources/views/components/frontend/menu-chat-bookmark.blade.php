@if (Auth::guard('web')->user()->user_type == 1 && Session::get('user_role') != 'freelancer')
    @php
        if(get_static_option('project_enable_disable') != 'disable' && get_static_option('job_enable_disable') != 'disable'){
            $client_bookmarks = \App\Models\Bookmark::where('user_id',Auth::guard('web')->user()->id)->get();
        }else if(get_static_option('project_enable_disable') == 'disable' && get_static_option('job_enable_disable') == 'disable'){
            $client_bookmarks = '';
        }else if(get_static_option('project_enable_disable') == 'disable'){
            $client_bookmarks = \App\Models\Bookmark::whereHas('bookmark_job')
            ->where('is_project_job','job')
            ->where('user_id',Auth::guard('web')->user()->id)
            ->get();
        }else if(get_static_option('job_enable_disable') == 'disable'){
            $client_bookmarks = \App\Models\Bookmark::whereHas('bookmark_project')
            ->where('is_project_job','projct')
            ->where('user_id',Auth::guard('web')->user()->id)
            ->get();
        }
    @endphp
    <div class="navbar-right-item">
        @php
            $unseen_message_count = \App\Models\User::select('id')->withCount(['client_unseen_message' => function($q){
                  $q->where('is_seen',0)->where('from_user',2);
                }])->where('id', auth("web")->id())->first();
        @endphp
        <a href="{{ route('client.live.chat') }}" class="navbar-right-chat position-relative reload_unseen_message_count"> <i class="fa-regular fa-comment-dots"></i>
            @if ($unseen_message_count->client_unseen_message_count > 0)
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">{{ $unseen_message_count->client_unseen_message_count ?? '' }}</span>
            @endif
        </a>
    </div>
    <div class="navbar-right-item bookmark_area position-relative">
        <a href="#0" class="navbar-right-chat nav-right-bookmark-icon position-relative">
            <i class="fa-regular fa-bookmark"></i>
            @if (!empty($client_bookmarks) && $client_bookmarks->count() > 0)
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-secondary">
                     {{ $client_bookmarks->count() }}
                </span>
            @endif
        </a>
        <div class="bookmark-wrap">
            @if(!empty($client_bookmarks) && $client_bookmarks->count() > 0)
                @foreach ($client_bookmarks as $bookmark)
                    <div class="bookmark-item">
                        @if ($bookmark->is_project_job == 'project')
                            <a href="{{ route('project.details', ['username' => $bookmark?->bookmark_project?->project_creator?->username, 'slug' => $bookmark?->bookmark_project?->slug]) }}"
                               class="bookmark-item-para">
                                <span class="bookmark-header-icon"><i class="fa-regular fa-bookmark"></i></span>
                                {{ $bookmark?->bookmark_project?->title ?? '' }}
                            </a>
                        @else
                            <a href="{{ route('job.details', ['username' => $bookmark?->bookmark_job?->job_creator?->username, 'slug' => $bookmark?->bookmark_job?->slug]) }}"
                               class="bookmark-item-para">
                                <span class="bookmark-header-icon"><i class="fa-regular fa-bookmark"></i></span>
                                {{ $bookmark?->bookmark_job?->title ?? '' }}
                            </a>
                        @endif
                        <span class="bookmark-item-close remove_from_bookmark"
                              data-identity = "{{ $bookmark->id }}"
                              data-route = "{{ route('client.bookmark.remove') }}">
                                        <i class="fas fa-times"></i>
                                    </span>
                    </div>
                @endforeach
            @else
                <span class="navbar-right-notification-wrapper-list-item-content-title">
                                    <span class="bookmark-header">
                                        <span class="bookmark-header-icon"><i class="fa-regular fa-bookmark"></i></span>
                                        <h6 class="bookmark-header-title">{{ __('No Bookmarks') }}</h6>
                                    </span>
                                </span>
            @endif
        </div>
    </div>
@else
    @php
        if(get_static_option('project_enable_disable') != 'disable' && get_static_option('job_enable_disable') != 'disable'){
            $freelancer_bookmarks = \App\Models\Bookmark::where('user_id',Auth::guard('web')->user()->id)->get();
        }else if(get_static_option('project_enable_disable') == 'disable' && get_static_option('job_enable_disable') == 'disable'){
            $freelancer_bookmarks = '';
        }else if(get_static_option('project_enable_disable') == 'disable'){
            $freelancer_bookmarks = \App\Models\Bookmark::whereHas('bookmark_job')
            ->where('is_project_job','job')
            ->where('user_id',Auth::guard('web')->user()->id)->get();
        }else if(get_static_option('job_enable_disable') == 'disable'){
            $freelancer_bookmarks = \App\Models\Bookmark::whereHas('bookmark_project')
            ->where('is_project_job','project')
            ->where('user_id',Auth::guard('web')->user()->id)->get();
        }
    @endphp

    @if (Auth::guard('web')->user()->user_type == 2 && Session::get('user_role') == 'client')
        {{--this is for freelancer he switch as a client--}}
        @php
            if(get_static_option('project_enable_disable') != 'disable' && get_static_option('job_enable_disable') != 'disable'){
                $client_bookmarks = \App\Models\Bookmark::where('user_id',Auth::guard('web')->user()->id)->get();
            }else if(get_static_option('project_enable_disable') == 'disable' && get_static_option('job_enable_disable') == 'disable'){
                $client_bookmarks = '';
            }else if(get_static_option('project_enable_disable') == 'disable'){
                $client_bookmarks = \App\Models\Bookmark::whereHas('bookmark_job')
                ->where('is_project_job','job')
                ->where('user_id',Auth::guard('web')->user()->id)
                ->get();
            }else if(get_static_option('job_enable_disable') == 'disable'){
                $client_bookmarks = \App\Models\Bookmark::whereHas('bookmark_project')
                ->where('is_project_job','projct')
                ->where('user_id',Auth::guard('web')->user()->id)
                ->get();
            }
        @endphp
        <div class="navbar-right-item">
            @php
                $unseen_message_count = \App\Models\User::select('id')->withCount(['client_unseen_message' => function($q){
                      $q->where('is_seen',0)->where('from_user',2);
                    }])->where('id', auth("web")->id())->first();
            @endphp
            <a href="{{ route('client.live.chat') }}" class="navbar-right-chat position-relative reload_unseen_message_count"> <i class="fa-regular fa-comment-dots"></i>
                @if ($unseen_message_count->client_unseen_message_count > 0)
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">{{ $unseen_message_count->client_unseen_message_count ?? '' }}</span>
                @endif
            </a>
        </div>
    @else
        {{--this is for freelancer and also for client when he switch as a freelancer--}}
        <div class="navbar-right-item">
            @php
                $unseen_message_count = \App\Models\User::select('id')->withCount(['freelancer_unseen_message' => function($q){
                      $q->where('is_seen',0)->where('from_user',1);
                    }])->where('id', auth("web")->id())->first();
            @endphp
            <a href="{{ route('freelancer.live.chat') }}" class="navbar-right-chat position-relative reload_unseen_message_count"> <i class="fa-regular fa-comment-dots"></i>
                @if ($unseen_message_count->freelancer_unseen_message_count > 0)
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">{{ $unseen_message_count->freelancer_unseen_message_count ?? '' }}</span>
                @endif
            </a>
        </div>
    @endif

    <div class="navbar-right-item bookmark_area position-relative">
        <a href="#0" class="navbar-right-chat nav-right-bookmark-icon position-relative">
            <i class="fa-regular fa-bookmark"></i>
            @if (!empty($freelancer_bookmarks) && $freelancer_bookmarks->count() > 0)
                <span
                        class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-secondary">
                                    {{ $freelancer_bookmarks->count() }}
                                </span>
            @endif
        </a>
        <div class="bookmark-wrap">
            @if(!empty($freelancer_bookmarks) && $freelancer_bookmarks->count() > 0)
                @foreach ($freelancer_bookmarks as $bookmark)
                    <div class="bookmark-item">
                        @if ($bookmark->is_project_job == 'project')
                            <a href="{{ route('project.details', ['username' => $bookmark?->bookmark_project?->project_creator?->username, 'slug' => $bookmark?->bookmark_project?->slug]) }}"
                               class="bookmark-item-para"> <span class="bookmark-header-icon"><i class="fa-regular fa-bookmark"></i></span>
                                {{ $bookmark?->bookmark_project?->title ?? '' }}
                            </a>
                        @else
                            <a href="{{ route('job.details', ['username' => $bookmark?->bookmark_job?->job_creator?->username, 'slug' => $bookmark?->bookmark_job?->slug]) }}"
                               class="bookmark-item-para"><span class="bookmark-header-icon"><i class="fa-regular fa-bookmark"></i></span>
                                {{ $bookmark?->bookmark_job?->title ?? '' }}
                            </a>
                        @endif
                        <span class="bookmark-item-close remove_from_bookmark"
                              data-identity = "{{ $bookmark->id }}"
                              data-route = "{{ route('freelancer.bookmark.remove') }}">
                                            <i class="fas fa-times"></i>
                                        </span>
                    </div>
                @endforeach
            @else
                <span class="navbar-right-notification-wrapper-list-item-content-title">
                                    <span class="bookmark-header">
                                        <span class="bookmark-header-icon"><i class="fa-regular fa-bookmark"></i></span>
                                        <h6 class="bookmark-header-title">{{ __('No Bookmarks') }}</h6>
                                    </span>
                                </span>
            @endif
        </div>
    </div>
@endif