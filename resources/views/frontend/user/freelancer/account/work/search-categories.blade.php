@foreach($more_categories as $more_cat)
    <div class="col-lg-4 col-sm-6 setup-work-child work_category_id">
        <input type="hidden" value="{{ $more_cat->id }}">
        <div class="setup-wrapper-work-single center-text @if(!empty($user_work)) @if($cat->id == $user_work->category_id) active @endif @endif">
            <div class="setup-wrapper-work-single-icon">
                {!! render_image_markup_by_attachment_id($more_cat->image) !!}
            </div>
            <h4 class="setup-wrapper-work-single-title"> <a href="javascript:void(0)">{{ $more_cat->category }}</a> </h4>
        </div>
    </div>
@endforeach
