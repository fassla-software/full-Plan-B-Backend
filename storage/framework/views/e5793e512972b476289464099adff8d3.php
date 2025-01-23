<div class="dashboard__header">
    <div class="dashboard__header__flex">
        <div class="dashboard__header__left">
            <h4 class="dashboard__header__title"><?php echo e(Auth::guard('admin')->user()->name ?? ''); ?></h4>
            <a href="<?php echo e(url('/')); ?>" class="dashboard__header__para mt-2"><?php echo e(__('Here\'s what\'s going on in')); ?> <?php echo e(get_static_option('site_title')); ?></a>
        </div>
        <div class="dashboard__header__right">
            <div class="dashboard__header__right__flex">
                <div class="dashboard__header__right__item">
                    <a target="_blank" href="<?php echo e(url('/')); ?>" class="visitSite__btn"><?php echo e(__('Visit Site')); ?></a>
                </div>
                <div class="dashboard__header__right__item">
                    <div class="dashboard__author">
                        <a href="javascript:void(0)" class="dashboard__author__flex flex-btn">
                            <div class="dashboard__author__thumb">
                                <?php if(Auth::check() && Auth::guard('admin')->user()->image): ?>
                                    <?php echo render_image_markup_by_attachment_id(Auth::guard('admin')->user()->image,'','thumb'); ?>

                                <?php else: ?>
                                    <img src="<?php echo e(asset('assets/static/img/admin/admin.jpg')); ?>" alt="authorImg">
                                <?php endif; ?>
                            </div>
                        </a>
                        <div class="dashboard__author__wrapper">
                            <div class="dashboard__author__wrapper__list">
                                <?php if(Auth::check() && Auth::guard('admin')->user()->role == '1'): ?>
                                <a href="<?php echo e(route('admin.all')); ?>" class="dashboard__author__wrapper__list__item"><i class="fa-solid fa-gear"></i><?php echo e(__('Admin settings')); ?></a>
                                <?php endif; ?>
                                    <a href="<?php echo e(route('admin.update.info')); ?>" class="dashboard__author__wrapper__list__item"><i class="fa-solid fa-info"></i><?php echo e(__('Update Info')); ?></a>
                                    <a href="<?php echo e(route('admin.logout')); ?>" class="dashboard__author__wrapper__list__item"><i class="fa-solid fa-arrow-right-from-bracket"></i><?php echo e(__('Log Out')); ?></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="dashboard__header__right__item">
                    <div class="dashboard__notification">
                        <a href="javascript:void(0)" class="dashboard__notification__icon">
                                <i class="fa-solid fa-bell"></i>
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"><?php echo e(App\Models\AdminNotification::unread_notification()->count()); ?></span>
                        </a>
                        <div class="dashboard__notification__wrapper">
                            <div class="dashboard__notification__list">
                                <?php $__currentLoopData = App\Models\AdminNotification::unread_notification(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                  <?php if (isset($component)) { $__componentOriginala4da870086bc8388cd68c77881503bdd = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala4da870086bc8388cd68c77881503bdd = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.backend.admin-notification','data' => ['notification' => $notification]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('backend.admin-notification'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['notification' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($notification)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala4da870086bc8388cd68c77881503bdd)): ?>
<?php $attributes = $__attributesOriginala4da870086bc8388cd68c77881503bdd; ?>
<?php unset($__attributesOriginala4da870086bc8388cd68c77881503bdd); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala4da870086bc8388cd68c77881503bdd)): ?>
<?php $component = $__componentOriginala4da870086bc8388cd68c77881503bdd; ?>
<?php unset($__componentOriginala4da870086bc8388cd68c77881503bdd); ?>
<?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php /**PATH D:\laravel_workspace\Fassla\planb\core\resources\views/backend/layout/partials/top-header.blade.php ENDPATH**/ ?>