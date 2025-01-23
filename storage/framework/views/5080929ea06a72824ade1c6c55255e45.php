<!-- appStore area starts -->
<section class="appStore-area pat-100 pab-100">
    <div class="container">
        <div class="row gy-5 justify-content-center">
            <div class="col-xl-6 col-lg-9">
                <div class="single-appStore">
                    <div class="single-appStore-flex">
                        <div class="single-appStore-contents">
                            <?php if($free_app_store_title): ?>
                            <h3 class="single-appStore-contents-title">
                                <a href="javascript:void(0)"><?php echo e($free_app_store_title); ?></a>
                            </h3>
                            <?php endif; ?>
                            <div class="single-appStore-btn flex-btn gap-2 mt-4">
                                <?php if($free_app_store_image): ?>
                                <a href="<?php echo e($free_app_store_link); ?>">
                                    <?php echo render_image_markup_by_attachment_id($free_app_store_image); ?>

                                </a>
                                <?php endif; ?>
                                <?php if($free_app_play_store_image): ?>
                                <a href="<?php echo e($free_app_play_store_link); ?>">
                                    <?php echo render_image_markup_by_attachment_id($free_app_play_store_image); ?>

                                </a>
                                <?php endif; ?>
                            </div>
                            <?php if($free_app_store_shape): ?>
                                <div class="single-appStore-shapes">
                                    <?php echo render_image_markup_by_attachment_id($free_app_store_shape); ?>

                                </div>
                            <?php endif; ?>
                        </div>
                        <?php if($free_app_store_phone): ?>
                            <div class="single-appStore-thumb wow fadeInUp" data-wow-delay=".2s">
                                <?php echo render_image_markup_by_attachment_id($free_app_store_phone); ?>

                            </div>
                       <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="col-xl-6 col-lg-9">
                <div class="single-appStore">
                    <div class="single-appStore-flex">
                        <div class="single-appStore-contents">
                            <?php if($client_app_store_title): ?>
                            <h3 class="single-appStore-contents-title">
                                <a href="javascript:void(0)"><?php echo e($client_app_store_title); ?></a>
                            <?php endif; ?>
                            <div class="single-appStore-btn flex-btn gap-2 mt-4">
                                <?php if($client_app_store_image): ?>
                                <a href="<?php echo e($client_app_store_link); ?>">
                                    <?php echo render_image_markup_by_attachment_id($client_app_store_image); ?>

                                </a>
                                <?php endif; ?>
                                <?php if($client_app_play_store_image): ?>
                                <a href="<?php echo e($client_app_play_store_link); ?>">
                                    <?php echo render_image_markup_by_attachment_id($client_app_play_store_image); ?>

                                </a>
                                <?php endif; ?>
                            </div>
                            <?php if($client_app_store_shape): ?>
                                <div class="single-appStore-shapes">
                                    <?php echo render_image_markup_by_attachment_id($client_app_store_shape); ?>

                                </div>
                            <?php endif; ?>
                        </div>
                        <?php if($client_app_store_phone): ?>
                            <div class="single-appStore-thumb wow fadeInUp" data-wow-delay=".5s">
                                <?php echo render_image_markup_by_attachment_id($client_app_store_phone); ?>

                            </div>
                         <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- appStore area end --><?php /**PATH D:\laravel_workspace\Fassla\planb\core\app\Providers/../../plugins/PageBuilder/views/mobiapplica/mobiapplica.blade.php ENDPATH**/ ?>