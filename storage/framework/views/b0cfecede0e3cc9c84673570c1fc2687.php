<div class="navbar-right-content show-nav-content">
    <div class="single-right-content">
        <div class="navbar-right-flex">
            <div class="navbar-right-item position-relative">
                <a href="#0" class="navbar-right-chat search-header-open">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </a>
                <div class="header-global-search">
                    <div class="header-global-search-header">
                        <h5 class="header-global-search-title"><?php echo e(__('Search')); ?></h5>
                        <div class="header-global-search-close search-close"><i class="fa-solid fa-times"></i>
                        </div>
                    </div>
                    <div class="header-global-search-input d-flex align-items-center">
                        <div class="header-global-search-input-inner">
                            <div class="header-global-search-input-inner-icon" id="header_search_load_spinner">
                                <i class="fa-solid fa-magnifying-glass"></i>
                            </div>
                            <input type="text" id="search_your_desired_job" class="form-control" placeholder="<?php echo e(__('Search')); ?>" autocomplete="off">
                            <div class="header-global-search-select">
                                <select id="Select_project_or_job_for_search">
                                    <?php if(get_static_option('project_enable_disable') != 'disable'): ?>
                                        <option value="project"><?php echo e(__('Project')); ?></option>
                                    <?php endif; ?>
                                    <?php if(get_static_option('job_enable_disable') != 'disable'): ?>
                                        <option value="job"><?php echo e(__('Job')); ?></option>
                                    <?php endif; ?>
                                    <option value="talent"><?php echo e(__('Talent')); ?></option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="display_search_result"></div>
                </div>
                <div class="search-overlay"></div>
            </div>
            <div class="navbar-right-btn">
                <?php if(moduleExists('Community')): ?>
                <a href="<?php echo e(route('community.all')); ?>"
                   class="cmn-btn btn-outline-1 color-one btn-small radius-10"><?php echo e(__('Community')); ?>

                </a>
                <?php endif; ?>
                <a href="<?php echo e(route('user.login')); ?>"
                   class="cmn-btn btn-outline-1 color-one btn-small radius-10"><?php echo e(__('Log In')); ?>

                </a>
            </div>
            <div class="btn-wrapper">
                <a href="<?php echo e(route('user.register')); ?>"
                   class="cmn-btn btn-bg-1 btn-small radius-10"><?php echo e(__('Sign Up')); ?>

                </a>
            </div>
        </div>
    </div>
</div><?php /**PATH D:\laravel_workspace\Fassla\planb\core\resources\views/components/frontend/menu-search-login-register.blade.php ENDPATH**/ ?>