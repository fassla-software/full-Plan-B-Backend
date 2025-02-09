<?php
    $current_url = url()->current();
    $root_url = url('/');
    $contains = Str::of($current_url)->contains($root_url.'/jobs');
    if($contains == $root_url.'/jobs') {
        //if project disable show job categories as default
        if(get_static_option('project_enable_disable') != 'disable'){
            $jobs_categories = \Modules\Service\Entities\Category::with('sub_categories')->where('status', '1')->whereHas('jobs')->get();
        }
        //if project disable show job categories as default end
    }
    else{
        if(get_static_option('category_section_enable_disable_for_homepage') == 'disable'){
            if($current_url != $root_url){
                $all_categories = \Modules\Service\Entities\Category::with('sub_categories')->where('status','1')->whereHas('projects')->get();
            }
        }else{
            $all_categories = \Modules\Service\Entities\Category::with('sub_categories')->where('status','1')->whereHas('projects')->get();
        }
   }
?>

<?php if(get_static_option('category_section_enable_disable') != 'disable'): ?>
    <?php if(!empty($jobs_categories)): ?>
        <div class="categorySub-area categorySub-padding border-top bg-white">
            <div class="container custom-container-one">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="categorySub">
                            <div class="categorySub-list nav-horizontal-scroll has-arrows" id="categoryWrap-list">
                                <div class="categorySub-arrow" id="left-arrow"></div>
                                <ul class="categorySub-list-slide" id="categoryslide-list">
                                    <?php $__currentLoopData = $jobs_categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li class="categorySub-list-slide-list">
                                            <a href="<?php echo e(route('category.jobs',$category->slug)); ?>" class="categorySub-list-slide-link"><?php echo e($category->category); ?><span class="mobileIcon"></span></a>
                                            <ul class="categorySub-slide-submenu">
                                                <?php $__currentLoopData = $category->sub_categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sub_category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <?php if($sub_category->jobs()): ?>
                                                        <li><a href="<?php echo e(route('subcategory.jobs',$sub_category->slug)); ?>"><?php echo e($sub_category->sub_category); ?></a></li>
                                                    <?php endif; ?>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </ul>
                                        </li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </ul>
                                <div class="categorySub-arrow right-arrow" id="right-arrow"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php if(get_static_option('project_enable_disable') == 'disable'): ?>
        
        <?php
            $jobs_categories = \Modules\Service\Entities\Category::with('sub_categories')->where('status', '1')->whereHas('jobs')->get();
        ?>
        <?php if(!empty($jobs_categories)): ?>
            <div class="categorySub-area categorySub-padding border-top bg-white">
                <div class="container custom-container-one">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="categorySub">
                                <div class="categorySub-list nav-horizontal-scroll has-arrows" id="categoryWrap-list">
                                    <div class="categorySub-arrow" id="left-arrow"></div>
                                    <ul class="categorySub-list-slide" id="categoryslide-list">
                                        <?php $__currentLoopData = $jobs_categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <li class="categorySub-list-slide-list">
                                                <a href="<?php echo e(route('category.jobs',$category->slug)); ?>" class="categorySub-list-slide-link"><?php echo e($category->category); ?><span class="mobileIcon"></span></a>
                                                <ul class="categorySub-slide-submenu">
                                                    <?php $__currentLoopData = $category->sub_categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sub_category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <?php if($sub_category->jobs()): ?>
                                                            <li><a href="<?php echo e(route('subcategory.jobs',$sub_category->slug)); ?>"><?php echo e($sub_category->sub_category); ?></a></li>
                                                        <?php endif; ?>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </ul>
                                            </li>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </ul>
                                    <div class="categorySub-arrow right-arrow" id="right-arrow"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        
    <?php else: ?>
        <?php if(!empty($all_categories)): ?>
            <div class="categorySub-area categorySub-padding border-top bg-white">
                <div class="container custom-container-one">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="categorySub">
                                <div class="categorySub-list nav-horizontal-scroll has-arrows" id="categoryWrap-list">
                                    <div class="categorySub-arrow" id="left-arrow"></div>
                                    <ul class="categorySub-list-slide" id="categoryslide-list">
                                        <?php $__currentLoopData = $all_categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <li class="categorySub-list-slide-list">
                                                <a href="<?php echo e(route('category.projects',$category->slug)); ?>" class="categorySub-list-slide-link"><?php echo e($category->category); ?><span class="mobileIcon"></span></a>
                                                <ul class="categorySub-slide-submenu">
                                                    <?php $__currentLoopData = $category->sub_categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sub_category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <li><a href="<?php echo e(route('subcategory.projects',$sub_category->slug)); ?>"><?php echo e($sub_category->sub_category); ?></a></li>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </ul>
                                            </li>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </ul>
                                    <div class="categorySub-arrow right-arrow" id="right-arrow"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    <?php endif; ?>
<?php endif; ?><?php /**PATH D:\work\planb\planb-backend\resources\views/components/frontend/category/category.blade.php ENDPATH**/ ?>