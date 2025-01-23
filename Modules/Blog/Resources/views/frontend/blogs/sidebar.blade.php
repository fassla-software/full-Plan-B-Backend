<div class="single-shop-left bg-white radius-10">
    <div class="single-shop-left-title open">
        <h5 class="title blog-category-title"> {{ __('Categories') }} ({{ $categories->count() }})</h5>
        <div class="single-shop-left-inner mt-4">
            <div class="single-shop-left-select">
                <a href="" data-blog-category="all" class="jobFilter-about-clients filter_blog active">
                    <div class="jobFilter-about-clients-single flex-between">
                        <span class="jobFilter-about-clients-para">{{ __('All') }}</span>
                        <span class="jobFilter-about-clients-completed">({{ $blogs->total() }})</span>
                    </div>
                </a>
                @foreach($categories as $category)
                    <a href="" data-blog-category="{{ $category->id }}" class="jobFilter-about-clients filter_blog">
                        <div  class="jobFilter-about-clients-single flex-between">
                            <span class="jobFilter-about-clients-para">{{ $category->category }}</span>
                            <span class="jobFilter-about-clients-completed">({{ $category->blogs_count }})</span>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</div>

