<!DOCTYPE html>
<html lang="<?php echo e(get_default_language()); ?>" dir="<?php echo e(get_user_lang_direction()); ?>">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="description" content="<?php echo e(get_static_option('site_meta_description')); ?>">
    <meta name="tags" content="<?php echo e(get_static_option('site_meta_tags')); ?>">
    <?php
        $site_favicon = get_attachment_image_by_id(get_static_option('site_favicon'),"full",false);
    ?>
    <?php if(!empty($site_favicon)): ?>
        <link rel="icon" href="<?php echo e($site_favicon['img_url'] ?? ''); ?>" sizes="40x40" type="icon/png">
    <?php endif; ?>
    <?php echo load_google_fonts(); ?>

    <link rel="stylesheet" href="<?php echo e(asset('assets/common/css/bootstrap.min.css')); ?>">
    <style>
        .maintenance-page-content-area {
            width: 100%;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
            z-index: 0;
            background-size: cover;
            background-position: center;
        }
        .maintenance-page-content-area:after {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            content: '';
        }
        .page-content-wrap {
            text-align: center;
        }
        .page-content-wrap .logo-wrap {
            margin-bottom: 10px;
        }
        .page-content-wrap img {
            max-width: 100%;
        }
        .page-content-wrap-contents {
            max-width: 500px;
            margin-inline: auto;
        }
        .page-content-wrap .maintain-title {
            font-size: 24px;
            font-weight: 700;
            line-height: 32px;
            margin-bottom: 20px;
        }
        .page-content-wrap p {
            font-size: 16px;
            line-height: 28px;
            color: rgba(255, 255, 255, .7);
            font-weight: 400;
        }
        @media screen and (max-width: 480px) {
            .page-content-wrap .maintain-title {
                font-size: 20px;
                line-height: 28px;
            }
        }
        @media screen and (max-width: 375px) {
            .page-content-wrap .maintain-title {
                font-size: 18px;
            }
        }
    </style>
    <?php echo $__env->yieldContent('style'); ?>
</head>
<body>

<div class="maintenance-page-content-area">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="maintenance-page-inner-content">
                    <div class="page-content-wrap">
                        <?php echo render_image_markup_by_attachment_id(get_static_option('maintain_page_logo')); ?>

                        <div class="page-content-wrap-contents mt-5">
                            <div class="logo-wrap">
                                <?php if(!empty(get_static_option('site_logo'))): ?>
                                    <?php echo render_image_markup_by_attachment_id(get_static_option('site_logo')); ?>

                                <?php else: ?>
                                    <img src="<?php echo e(asset('assets/static/img/logo/logo.png')); ?>" alt="site-logo">
                                <?php endif; ?>
                            </div>
                            <h4 class="maintain-title"><?php echo e(get_static_option('maintain_page_title')); ?></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
<?php /**PATH D:\work\planb\planb-backend\resources\views/frontend/pages/maintain.blade.php ENDPATH**/ ?>