<div class="login-right-item">
    <div class="login-right-shapes">
        <div class="login-right-thumb">
            <?php if(empty(get_static_option('register_page_sidebar_image'))): ?>
                <img src="<?php echo e(asset('assets/static/single-page/fr_1.png')); ?>" alt="loginImg">
            <?php else: ?>
                <?php echo render_image_markup_by_attachment_id(get_static_option('register_page_sidebar_image')); ?>

            <?php endif; ?>
        </div>
    </div>
    <div class="login-right-contents text-white">
        <h4 class="login-right-contents-title"> <?php echo e(get_static_option('register_page_sidebar_title') ?? __('Register and start discover')); ?> </h4>
        <p class="login-right-contents-para"><?php echo e(get_static_option('register_page_sidebar_description') ?? __('Once register you will see the magic of xilancer marketplace.')); ?></p>
    </div>
</div>
<?php /**PATH D:\laravel_workspace\Fassla\planb\core\resources\views/components/frontend/register/user-register-slider.blade.php ENDPATH**/ ?>