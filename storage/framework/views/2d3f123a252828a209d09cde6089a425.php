<?php if(get_static_option('project_enable_disable') != 'disable'): ?>
<!-- Category area starts -->
<section class="category-area pat-50 pab-50" data-padding-top="<?php echo e($padding_top ?? ''); ?>" data-padding-bottom="<?php echo e($padding_bottom ?? ''); ?>" style="background-color:<?php echo e($section_bg ?? ''); ?>">
    <div class="container">
        <div class="section-title center-text">
            <h2 class="title"> <?php echo e($title ?? __('Browse Projects By Categories')); ?> </h2>
        </div>
        <div class="row gy-4 mt-4">
            <div class="col-lg-12">
                <div class="global-slick-init nav-style-one slider-inner-margin" data-rtl="<?php echo e(get_user_lang_direction() == 'rtl' ? 'true' : 'false'); ?>" data-appendArrows=".append-projectCategory" data-slidesToShow="6" data-infinite="true" data-arrows="true" data-dots="false" data-swipeToSlide="true" data-autoplay="true" data-autoplaySpeed="2500" data-prevArrow='<div class="prev-icon"><i class="fas fa-arrow-left"></i></div>'
                     data-nextArrow='<div class="next-icon"><i class="fas fa-arrow-right"></i></div>' data-responsive='[{"breakpoint": 1400,"settings": {"slidesToShow": 5}},{"breakpoint": 1200,"settings": {"slidesToShow": 4}},{"breakpoint": 992,"settings": {"slidesToShow": 3}},{"breakpoint": 768,"settings": {"slidesToShow": 2}},{"breakpoint": 480, "settings": {"slidesToShow": 1} }]'>
                    <?php if($items <= 1): ?>
                        <?php $__currentLoopData = $project_categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="row">
                                <div class="col-lg-2">
                                    <div class="category-slider-item">
                                        <a href="<?php echo e(route('category.projects',$category->slug)); ?>">
                                            <div class="single-category center-text radius-10">
                                                <div class="single-category-icon">
                                                    <?php echo render_image_markup_by_attachment_id($category->image); ?>

                                                </div>
                                                <div class="single-category-contents">
                                                    <h5 class="single-category-contents-title"> <?php echo e($category->category ?? ''); ?> </h5>
                                                    <span class="single-category-contents-subtitle"> <?php echo e($category->projects_count ?? ''); ?> <?php echo e(__('Projects')); ?> </span>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php else: ?>
                        <?php $__currentLoopData = $project_categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="category-slider-item">
                                <a href="<?php echo e(route('category.projects',$category->slug)); ?>">
                                    <div class="single-category center-text radius-10">
                                        <div class="single-category-icon">
                                            <?php echo render_image_markup_by_attachment_id($category->image); ?>

                                        </div>
                                        <div class="single-category-contents">
                                            <h5 class="single-category-contents-title"> <?php echo e($category->category ?? ''); ?> </h5>
                                            <span class="single-category-contents-subtitle"> <?php echo e($category->projects_count ?? ''); ?> <?php echo e(__('Projects')); ?> </span>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php if($project_categories->count() > 0): ?>
        <div class="row mt-5 appendSliderWrapper">
            <div class="testimonial-arrows center-text">
                <div class="append-projectCategory"> <span> <?php echo e($slider_button_text ?? __('Swipe')); ?> </span> </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</section>
<!-- Category area end -->
<?php endif; ?><?php /**PATH D:\laravel_workspace\Fassla\planb\core\app\Providers/../../plugins/PageBuilder/views/categories/category-projects-one.blade.php ENDPATH**/ ?>