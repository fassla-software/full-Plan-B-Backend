<!DOCTYPE html>
<html lang="<?php echo e(get_user_lang()); ?>" dir="<?php echo e(get_user_lang_direction()); ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title',''); ?></title>
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
    <!-- bootstrap -->

    <!-- All Plugin Css -->
    <link rel="stylesheet" href="<?php echo e(asset('assets/common/css/all_plugin.css')); ?>">
    <!-- Toastr Css -->
    <link rel="stylesheet" href="<?php echo e(asset('assets/common/css/toastr.min.css')); ?>">
    <!-- Dashboard Stylesheet -->
    <link rel="stylesheet" href="<?php echo e(asset('assets/backend/css/admin_dashboard.css')); ?>">
    <?php if(get_user_lang_direction() == 'rtl'): ?>
        <link rel="stylesheet" href="<?php echo e(asset('assets/backend/css/admin-rtl.css')); ?>">
    <?php endif; ?>
    <?php echo $__env->make('frontend.layout.partials.root-style', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php echo $__env->yieldContent('style'); ?>

</head>
<body>
<?php /**PATH D:\laravel_workspace\Fassla\planb\core\resources\views/backend/layout/partials/header.blade.php ENDPATH**/ ?>