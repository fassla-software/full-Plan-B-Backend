<div class="profile-wrapper-item add-project-parent radius-10 project_wrapper_area">
    <div class="profile-wrapper-item-flex flex-between align-items-center profile-border-bottom">
        <h4 class="profile-wrapper-item-title"> {{ __('Project Catalogues') }} </h4>
        @if(Auth::guard('web')->check() && Auth::guard('web')->user()->user_type == 2  && Auth::guard('web')->user()->username==$username)
            <div class="profile-wrapper-item-plus create_project_show_hide">
               <a href="{{route('freelancer.project.create')}}"><i class="fas fa-plus"></i></a>
            </div>
        @endif
    </div>
    @foreach($projects as $project)
        @if(Auth::guard('web')->check() && Auth::guard('web')->user()->user_type == 2 && Auth::guard('web')->user()->username==$username)
            <div class="single-project project-catalogue">
                <div class="project-catalogue-flex">
                    <div class="single-project-thumb project-catalogue-thumb">
                        <a href="{{ route('project.details', ['username' => $project->project_creator?->username, 'slug' => $project->slug]) }}">

                            @if(cloudStorageExist() && in_array(Storage::getDefaultDriver(), ['s3', 'cloudFlareR2', 'wasabi']))
                                <img src="{{ render_frontend_cloud_image_if_module_exists( 'project/'.$project->image, load_from: $project->load_from) }}" alt="project">
                            @else
                                <img src="{{url('assets/uploads/project/'.$project->image)}}" alt="project">
                            @endif

                        </a>
                    </div>
                    <div class="single-project-content project-catalogue-contents mt-0">
                        <div class="single-project-content-top align-items-center flex-between">
                            {!! project_rating($project->id) !!}
                        </div>
                        <h4 class="single-project-content-title">
                            <a href="{{ route('project.details',['username'=>$project->project_creator?->username,'slug'=>$project->slug]) }}"> {{$project->title}} </a>
                        </h4>

                        <div class="project-catalogue-bottom flex-between mt-3">
                            @if($project->basic_discount_charge != null && $project->basic_discount_charge > 0)
                                <span class="single-project-content-price"> {{ amount_with_currency_symbol($project->basic_discount_charge) ?? '' }} <s>{{ amount_with_currency_symbol($project->basic_regular_charge) ?? '' }}</s> </span>
                            @else
                                <span class="single-project-content-price"> {{ amount_with_currency_symbol($project->basic_regular_charge) ?? '' }}</span>
                            @endif
                            <div class="single-project-delivery">
                            <span class="single-project-delivery-icon">
                                <i class="fa-regular fa-clock"></i> {{ __('Delivery') }}
                            </span>
                                <span class="single-project-delivery-days"> {{ __($project->basic_delivery) ?? 0 }} </span>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="profile-wrapper-item-bottom profile-border-top">
                    <div class="profile-wrapper-item-bottom-flex flex-between align-items-center">
                        @if($project->status === 1)
                            <div class="profile-wrapper-right-flex flex-btn order_availability_show_hide">
                                <span class="profile-wrapper-switch-title"> {{ __('Available for order') }} </span>
                                <div class="profile-wrapper-switch-custom display_availability_for_order_or_not_{{$project->id}}">
                                    <label class="custom_switch">
                                        <input type="checkbox" id="available_for_order_or_not" data-id="{{ $project->id }}" data-project_on_off="{{ $project->project_on_off }}" @if($project->project_on_off == 1)checked @endif>
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>
                        @else
                            <div class="flex-btn">
                                <x-status.table.active-inactive :status="$project->status"/>
                                @if($project->project_approve_request == 2)
                                    <span class="btn-profile btn-outline-1 mb-3 view_project_reject_reason_details"
                                          data-bs-target="#rejectProjectReason"
                                          data-bs-toggle="modal"
                                          data-project-reject-description="{{ $project?->project_history?->reject_reason ?? __('No Description')}}"
                                    >
                                          {{ __('View Reject Reason') }}
                                    </span>
                                @endif
                            </div>
                        @endif
                        <div class="profile-wrapper-item-btn flex-btn">
                            @if($project?->orders_count == 0)
                            <a href="javascript:void(0)" class="btn-profile btn-outline-cancel delete_project edit_info_show_hide" data-project-id="{{ $project->id }}"> {{__('Delete')}} </a>
                            @endif
                            @if(moduleExists('SecurityManage'))
                                @if(Auth::guard('web')->user()->freeze_project == 'freeze')
                                    <a href="#" class="btn-profile btn-bg-1 @if(Auth::guard('web')->user()->freeze_project == 'freeze') disabled-link @endif"> {{ __('Edit Project') }} </a>
                                @else
                                    <a href="{{ route('freelancer.project.edit',$project->id) }}" class="btn-profile btn-bg-1 edit_info_show_hide"> {{ __('Edit Project') }} </a>
                                @endif
                            @else
                               <a href="{{ route('freelancer.project.edit',$project->id) }}" class="btn-profile btn-bg-1 edit_info_show_hide"> {{ __('Edit Project') }} </a>
                            @endif

                            @if(moduleExists('PromoteFreelancer'))
                                @php
                                       $current_date = \Carbon\Carbon::now()->toDateTimeString();
                                       $is_promoted = \Modules\PromoteFreelancer\Entities\PromotionProjectList::where('identity',$project->id)->where('type','project')->where('expire_date','>',$current_date)->where('payment_status','complete')->first();
                                @endphp

                                @if(!empty($is_promoted))
                                    <button type="button" class="btn btn-outline-primary" disabled>{{ __('Promoted') }}</button>
                                @else
                                    <a href="javascript:void(0)"
                                       class="btn-profile btn-bg-1 open_project_promote_modal"
                                       data-bs-target="#openProjectPromoteModal"
                                       data-bs-toggle="modal"
                                       data-project-id="{{ $project->id }}">
                                        {{ __('Promote Project') }}
                                    </a>
                                @endif
                           @endif
                        </div>
                    </div>
                </div>
            </div>
        @elseif(Auth::guard('web')->check() && Auth::guard('web')->user()->user_type == 1 && Auth::guard('web')->user()->username==$username && Session::get('user_role') == 'freelancer')
            <div class="single-project project-catalogue">
            <div class="project-catalogue-flex">
                <div class="single-project-thumb project-catalogue-thumb">
                    <a href="{{ route('project.details', ['username' => $project->project_creator?->username, 'slug' => $project->slug]) }}">

                        @if(cloudStorageExist() && in_array(Storage::getDefaultDriver(), ['s3', 'cloudFlareR2', 'wasabi']))
                            <img src="{{ render_frontend_cloud_image_if_module_exists( 'project/'.$project->image, load_from: $project->load_from) }}" alt="project">
                        @else
                            <img src="{{url('assets/uploads/project/'.$project->image)}}" alt="project">
                        @endif

                    </a>
                </div>
                <div class="single-project-content project-catalogue-contents mt-0">
                    <div class="single-project-content-top align-items-center flex-between">
                        {!! project_rating($project->id) !!}
                    </div>
                    <h4 class="single-project-content-title">
                        <a href="{{ route('project.details',['username'=>$project->project_creator?->username,'slug'=>$project->slug]) }}"> {{$project->title}} </a>
                    </h4>

                    <div class="project-catalogue-bottom flex-between mt-3">
                        @if($project->basic_discount_charge != null && $project->basic_discount_charge > 0)
                            <span class="single-project-content-price"> {{ amount_with_currency_symbol($project->basic_discount_charge) ?? '' }} <s>{{ amount_with_currency_symbol($project->basic_regular_charge) ?? '' }}</s> </span>
                        @else
                            <span class="single-project-content-price"> {{ amount_with_currency_symbol($project->basic_regular_charge) ?? '' }}</span>
                        @endif
                        <div class="single-project-delivery">
                            <span class="single-project-delivery-icon">
                                <i class="fa-regular fa-clock"></i> {{ __('Delivery') }}
                            </span>
                            <span class="single-project-delivery-days"> {{ __($project->basic_delivery) ?? 0 }} </span>
                        </div>
                    </div>

                </div>
            </div>
            <div class="profile-wrapper-item-bottom profile-border-top">
                <div class="profile-wrapper-item-bottom-flex flex-between align-items-center">
                    @if($project->status === 1)
                        <div class="profile-wrapper-right-flex flex-btn order_availability_show_hide">
                            <span class="profile-wrapper-switch-title"> {{ __('Available for order') }} </span>
                            <div class="profile-wrapper-switch-custom display_availability_for_order_or_not_{{$project->id}}">
                                <label class="custom_switch">
                                    <input type="checkbox" id="available_for_order_or_not" data-id="{{ $project->id }}" data-project_on_off="{{ $project->project_on_off }}" @if($project->project_on_off == 1)checked @endif>
                                    <span class="slider round"></span>
                                </label>
                            </div>
                        </div>
                    @else
                        <div class="flex-btn">
                            <x-status.table.active-inactive :status="$project->status"/>
                            @if($project->project_approve_request == 2)
                                <span class="btn-profile btn-outline-1 mb-3 view_project_reject_reason_details"
                                      data-bs-target="#rejectProjectReason"
                                      data-bs-toggle="modal"
                                      data-project-reject-description="{{ $project?->project_history?->reject_reason ?? __('No Description')}}"
                                >
                                          {{ __('View Reject Reason') }}
                                    </span>
                            @endif
                        </div>
                    @endif
                    <div class="profile-wrapper-item-btn flex-btn">
                        @if($project?->orders_count == 0)
                            <a href="javascript:void(0)" class="btn-profile btn-outline-cancel delete_project edit_info_show_hide" data-project-id="{{ $project->id }}"> {{__('Delete')}} </a>
                        @endif
                        @if(moduleExists('SecurityManage'))
                            @if(Auth::guard('web')->user()->freeze_project == 'freeze')
                                <a href="#" class="btn-profile btn-bg-1 @if(Auth::guard('web')->user()->freeze_project == 'freeze') disabled-link @endif"> {{ __('Edit Project') }} </a>
                            @else
                                <a href="{{ route('freelancer.project.edit',$project->id) }}" class="btn-profile btn-bg-1 edit_info_show_hide"> {{ __('Edit Project') }} </a>
                            @endif
                        @else
                            <a href="{{ route('freelancer.project.edit',$project->id) }}" class="btn-profile btn-bg-1 edit_info_show_hide"> {{ __('Edit Project') }} </a>
                        @endif

{{--                        @if(moduleExists('PromoteFreelancer'))--}}
{{--                            @php--}}
{{--                                $current_date = \Carbon\Carbon::now()->toDateTimeString();--}}
{{--                                $is_promoted = \Modules\PromoteFreelancer\Entities\PromotionProjectList::where('identity',$project->id)->where('type','project')->where('expire_date','>',$current_date)->where('payment_status','complete')->first();--}}
{{--                            @endphp--}}

{{--                            @if(!empty($is_promoted))--}}
{{--                                <button type="button" class="btn btn-outline-primary" disabled>{{ __('Promoted') }}</button>--}}
{{--                            @else--}}
{{--                                <a href="javascript:void(0)"--}}
{{--                                   class="btn-profile btn-bg-1 open_project_promote_modal"--}}
{{--                                   data-bs-target="#openProjectPromoteModal"--}}
{{--                                   data-bs-toggle="modal"--}}
{{--                                   data-project-id="{{ $project->id }}">--}}
{{--                                    {{ __('Promote Project') }}--}}
{{--                                </a>--}}
{{--                            @endif--}}
{{--                        @endif--}}
                    </div>
                </div>
            </div>
        </div>
        @else
            @if($project->project_on_off == 1 && $project->status == 1 && $project->project_approve_request == 1)
                <div class="single-project project-catalogue">
                <div class="project-catalogue-flex">
                    <div class="single-project-thumb project-catalogue-thumb">
                        <a href="{{ route('project.details',['username'=>$project->project_creator?->username,'slug'=>$project->slug]) }}">
                            @if(cloudStorageExist() && in_array(Storage::getDefaultDriver(), ['s3', 'cloudFlareR2', 'wasabi']))
                                <img src="{{ render_frontend_cloud_image_if_module_exists( 'project/'.$project->image, load_from: $project->load_from) }}" alt="project">
                            @else
                                <img src="{{url('assets/uploads/project/'.$project->image)}}" alt="project">
                            @endif
                        </a>
                    </div>
                    <div class="single-project-content project-catalogue-contents mt-0">
                        <h4 class="single-project-content-title">
                            <a href="{{ route('project.details',['username'=>$project->project_creator?->username,'slug'=>$project->slug]) }}"> {{$project->title}} </a>
                        </h4>

                        <div class="project-catalogue-bottom flex-between mt-3">
                            @if($project->basic_discount_charge != null && $project->basic_discount_charge > 0)
                                <span class="single-project-content-price"> {{ amount_with_currency_symbol($project->basic_discount_charge) ?? '' }} <s>{{ amount_with_currency_symbol($project->basic_regular_charge) ?? '' }}</s> </span>
                            @else
                                <span class="single-project-content-price"> {{ amount_with_currency_symbol($project->basic_regular_charge) ?? '' }}</span>
                            @endif
                            <div class="single-project-delivery">
                            <span class="single-project-delivery-icon">
                                <i class="fa-regular fa-clock"></i> {{ __('Delivery') }}
                            </span>
                                <span class="single-project-delivery-days"> {{ __($project->basic_delivery) ?? 0 }}</span>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            @endif
        @endif
    @endforeach
    @include('frontend.profile-details.project-reject-reason')
    @if(moduleExists('PromoteFreelancer'))
    @include('frontend.profile-details.promotion.project-promote-modal')
    @endif
</div>
