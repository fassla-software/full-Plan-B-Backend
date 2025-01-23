<?php $__env->startSection('title', __('Dashboard')); ?>
<?php $__env->startSection('content'); ?>
    <div class="dashboard__body">
        <div class="row g-4">
            <div class="col-xxl-8 col-lg-12">
                <div class="dashboard__promo bg-white">
                    <div class="dashboard__promo__row">
                            <a href="<?php echo e(route('admin.order.all')); ?>" class="dashboard__promo__col promo_child">
                                <div class="single_promo">
                                    <div class="single_promo__contents">
                                        <span class="single_promo__subtitle"> <?php echo e(__('Total Revenue')); ?> </span>
                                        <h4 class="single_promo__title mt-2"> <?php echo e(float_amount_with_currency_symbol($total_revenue) ?? ''); ?> </h4>
                                    </div>
                                </div>
                            </a>
                        <a href="<?php echo e(route('admin.jobs')); ?>" class="dashboard__promo__col promo_child">
                            <div class="single_promo">
                                <div class="single_promo__contents">
                                    <span class="single_promo__subtitle"> <?php echo e(__('Total Job Posted')); ?> </span>
                                    <h4 class="single_promo__title mt-2"><?php echo e($total_job ?? ''); ?> </h4>
                                </div>
                            </div>
                        </a>
                        <a href="<?php echo e(route('admin.freelancer.all')); ?>" class="dashboard__promo__col promo_child">
                            <div class="single_promo">
                                <div class="single_promo__contents">
                                    <span class="single_promo__subtitle"> <?php echo e(__('Total Freelancers')); ?> </span>
                                    <h4 class="single_promo__title mt-2"> <?php echo e($total_freelancer ?? ''); ?> </h4>
                                </div>
                            </div>
                        </a>
                        <a href="<?php echo e(route('admin.client.all')); ?>" class="dashboard__promo__col promo_child">
                            <div class="single_promo">
                                <div class="single_promo__contents">
                                    <span class="single_promo__subtitle"> <?php echo e(__('Total Clients')); ?> </span>
                                    <h4 class="single_promo__title mt-2"> <?php echo e($total_client ?? ''); ?></h4>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="dashboard__charts padding-20 radius-10 bg-white mt-4">
                    <div class="dashboard__charts__header flex-between align-items-center">
                        <h4 class="dashboard__charts__title"><?php echo e(__('Revenue')); ?></h4>
                        <div class="dashboard__select">
                          <strong><?php echo e(__('Monthly Revenue')); ?></strong>
                        </div>
                    </div>
                    <div class="dashboard__charts__inner profile-border-top">
                        <canvas id="bar-chart-grouped"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-xxl-4 col-lg-8">
                <div class="dashboard__maps bg-white padding-20 radius-10">
                    <div class="dashboard__maps__flex flex-between align-items-center">
                        <h4 class="dashboard__maps__title"><?php echo e(__('Quick Access')); ?></h4>
                    </div>
                    <div class="dashboard__maps__footer mt-4">
                        <h6 class="dashboard__maps__footer__title"><?php echo e(__('System Settings By Super Admin')); ?></h6>
                        <ul class="dashboard__maps__footer__list mt-4">
                            <li class="dashboard__maps__footer__list_item">
                                <span class="dashboard__maps__footer__list__country"><?php echo e(__('Commission Type')); ?></span>
                                <span class="dashboard__maps__footer__list__count"><?php echo e(ucfirst(get_static_option('admin_commission_type') ?? '')); ?></span>
                            </li>
                            <li class="dashboard__maps__footer__list_item">
                                <span class="dashboard__maps__footer__list__country"><?php echo e(__('Commission Charge')); ?></span>
                                <span class="dashboard__maps__footer__list__count"><?php echo e(get_static_option('admin_commission_charge') ?? ''); ?></span>
                            </li>
                            <li class="dashboard__maps__footer__list_item">
                                <span class="dashboard__maps__footer__list__country"><?php echo e(__('Transaction Fee Type')); ?></span>
                                <span class="dashboard__maps__footer__list__count"><?php echo e(ucfirst(get_static_option('transaction_fee_type') ?? '')); ?></span>
                            </li>
                            <li class="dashboard__maps__footer__list_item">
                                <span class="dashboard__maps__footer__list__country"><?php echo e(__('Transaction Fee Charge')); ?></span>
                                <span class="dashboard__maps__footer__list__count"><?php echo e(get_static_option('transaction_fee_charge') ?? ''); ?></span>
                            </li>
                            <li class="dashboard__maps__footer__list_item">
                                <span class="dashboard__maps__footer__list__country"><?php echo e(__('Connect Reduce Per Proposal')); ?></span>
                                <span class="dashboard__maps__footer__list__count"><?php echo e(get_static_option('limit_settings') ?? 1); ?></span>
                            </li>
                            <li class="dashboard__maps__footer__list_item">
                                <span class="dashboard__maps__footer__list__country"><?php echo e(__('Job Auto Approval')); ?></span>
                                <span class="dashboard__maps__footer__list__count"><?php echo e(ucfirst(get_static_option('job_auto_approval'))); ?></span>
                            </li>
                            <li class="dashboard__maps__footer__list_item">
                                <span class="dashboard__maps__footer__list__country"><?php echo e(__('Project Auto Approval')); ?></span>
                                <span class="dashboard__maps__footer__list__count"><?php echo e(ucfirst(get_static_option('project_auto_approval'))); ?></span>
                            </li>
                            <li class="dashboard__maps__footer__list_item">
                                <span class="dashboard__maps__footer__list__country"><?php echo e(__('Withdraw Fee')); ?></span>
                                <span class="dashboard__maps__footer__list__count"><?php echo e(float_amount_with_currency_symbol(get_static_option('withdraw_fee')) ?? 0); ?></span>
                            </li>
                            <li class="dashboard__maps__footer__list_item">
                                <span class="dashboard__maps__footer__list__country"><?php echo e(__('Display Commission Amount')); ?></span>
                                <span class="dashboard__maps__footer__list__count"><?php echo e(!empty(get_static_option('commission_disable_client_panel')) && get_static_option('commission_disable_client_panel') == 'enable' ? __('Yes') : __('No')); ?></span>
                            </li>
                            <li class="dashboard__maps__footer__list_item">
                                <span class="dashboard__maps__footer__list__country"><?php echo e(__('Maximum Deposit Amount')); ?></span>
                                <span class="dashboard__maps__footer__list__count"><?php echo e(float_amount_with_currency_symbol(get_static_option('deposit_amount_limitation_for_user')) ?? 0); ?></span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col">
                <div class="activities">
                    <div class="activities-single radius-10 padding-20">
                        <div class="activities-single-header profile-border-bottom flex-between align-items-center">
                            <h4 class="activities-single-header-title"><?php echo e(__('Recent Orders')); ?></h4>
                        </div>
                        <div class="dashboard-tab-content-item active" id="Transactions">
                            <div class="activities-single-table mt-4">
                                <table class="table table-responsive">
                                    <thead>
                                        <tr>
                                            <th col="scope"><?php echo e(__('User ID')); ?></th>
                                            <th col="scope"><?php echo e(__('Type')); ?></th>
                                            <th col="scope"><?php echo e(__('Price')); ?></th>
                                            <th col="scope"><?php echo e(__('Payment Gateway')); ?></th>
                                            <th col="scope"><?php echo e(__('Payment Status')); ?></th>
                                            <th col="scope"><?php echo e(__('Status')); ?></th>
                                            <th col="scope"><?php echo e(__('Order Date')); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($order->user_id ?? ''); ?></td>
                                        <td><?php echo e(ucfirst($order->is_project_job)); ?></td>
                                        <td><?php echo e(float_amount_with_currency_symbol($order->price)); ?></td>
                                        <td>
                                            <?php if($order->payment_gateway == 'manual_payment'): ?>
                                                <?php echo e(ucfirst(str_replace('_',' ',$order->payment_gateway))); ?>

                                            <?php else: ?>
                                                <?php echo e($order->payment_gateway == 'authorize_dot_net' ? __('Authorize.Net') : ucfirst($order->payment_gateway)); ?>

                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if($order->payment_gateway != 'manual_payment' && $order->payment_status == 'pending'): ?>
                                                <span class="btn btn-danger btn-sm"><?php echo e(__('Payment Failed')); ?></span>
                                            <?php elseif($order->payment_status == 'pending'): ?>
                                                <span class="btn btn-warning btn-sm"><?php echo e(ucfirst(__($order->payment_status))); ?></span>
                                            <?php else: ?>
                                                <span class="btn btn-success btn-sm"><?php echo e(ucfirst(__($order->payment_status))); ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td> <?php if (isset($component)) { $__componentOriginal605246b4108ad5e678a2d15514a955b9 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal605246b4108ad5e678a2d15514a955b9 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.status.table.order-status','data' => ['status' => $order->status]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('status.table.order-status'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['status' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($order->status)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal605246b4108ad5e678a2d15514a955b9)): ?>
<?php $attributes = $__attributesOriginal605246b4108ad5e678a2d15514a955b9; ?>
<?php unset($__attributesOriginal605246b4108ad5e678a2d15514a955b9); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal605246b4108ad5e678a2d15514a955b9)): ?>
<?php $component = $__componentOriginal605246b4108ad5e678a2d15514a955b9; ?>
<?php unset($__componentOriginal605246b4108ad5e678a2d15514a955b9); ?>
<?php endif; ?> </td>
                                        <td><?php echo e($order->created_at->format('Y-m-d') ?? ''); ?></td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <script>
        (function($){
            "use strict";
            $(document).ready(function(){
                //monthly income
                new Chart(document.getElementById("bar-chart-grouped"), {
                    type: 'bar',
                    data: {
                        labels: [<?php $__currentLoopData = $month_list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $list): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> "<?php echo e($list); ?>", <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>],
                        datasets: [{
                            label: "<?php echo e(__('Revenue')); ?>",
                            backgroundColor: "#6176F6",
                            data: [<?php $__currentLoopData = $monthly_income; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $income): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> "<?php echo e($income); ?>", <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>],
                            barThickness: 15,
                            hoverBackgroundColor: '#fff',
                            hoverBorderColor: '#6176F6',
                            borderColor: '#fff',
                            borderWidth: 2,
                        }],
                    },
                    options: {
                        scales: {
                            x: {
                                grid: {
                                    display: false,
                                }
                            },
                            y: {
                                grid: {
                                    display: false,
                                }
                            },
                        }
                    }
                });
            });
        }(jQuery));

    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('backend.layout.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\laravel_workspace\Fassla\planb\core\resources\views/backend/pages/dashboard/dashboard.blade.php ENDPATH**/ ?>