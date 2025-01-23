<!-- Choose area starts -->
<section class="choose-area" data-padding-top="<?php echo e($padding_top ?? ''); ?>" data-padding-bottom="<?php echo e($padding_bottom ?? ''); ?>">
    <div class="container">
        <div class="row gy-5">
            <div class="col-lg-6">
                <div class="choose-contents">
                    <div class="section-title">
                        <div class="subtitle"> <span> <?php echo e($subtitle); ?> </span> </div>
                        <h2 class="title"> <?php echo e($title); ?></h2>
                        <p class="section-para"><?php echo e($mini_description); ?></p>
                    </div>
                    <ul class="choose-contents-list mt-4">
                        <?php $__currentLoopData = $repeater_data['title_']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li class="choose-contents-list-item"><?php echo e($repeater_data['title_'][$key]); ?> </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="choose-wrapper">
                    <?php if($shape_image_one): ?>
                    <div class="choose-wrapper-thumb-shapes">
                        <?php echo render_image_markup_by_attachment_id($shape_image_one); ?>

                    </div>
                    <?php endif; ?>
                    <?php if($thumbnail_image): ?>
                    <div class="choose-wrapper-thumb">
                        <?php echo render_image_markup_by_attachment_id($thumbnail_image); ?>

                    </div>
                    <?php endif; ?>
                    <?php if($shape_image_two): ?>
                    <div class="choose-wrapper-shapes">
                        <?php echo render_image_markup_by_attachment_id($shape_image_two); ?>

                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Choose area ends --><?php /**PATH D:\laravel_workspace\Fassla\planb\core\app\Providers/../../plugins/PageBuilder/views/why-choose-us/why-choose-us.blade.php ENDPATH**/ ?>