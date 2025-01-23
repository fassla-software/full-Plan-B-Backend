<?php
//$project_complete_orders = \App\Models\Order::select('id','identity','status','is_project_job')->where('identity',$project_id)
//    ->whereHas('rating')
//    ->where('status',3)
//    ->where('is_project_job','project')
////    ->paginate($pagination_limit);
//    ->paginate(1);
//?><!---->
@foreach($project_complete_orders as $order)
    @php $rating = \App\Models\Rating::with('order')->where('order_id',$order->id)->where('sender_type',1)->first(); @endphp
    @if($rating)
        @php $fullname = $rating->order?->user?->fullname; @endphp
        <div class="project-feedback profile-border-bottom">
            <div class="project-feedback-flex">
                <div class="project-feedback-thumb">
                    @if($rating->order?->user?->image)
                        <img src="{{ asset('assets/uploads/profile/'.$rating->order?->user?->image) }}" alt="">
                    @else
                        <img src="{{ asset('assets/static/img/author/author.jpg') }}" alt="{{ __('author') }}">
                     @endif
                </div>
                <div class="project-feedback-contents">
                    <div class="project-feedback-contents-flex">
                        <div class="project-feedback-contents-name">
                            <h4 class="project-feedback-contents-title">{{ $fullname}}</h4>
                            @if($rating->order?->user?->user_state?->state)
                                <p class="project-feedback-contents-subtitle mt-2"><span>{{ $rating->order?->user?->user_state?->state }}, {{ $rating->order?->user?->user_country?->country }}</span> </p>
                            @else
                                <p class="project-feedback-contents-subtitle mt-2"><span>{{ $rating->order?->user?->user_country?->country }}</span> </p>
                            @endif
                        </div>
                        <div class="project-feedback-contents-right">
                            <p class="project-feedback-contents-time">{{ $rating->created_at->toFormattedDateString() ?? '' }}</p>
                        </div>
                    </div>
                    <div class="project-feedback-contents-review mt-2">
                        <div class="rating_profile_details">
                            <div class="rating_profile_details_icon">
                                <i data-star="{{ $rating->rating }}"></i>
                            </div>
                            <span class="rating_profile_details-para">{{ $rating->rating }}</span>
                        </div>
                    </div>
                    <p class="project-feedback-contents-para mt-3">{{ $rating->review_feedback }}</p>
                </div>
            </div>
        </div>
    @endif
@endforeach
<x-pagination.laravel-paginate :allData="$project_complete_orders"/>


