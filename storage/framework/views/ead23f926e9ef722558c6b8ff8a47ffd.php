
<!DOCTYPE html>
<html lang="<?php echo e(get_user_lang()); ?>" dir="<?php echo e(get_user_lang_direction()); ?>">

<head>
    <?php echo renderHeadStartHooks(); ?>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <!-- favicon -->
    <?php
        $site_favicon = get_attachment_image_by_id(get_static_option('site_favicon'),"full",false);
    ?>
    <?php if(!empty($site_favicon)): ?>
        <link rel="icon" href="<?php echo e($site_favicon['img_url'] ?? ''); ?>" sizes="40x40" type="icon/png">
    <?php endif; ?>
    <?php echo load_google_fonts(); ?>


    <!-- bootstrap -->
    <link rel="stylesheet" href="<?php echo e(asset('assets/common/css/bootstrap.min.css')); ?>">
    <!-- Animate Css -->
    <link rel="stylesheet" href="<?php echo e(asset('assets/frontend/css/animate.css')); ?>">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="<?php echo e(asset('assets/frontend/css/slick.css')); ?>">
    <!-- All Plugin Css -->
    <link rel="stylesheet" href="<?php echo e(asset('assets/common/css/all_plugin.css')); ?>">
    <!-- Toastr Css -->
    <link rel="stylesheet" href="<?php echo e(asset('assets/common/css/toastr.min.css')); ?>">
    <!-- Helper Css -->
    <link rel="stylesheet" href="<?php echo e(asset('assets/frontend/css/helpers.css')); ?>">
    <!-- Main Stylesheet -->
    <link rel="stylesheet" href="<?php echo e(asset('assets/frontend/css/magnific-popup.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/frontend/css/style.css')); ?>">
    <?php if(get_user_lang_direction() == 'rtl'): ?>
        <link rel="stylesheet" href="<?php echo e(asset('assets/frontend/css/xilancer-rtl.css')); ?>">
    <?php endif; ?>
    <?php echo $__env->make('frontend.layout.partials.root-style', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <!-- page css -->
    <?php echo $__env->yieldContent('style'); ?>

    <?php if(request()->routeIs('homepage')): ?>
        <title><?php echo e(get_static_option('site_title')); ?> - <?php echo e(get_static_option('site_tag_line')); ?></title>

        <?php echo render_site_meta(); ?>


    <?php elseif( request()->routeIs('frontend.dynamic.page') && $page_type === 'page' ): ?>

        <?php echo render_site_title(optional($page_post)->title ); ?>

        <?php echo render_site_meta(); ?>


    <?php else: ?>
        <title><?php echo $__env->yieldContent('site_title'); ?></title>
        <?php if(View::hasSection('meta_title')): ?>
            <meta name="title" content="<?php echo $__env->yieldContent('meta_title'); ?>">
        <?php endif; ?>
        <?php if(View::hasSection('meta_description')): ?>
            <meta name="description" content="<?php echo $__env->yieldContent('meta_description'); ?>">
        <?php endif; ?>
    <?php endif; ?>
<?php

    $custom_css = '';
    if (file_exists('assets/frontend/css/dynamic-style.css')) {
        $custom_css = file_get_contents('assets/frontend/css/dynamic-style.css');
    }
    ?>
    <?php if(!empty($custom_css)): ?>
        <link rel="stylesheet" href="<?php echo e(asset('assets/frontend/css/dynamic-style.css')); ?>">
    <?php endif; ?>
    <?php echo renderHeadEndHooks(); ?>

</head>

<body>
<?php echo renderBodyStartHooks(); ?><?php /**PATH D:\work\planb\planb-backend\resources\views/frontend/layout/partials/header.blade.php ENDPATH**/ ?>