<!-- Work area starts -->
<section class="work-area pat-100 pab-50" data-padding-top="<?php echo e($padding_top ?? ''); ?>" data-padding-bottom="<?php echo e($padding_bottom ?? ''); ?>" style="background-color:<?php echo e($section_bg ?? ''); ?>">
    <div class="container">
        <div class="section-title center-text">
            <h2 class="title"> <?php echo e($title ?? __('Why work in our platform?')); ?>  </h2>
        </div>
        <div class="row gy-4 mt-5">
            <?php $__currentLoopData = $repeater_data['image_']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-xl-3 col-lg-4 col-sm-6 wow fadeInRight" data-wow-delay=".2s">
                <div class="single-work single-work-border radius-10">
                    <div class="single-work-icon">
                        <?php echo render_image_markup_by_attachment_id($data); ?>

                    </div>
                    <div class="single-work-contents mt-3">
                        <h4 class="single-work-contents-title"> <a href="#/"><?php echo e($repeater_data['title_'][$key]); ?></a> </h4>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</section>
<!-- Work area end --><?php /**PATH D:\laravel_workspace\Fassla\planb\core\app\Providers/../../plugins/PageBuilder/views/work/why-work-with-us.blade.php ENDPATH**/ ?>