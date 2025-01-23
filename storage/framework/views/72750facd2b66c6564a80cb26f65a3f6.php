<?php
    $navbar_variant = !is_null(get_navbar_style()) ? get_navbar_style() : '02';
?>
<?php echo $__env->make('frontend.layout.partials.navbar-variant.navbar-'. $navbar_variant, \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php /**PATH D:\laravel_workspace\Fassla\planb\core\resources\views/frontend/layout/partials/navbar.blade.php ENDPATH**/ ?>