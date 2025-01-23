    <style>
        button.close {
            width: 30px;
            height: 30px;
            border: none;
            background: #000;
            color: #fff;
            border-radius: 3px;
            float: right;
            font-size: 20px;
        }
    </style>

<?php if($errors->any()): ?>
    <div class="alert alert-danger mt-3">
        <ul class="list-none">
            <button type="button btn-sm" class="close" data-bs-dismiss="alert">×</button>
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li> <?php echo e(__($error)); ?></li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    </div>
<?php endif; ?>
<?php /**PATH D:\laravel_workspace\Fassla\planb\core\resources\views/components/validation/error.blade.php ENDPATH**/ ?>