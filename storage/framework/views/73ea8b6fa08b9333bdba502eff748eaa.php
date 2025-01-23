<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="description" content="<?php echo e(get_static_option('site_meta_description')); ?>">
    <meta name="tags" content="<?php echo e(get_static_option('site_meta_tags')); ?>">
    <title><?php echo e(get_static_option('site_title')); ?> - <?php echo e(get_static_option('site_tag_line')); ?></title>

    <!-- favicon -->
    <?php echo render_favicon_by_id(get_static_option('site_favicon')); ?>


    <link rel="stylesheet" href="<?php echo e(asset('assets/common/css/bootstrap.min.css')); ?>">
    <style>
        :root {
            --main-color-one: <?php echo e(get_static_option('main_color_one') ?? '#6176f6'); ?>;
            --main-color-two: <?php echo e(get_static_option('main_color_two') ?? '#2bdfff'); ?>;
            --main-color-one-rgb: <?php echo e('97, 118, 246'); ?>;
            --secondary-color: <?php echo e(get_static_option('secondary_color')); ?>;
            --secondary-color-rgb: <?php echo e(get_static_option('secondary_color','#ffa500')); ?>;
            --heading-color: <?php echo e(get_static_option('heading_color','#1D2635')); ?>;
            --paragraph-color: <?php echo e(get_static_option('paragraph_color','#1D2635')); ?>;
        }

        .error-area {
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .error-wrapper-thumb img {
            max-width: 100%;
        }
        .error-wrapper-title {
            font-size: 42px;
            font-weight: 500;
            color: var(--heading-color);
            line-height: 1.2;
        }
        .error-wrapper-para {
            font-size: 16px;
            font-weight: 400;
            color: var(--paragraph-color);
            line-height: 24px;
        }
        .cmn-btn {
            color: var(--paragraph-color);
            font-size: 16px;
            font-weight: 500;
            font-family: var(--body-font);
            display: inline-block;
            border-radius: 5px;
            text-align: center;
            cursor: pointer;
            line-height: 34px;
            padding: 7px 35px;
            white-space: nowrap;
            -webkit-transition: all 0.3s ease-in;
            transition: all 0.3s ease-in;
            text-decoration: none;
        }
        .cmn-btn.btn-bg-1 {
            background: var(--main-color-one);
            color: #fff;
            border: 2px solid transparent;
        }
        .cmn-btn.btn-bg-1:hover {
            background: var(--secondary-color);
        }

        @media screen and (max-width: 575px) {
            .error-wrapper-title {
                font-size: 32px;
            }
        }
        @media screen and (max-width: 375px) {
            .error-wrapper-title {
                font-size: 28px;
            }
        }

    </style>
</head>

<body>
    <div class="overlays"></div>
    <!-- Header area end -->
    <!-- Error Area starts -->
    <div class="error-area padding-top-100 padding-bottom-100">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6 col-md-9">
                    <div class="error-wrapper text-center">
                        <div class="error-wrapper-thumb">
                            <?php echo render_image_markup_by_attachment_id(get_static_option('error_image')); ?>

                        </div>
                        <div class="error-wrapper-contents mt-5">
                            <h2 class="error-wrapper-title"><?php echo e(get_static_option('error_404_page_title')); ?></h2>
                            <p class="error-wrapper-para mt-3"><?php echo e(get_static_option('error_404_page_paragraph')); ?></p>
                            <div class="btn-wrapper mt-4">
                                <a href="<?php echo e(route('homepage')); ?>" class="cmn-btn btn-bg-1"><?php echo e(get_static_option('error_404_page_button_text')); ?></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Error Area ends -->
</body>
</html>
<?php /**PATH D:\laravel_workspace\Fassla\planb\core\resources\views/errors/404.blade.php ENDPATH**/ ?>