<?php if(Auth::guard('web')->check()): ?>
    <div class="navbar-right-content show-nav-content">
        <div class="single-right-content">
            <div class="navbar-right-flex">

                <?php if (isset($component)) { $__componentOriginaladdcaafd8f3829115a20fa4fa94b39e7 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaladdcaafd8f3829115a20fa4fa94b39e7 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.frontend.menu-searchbar','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('frontend.menu-searchbar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginaladdcaafd8f3829115a20fa4fa94b39e7)): ?>
<?php $attributes = $__attributesOriginaladdcaafd8f3829115a20fa4fa94b39e7; ?>
<?php unset($__attributesOriginaladdcaafd8f3829115a20fa4fa94b39e7); ?>
<?php endif; ?>
<?php if (isset($__componentOriginaladdcaafd8f3829115a20fa4fa94b39e7)): ?>
<?php $component = $__componentOriginaladdcaafd8f3829115a20fa4fa94b39e7; ?>
<?php unset($__componentOriginaladdcaafd8f3829115a20fa4fa94b39e7); ?>
<?php endif; ?>
                <?php if (isset($component)) { $__componentOriginal4ea3d67194cd9259fa00ff5861e7fa45 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4ea3d67194cd9259fa00ff5861e7fa45 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.frontend.menu-chat-bookmark','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('frontend.menu-chat-bookmark'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal4ea3d67194cd9259fa00ff5861e7fa45)): ?>
<?php $attributes = $__attributesOriginal4ea3d67194cd9259fa00ff5861e7fa45; ?>
<?php unset($__attributesOriginal4ea3d67194cd9259fa00ff5861e7fa45); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal4ea3d67194cd9259fa00ff5861e7fa45)): ?>
<?php $component = $__componentOriginal4ea3d67194cd9259fa00ff5861e7fa45; ?>
<?php unset($__componentOriginal4ea3d67194cd9259fa00ff5861e7fa45); ?>
<?php endif; ?>
                <?php if (isset($component)) { $__componentOriginalf571bcd7f29cc78cae8cfeaccebe84e2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf571bcd7f29cc78cae8cfeaccebe84e2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.frontend.menu-notification','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('frontend.menu-notification'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf571bcd7f29cc78cae8cfeaccebe84e2)): ?>
<?php $attributes = $__attributesOriginalf571bcd7f29cc78cae8cfeaccebe84e2; ?>
<?php unset($__attributesOriginalf571bcd7f29cc78cae8cfeaccebe84e2); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf571bcd7f29cc78cae8cfeaccebe84e2)): ?>
<?php $component = $__componentOriginalf571bcd7f29cc78cae8cfeaccebe84e2; ?>
<?php unset($__componentOriginalf571bcd7f29cc78cae8cfeaccebe84e2); ?>
<?php endif; ?>
                <?php if (isset($component)) { $__componentOriginal44082960a85d37a9b44647cb098508d4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal44082960a85d37a9b44647cb098508d4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.frontend.menu-user-items','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('frontend.menu-user-items'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal44082960a85d37a9b44647cb098508d4)): ?>
<?php $attributes = $__attributesOriginal44082960a85d37a9b44647cb098508d4; ?>
<?php unset($__attributesOriginal44082960a85d37a9b44647cb098508d4); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal44082960a85d37a9b44647cb098508d4)): ?>
<?php $component = $__componentOriginal44082960a85d37a9b44647cb098508d4; ?>
<?php unset($__componentOriginal44082960a85d37a9b44647cb098508d4); ?>
<?php endif; ?>
            </div>
        </div>
    </div>
<?php else: ?>
    <?php if (isset($component)) { $__componentOriginal62e2a6f240024cf89e058e2efb7d002d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal62e2a6f240024cf89e058e2efb7d002d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.frontend.menu-search-login-register','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('frontend.menu-search-login-register'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal62e2a6f240024cf89e058e2efb7d002d)): ?>
<?php $attributes = $__attributesOriginal62e2a6f240024cf89e058e2efb7d002d; ?>
<?php unset($__attributesOriginal62e2a6f240024cf89e058e2efb7d002d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal62e2a6f240024cf89e058e2efb7d002d)): ?>
<?php $component = $__componentOriginal62e2a6f240024cf89e058e2efb7d002d; ?>
<?php unset($__componentOriginal62e2a6f240024cf89e058e2efb7d002d); ?>
<?php endif; ?>
<?php endif; ?><?php /**PATH D:\work\planb\planb-backend\resources\views/components/frontend/user-menu.blade.php ENDPATH**/ ?>