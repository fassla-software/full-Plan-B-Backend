<!-- Setup Work Start -->
<div class="setup-wrapper-contents">
    <div class="setup-wrapper-contents-item">
        <h3 class="setup-wrapper-contents-title">{{ get_static_option('work_title') ?? __('What kinds of services will you provide to clients?(Work)') }}</h3>
        <div class="setup-wrapper-work">
            <div class="row g-4">
                <input type="hidden" id="set_category_id" @if(!empty($user_work)) value="{{ $user_work->category_id }}" @else value="" @endif>
                <input type="hidden" id="set_sub_category_id" @if(!empty($user_work)) value="{{ $user_work->sub_category_id }}" @else value="" @endif>
                @foreach($categories as $cat)
                <div class="col-md-4 col-sm-6 setup-work-child work_category_id">
                    <input type="hidden" value="{{ $cat->id }}">
                    <div class="setup-wrapper-work-single center-text @if(!empty($user_work)) @if($cat->id == $user_work->category_id) active @endif @endif">
                        <div class="setup-wrapper-work-single-icon">
                            {!! render_image_markup_by_attachment_id($cat->image) !!}
                        </div>
                        <h4 class="setup-wrapper-work-single-title"> <a href="javascript:void(0)">{{ $cat->category }}</a> </h4>
                    </div>
                </div>
                @endforeach
                <div class="col-md-4 col-sm-6 setup-work-child">
                    <div class="setup-wrapper-work-single center-text work-click">
                        <div class="setup-wrapper-work-single-icon">
                            <h4>+{{ $more_categories->count() ?? '' }}</h4>
                        </div>
                        <h4 class="setup-wrapper-work-single-title"> {{ __('Categories to choose from') }} </h4>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <div class="setup-wrapper-contents-item">
        <h3 class="setup-wrapper-contents-title-two"> {{ get_static_option('work_inner_title') ?? __('Choose, what would you do?') }} </h3>
        <ul class="setup-wrapper-work-list get_subcategory overflow-auto set_scroll_height">
            @if(!empty($user_work->category_id))
                <?php $sub_categories  = \Modules\Service\Entities\SubCategory::where('category_id',$user_work->category_id)->get();?>
                @foreach($sub_categories as $sub_cat)
                    <li class="setup-wrapper-work-list-item get_subcategory choose_a_subcategory @if($user_work->sub_category_id === $sub_cat->id && $user_work->user_id === $user_id) active @endif" data-id="{{ $sub_cat->id }}">{{ $sub_cat->sub_category }}</li>
                @endforeach
            @else
                <span class="choose_a_subcategory"></span>
            @endif
        </ul>
    </div>
</div>

<!-- Popup Modal Work area starts -->
<div class="popup-fixed work-popup">
    <div class="popup-contents">
        <span class="popup-contents-close popup-close"> <i class="fas fa-times"></i> </span>
        <h2 class="popup-contents-title">{{ get_static_option('work_modal_title') ?? __('Choose a service') }}</h2>
        <p class="popup-contents-para">{{ __('Choose what kinds of services will you provide to clients?') }}</p>
        <div class="popup-contents-form custom-form">
            <form action="#">
                <div class="single-input single-input-icon">
                    <input type="text" class="form--control" id="category_search_string" placeholder="{{ __('Search service') }}">
                    <span class="input-icon"> <i class="fas fa-search"></i> </span>
                </div>
            </form>
        </div>
        <div class="row g-4 mt-2 search_result">
            @include('frontend.user.freelancer.account.work.search-categories')
        </div>
        <div class="popup-contents-btn flex-btn justify-content-end profile-border-top">
            <a href="javascript:void(0)" class="btn-profile btn-outline-gray btn-hover-danger popup-close"> <i class="las la-arrow-left"></i>{{ __('Cancel') }}</a>
        </div>
    </div>
</div>
<!-- Popup Modal area ends -->
<!-- Setup Work Ends -->
