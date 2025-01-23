<!-- Banner area Starts -->
<div class="banner-area banner-area-three section-bg-gradient" data-padding-top="<?php echo e($padding_top ?? ''); ?>" data-padding-bottom="<?php echo e($padding_bottom ?? ''); ?>">
    <div class="banner-shapes-bg">
        <?php echo render_image_markup_by_attachment_id($background_image); ?>

    </div>
    <div class="container">
        <div class="row gy-4 justify-content-center align-items-center">
            <div class="col-lg-8">
                <div class="banner-single">
                    <div class="banner-single-content center-text">
                        <h1 class="banner-single-content-title"><?php echo e($title); ?></h1>
                        <p class="banner-single-content-para"><?php echo e($subtitle); ?></p>
                        <div class="btn-wrapper flex-btn mt-5">
                                <a href="<?php echo e($find_work_button_link  ?? ''); ?>" class="cmn-btn btn-bg-secondary"> <?php echo e($find_work_button_text ?? __('Find Work')); ?> </a>
                                <a href="<?php echo e($find_project_button_link ?? ''); ?>" class="cmn-btn btn-outline-1 color-one"> <?php echo e($find_project_button_text ?? __('Find Project')); ?> </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="banner-wrapper">
                    <div class="banner-wrapper-left">
                        <div class="banner-wrapper-thumb">
                            <?php if($freelancer_image): ?>
                                <?php echo render_image_markup_by_attachment_id($freelancer_image); ?>

                            <?php else: ?>
                                <img src="<?php echo e(asset('assets/static/img/freelancer.png')); ?>" alt="<?php echo e(__('Freelancer Image')); ?>">
                            <?php endif; ?>
                        </div>
                        <div class="banner-wrapper-project">
                            <div class="banner-wrapper-project-flex">
                                <div class="banner-wrapper-project-icon">
                                    <?php echo render_image_markup_by_attachment_id($light_image); ?>

                                </div>
                                <div class="banner-wrapper-project-content">
                                    <?php if($freelancer_order_count > 0): ?>
                                        <span class="banner-wrapper-project-content-title"> <?php echo e(__('Completed')); ?>

                                            <strong><?php echo e($freelancer_order_count); ?></strong> <?php echo e(__('Projects')); ?>

                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="banner-wrapper-right">
                        <div class="banner-wrapper-thumb">
                            <?php if($client_image): ?>
                                <?php echo render_image_markup_by_attachment_id($client_image); ?>

                            <?php else: ?>
                                <img src="<?php echo e(asset('assets/static/img/client.png')); ?>" alt="<?php echo e(__('Client Image')); ?>">
                            <?php endif; ?>
                        </div>
                        <div class="banner-wrapper-project">
                            <div class="banner-wrapper-project-flex">
                                <div class="banner-wrapper-project-icon">
                                    <?php echo render_image_markup_by_attachment_id($talent_image); ?>


                                </div>
                                <div class="banner-wrapper-project-content">
                                    <?php if($client_order_count > 0): ?>
                                        <span class="banner-wrapper-project-content-title"><?php echo e(__('Hired')); ?>

                                            <strong><?php echo e($client_order_count); ?>+</strong>
                                            <?php echo e(__('Talents')); ?>

                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="banner-wrapper-line">
                        <div class="banner-wrapper-line-shape">
                            <?php echo render_image_markup_by_attachment_id($shape_image_one); ?>

                        </div>
                        <div class="banner-wrapper-line-fav">
                            <?php echo render_image_markup_by_attachment_id($shape_image_two); ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Banner area end --><?php /**PATH D:\laravel_workspace\Fassla\planb\core\app\Providers/../../plugins/PageBuilder/views/header/header-two.blade.php ENDPATH**/ ?>