<div class="row g-4">
    @if($blogs->count() > 0)
        @foreach($blogs as $blog)
            <div class="col-xxl-6">
                <div class="project-category-item radius-10">
                    <div class="single-project project-catalogue">
                        <div class="single-project-thumb">
                            <a href="{{ route('blog.details',$blog->slug) }}">
                                {!! render_image_markup_by_attachment_id($blog->image) !!}
                            </a>
                        </div>
                        <div class="single-project-content">
                            <h4 class="single-project-content-title">
                                <a href="{{ route('blog.details',$blog->slug) }}"> {{ $blog->title }} </a>
                            </h4>
                        </div>
                        <div class="project-category-item-bottom profile-border-top">
                            <div class="project-category-item-bottom-flex flex-between align-items-center">
                                <div class="project-category-right-flex flex-btn">
                                    <p>{{ $blog->created_at->toFormattedDateString() }}</p>
                                </div>
                                <div class="project-category-item-btn flex-btn">
                                    <a href="{{ route('blog.details',$blog->slug) }}" class="btn-profile btn-outline-1"> {{ __('View Details') }} </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @else
        <div class="col-12">
            <h4 class="text-danger text-center">{{ __('No Blogs Found') }}</h4>
        </div>
    @endif
</div>
<x-pagination.laravel-paginate :allData="$blogs"/>