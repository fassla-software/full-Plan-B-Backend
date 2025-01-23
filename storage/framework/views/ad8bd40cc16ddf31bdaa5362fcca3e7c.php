<?php if(get_static_option('project_enable_disable') != 'disable'): ?>
    <!-- Project area starts -->
    <section class="project-area pat-50 pab-50" data-padding-top="<?php echo e($padding_top ?? ''); ?>" data-padding-bottom="<?php echo e($padding_bottom ?? ''); ?>" style="background-color:<?php echo e($section_bg ?? ''); ?>">
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
                <div class="append-project"></div>
            </div>

            <div class="row mt-5">
                <div class="col-12">
                    <div class="global-slick-init project-slider nav-style-one slider-inner-margin" data-rtl="<?php echo e(get_user_lang_direction() == 'rtl' ? 'true' : 'false'); ?>" data-appendArrows=".append-project" data-arrows="true" data-infinite="true" data-dots="false" data-slidesToShow="3" data-swipeToSlide="true" data-autoplaySpeed="2500"
                         data-prevArrow='<div class="prev-icon"><i class="fa-solid fa-arrow-left"></i></div>'
                         data-nextArrow='<div class="next-icon"><i class="fa-solid fa-arrow-right"></i></div>'
                         data-responsive='[{"breakpoint": 1400,"settings": {"slidesToShow": 3}},{"breakpoint": 1200,"settings": {"slidesToShow": 2}},{"breakpoint": 992,"settings": {"slidesToShow": 2}},{"breakpoint": 768,"settings": {"slidesToShow": 1}},{"breakpoint": 576, "settings": {"slidesToShow": 1} }]'>
                        <?php if($items <= 1): ?>
                            <?php $__currentLoopData = $explore_projects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $project): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="project-item wow fadeInUp" data-wow-delay=".1s">
                                            <div class="single-project radius-10">
                                                <div class="single-project-thumb">
                                                    <a href="<?php echo e(route('project.details', ['username' => $project->project_creator?->username, 'slug' => $project->slug])); ?>">
                                                        <?php if(cloudStorageExist() && in_array(Storage::getDefaultDriver(), ['s3', 'cloudFlareR2', 'wasabi'])): ?>
                                                            <img src="<?php echo e(render_frontend_cloud_image_if_module_exists( 'project/'.$project->image, load_from: $project->load_from)); ?>" alt="<?php echo e($project->title ?? ''); ?>">
                                                        <?php else: ?>
                                                            <img src="<?php echo e(asset('assets/uploads/project/'.$project->image) ?? ''); ?>" alt="<?php echo e($project->title ?? ''); ?>">
                                                        <?php endif; ?>
                                                    </a>
                                                </div>
                                                <div class="single-project-content">

                                                    <div class="single-project-content-top align-items-center flex-between">
                                                        <?php echo project_rating($project->id); ?>

                                                    </div>

                                                    <h4 class="single-project-content-title"> <a href="<?php echo e(route('project.details', ['username' => $project->project_creator?->username, 'slug' => $project->slug])); ?>"><?php echo e($project->title); ?> </a> </h4>
                                                </div>
                                                <div class="single-project-bottom flex-between">
                                                    <span class="single-project-content-price">
                                                        <?php if($project->basic_discount_charge): ?>
                                                            <?php echo e(float_amount_with_currency_symbol($project->basic_discount_charge)); ?>

                                                            <s><?php echo e(float_amount_with_currency_symbol($project->basic_regular_charge)); ?></s>
                                                        <?php else: ?>
                                                            <?php echo e(float_amount_with_currency_symbol($project->basic_regular_charge)); ?>

                                                        <?php endif; ?>
                                                    </span>
                                                    <div class="single-project-delivery">
                                                        <span class="single-project-delivery-icon"> <i class="fa-regular fa-clock"></i> <?php echo e(__('Delivery')); ?> </span>
                                                        <span class="single-project-delivery-days"> <?php echo e($project->basic_delivery); ?>  </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php else: ?>
                            <?php $__currentLoopData = $explore_projects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $project): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="project-item wow fadeInUp" data-wow-delay=".1s">
                                    <div class="single-project radius-10">
                                        <div class="single-project-thumb">
                                            <a href="<?php echo e(route('project.details', ['username' => $project->project_creator?->username, 'slug' => $project->slug])); ?>">
                                                <?php if(cloudStorageExist() && in_array(Storage::getDefaultDriver(), ['s3', 'cloudFlareR2', 'wasabi'])): ?>
                                                    <img src="<?php echo e(render_frontend_cloud_image_if_module_exists( 'project/'.$project->image, load_from: $project->load_from)); ?>" alt="<?php echo e($project->title ?? ''); ?>">
                                                <?php else: ?>
                                                    <img src="<?php echo e(asset('assets/uploads/project/'.$project->image) ?? ''); ?>" alt="<?php echo e($project->title ?? ''); ?>">
                                                <?php endif; ?>
                                            </a>
                                        </div>
                                        <div class="single-project-content">

                                            <div class="single-project-content-top align-items-center flex-between">
                                                <?php echo project_rating($project->id); ?>

                                            </div>

                                            <h4 class="single-project-content-title"> <a href="<?php echo e(route('project.details', ['username' => $project->project_creator?->username, 'slug' => $project->slug])); ?>"><?php echo e($project->title); ?> </a> </h4>
                                        </div>
                                        <div class="single-project-bottom flex-between">
                                <span class="single-project-content-price">
                                    <?php if($project->basic_discount_charge): ?>
                                        <?php echo e(float_amount_with_currency_symbol($project->basic_discount_charge)); ?>

                                        <s><?php echo e(float_amount_with_currency_symbol($project->basic_regular_charge)); ?></s>
                                    <?php else: ?>
                                        <?php echo e(float_amount_with_currency_symbol($project->basic_regular_charge)); ?>

                                    <?php endif; ?>
                                </span>
                                            <div class="single-project-delivery">
                                                <span class="single-project-delivery-icon"> <i class="fa-regular fa-clock"></i> <?php echo e(__('Delivery')); ?> </span>
                                                <span class="single-project-delivery-days"> <?php echo e($project->basic_delivery); ?>  </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Project area end -->
<?php endif; ?><?php /**PATH D:\laravel_workspace\Fassla\planb\core\app\Providers/../../plugins/PageBuilder/views/projects/explore-category-projects.blade.php ENDPATH**/ ?>