<?php if(get_static_option('job_enable_disable') != 'disable'): ?>
<!-- Jobs area starts -->
<section class="jobs-area pat-50 pab-100" data-padding-top="<?php echo e($padding_top ?? ''); ?>" data-padding-bottom="<?php echo e($padding_bottom ?? ''); ?>" style="background-color:<?php echo e($section_bg ?? ''); ?>">
    <div class="container">

        <div class="section-title text-left append-flex">
            <div class="d-flex w-100 justify-content-between">
                <h2 class="subtitle"> <?php echo e($title ?? ''); ?></h2>
                <div>
                    <?php if(!empty($view_all_link) && !empty($view_all_link)): ?>
                        <a href="<?php echo e($view_all_link); ?>" class="view-all-projects-jobbs"><?php echo e($view_all_text); ?></a>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="d-flex w-100 justify-content-between">
            <div class="section-title text-left mx-0">
                <div class="title">
                    <span><?php echo e($category->category ?? ''); ?></span>
                </div>
            </div>
            <div class="append-jobs"></div>
        </div>

        <div class="row mt-5">
            <div class="col-12">
                <div class="global-slick-init attraction-slider nav-style-one slider-inner-margin"
                     data-rtl="<?php echo e(get_user_lang_direction() == 'rtl' ? 'true' : 'false'); ?>"
                    data-appendArrows=".append-jobs" data-arrows="true" data-infinite="true" data-dots="false"
                    data-slidesToShow="3" data-swipeToSlide="true" data-autoplay="false" data-autoplaySpeed="2500"
                    data-prevArrow='<div class="prev-icon"><i class="fa-solid fa-arrow-left"></i></div>'
                    data-nextArrow='<div class="next-icon"><i class="fa-solid fa-arrow-right"></i></div>'
                    data-responsive='[{"breakpoint": 1400,"settings": {"slidesToShow": 3}},{"breakpoint": 1200,"settings": {"slidesToShow": 2}},{"breakpoint": 992,"settings": {"slidesToShow": 2}},{"breakpoint": 768, "settings": {"slidesToShow": 1} }]'>

                    <?php $__currentLoopData = $explore_jobs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $job): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="jobs-item">
                            <div class="single-jobs radius-10">
                                <h4 class="single-jobs-title"> <a
                                        href="<?php echo e(route('job.details', ['username' => $job->job_creator?->username, 'slug' => $job->slug])); ?>">
                                        <?php echo e($job->title); ?> </a> </h4>
                                <p class="single-jobs-date">
                                    <?php echo e($job->created_at->toFormattedDateString() ?? ''); ?> -
                                    <span><?php echo e(ucfirst($job->level) ?? ''); ?></span>
                                </p>

                                <h3 class="single-jobs-price">
                                    <?php echo e(float_amount_with_currency_symbol($job->budget)); ?>

                                    <span class="single-jobs-price-fixed"><?php echo e(__(ucfirst($job->type))); ?></span>
                                </h3>
                                <p class="single-jobs-para mt-4">
                                    <?php echo Str::limit(strip_tags($job->description), 90); ?> </p>
                                <div class="single-jobs-tag mt-4">
                                    <?php $__currentLoopData = $job->job_skills; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $skill): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <a href="<?php echo e(route('skill.jobs', $skill->skill)); ?>" class="single-jobs-tag-link">
                                            <?php echo e($skill->skill ?? ''); ?> </a>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                </div>
            </div>
        </div>
    </div>
</section>
<!-- Jobs area end -->
<?php endif; ?>
<?php /**PATH D:\laravel_workspace\Fassla\planb\core\app\Providers/../../plugins/PageBuilder/views/jobs/explore-category-jobs.blade.php ENDPATH**/ ?>