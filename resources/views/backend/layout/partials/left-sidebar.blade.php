<div class="dashboard__left dashboard-left-content">
    <div class="dashboard__left__main">
        <div class="dashboard__left__close close-bars"> <i class="fa-solid fa-times"></i> </div>
        <div class="dashboard__top">
            <div class="dashboard__top__logo">
                <a href="{{ route('admin.dashboard') }}">
                    @if (!empty(get_static_option('site_white_logo')))
                        {!! render_image_markup_by_attachment_id(get_static_option('site_white_logo')) !!}
                    @else
                        <img src="{{ asset('assets/static/img/logo/dashboard_logo.png') }}" alt="dashboard-logo">
                    @endif
                </a>
            </div>
        </div>
        <div class="dashboard__bottom mt-5">
            <ul class="dashboard__bottom__list dashboard-list">

                <li class="dashboard__bottom__list__item @if (request()->routeIs(['admin.dashboard'])) active @endif">
                    <a href="{{ route('admin.dashboard') }}"> <i
                            class="fa-solid fa-chart-simple"></i>{{ __('Dashboard') }}</a>
                </li>

                @can('admin-role-manage')
                    <li
                        class="dashboard__bottom__list__item has-children @if (request()->is('admin/manage*') || request()->is('admin/role*')) active open show @endif">
                        <a href="javascript:void(0)"> <i class="fa-solid fa-user"></i> {{ __('Admin Role Manage') }}
                        </a>
                        <ul class="submenu">
                            <li class="dashboard__bottom__list__item @if (request()->routeIs(['admin.create'])) selected @endif">
                                <a href="{{ route('admin.create') }}"> {{ __('Add New Admin') }} </a>
                            </li>
                            <li class="dashboard__bottom__list__item @if (request()->routeIs(['admin.all'])) selected @endif">
                                <a href="{{ route('admin.all') }}"> {{ __('All Admins') }} </a>
                            </li>
                            <li class="dashboard__bottom__list__item @if (request()->routeIs(['admin.role.create'])) selected @endif">
                                <a href="{{ route('admin.role.create') }}"> {{ __('All Roles') }} </a>
                            </li>
                        </ul>
                    </li>
                @endcan

                <li
                    class="dashboard__bottom__list__item has-children @if (request()->is('admin/user*')) active open show @endif">
                    <a href="javascript:void(0)"> <i class="fa-solid fa-user"></i> {{ __('User Manage') }} </a>
                    <ul class="submenu">
                        @can('user-list')
                            <li class="dashboard__bottom__list__item @if (request()->routeIs(['admin.freelancer.all'])) selected @endif">
                                <a href="{{ route('admin.freelancer.all') }}"> {{ __('All Freelancers') }} </a>
                            </li>
                            <li class="dashboard__bottom__list__item @if (request()->routeIs(['admin.client.all'])) selected @endif">
                                <a href="{{ route('admin.client.all') }}"> {{ __('All Clients') }} </a>
                            </li>
                        @endcan
                        @can('user-trash-list')
                            <li class="dashboard__bottom__list__item @if (request()->routeIs(['admin.user.restore'])) selected @endif">
                                <a href="{{ route('admin.user.restore') }}"> {{ __('Trash List') }} </a>
                            </li>
                        @endcan
                        @can('user-identity-verify-request-list')
                            <li class="dashboard__bottom__list__item @if (request()->routeIs(['admin.user.verification.request'])) selected @endif">
                                <a href="{{ route('admin.user.verification.request') }}">
                                    {{ __('Identity Verify Requests') }} </a>
                            </li>
                        @endcan
                        <li class="dashboard__bottom__list__item @if (request()->routeIs(['admin.user.add'])) selected @endif">
                            <a href="{{ route('admin.user.add') }}">
                                {{ __('Add New User') }} </a>
                        </li>
                        <li class="dashboard__bottom__list__item @if (request()->routeIs(['admin.email.send.to.all.users'])) selected @endif">
                        <a href="{{ route('admin.email.send.to.all.users') }}">
                            {{ __('Email To All User') }} </a>
                    </li>
                    </ul>
                </li>

                <li class="dashboard__bottom__list__item @if (request()->routeIs(['admin.integration'])) active @endif">
                    <a href="{{ route('admin.integration') }}"> <i
                                class="fa-solid fa-plug"></i>{{ __('Integrations') }}</a>
                </li>

                @if(moduleExists('PluginManage'))
                <li
                        class="dashboard__bottom__list__item has-children @if (request()->is('admin/plugin-manage*')) active open @endif">
                    <a href="javascript:void(0)"> <i class="fa-solid fa-user"></i> {{ __('Plugin Manage') }} </a>
                    <ul class="submenu">
                            <li class="dashboard__bottom__list__item @if (request()->routeIs(['admin.plugin.manage.all'])) selected @endif">
                                <a href="{{ route('admin.plugin.manage.all') }}"> {{ __('All Plugins') }} </a>
                            </li>
                            <li class="dashboard__bottom__list__item @if (request()->routeIs(['admin.plugin.manage.new'])) selected @endif">
                                <a href="{{ route('admin.plugin.manage.new') }}"> {{ __('Add Plugin') }} </a>
                            </li>
                    </ul>
                </li>
                @endif
                <!-- Render all module route start -->
                @php
                    $all_modules_route = (new \App\Helper\ModuleMetaData())->getAllExternalMenu() ?? [];

                @endphp
                @foreach($all_modules_route as $index => $externalMenu)
                    @php $flag = false;
                        // now i need to get all routes name from externalMenu
                        $activeRoutes = array_column((array) $externalMenu, 'route');
                    @endphp

                    @foreach ($externalMenu as $key => $individual_menu_item)
                        @php
                            $convert_to_array = (array) $individual_menu_item;
                            $convert_to_array['label'] = __($convert_to_array['label']);
                            if (array_key_exists('permissions', $convert_to_array) && !is_array($convert_to_array['permissions'])) {
                                $convert_to_array['permissions'] = [$convert_to_array['permissions']];
                            }
                            $routeName = $convert_to_array['route'];
                            $icon = array_key_exists('icon', $convert_to_array) ? $convert_to_array['icon'] : '';
                        @endphp
                        @if(count($externalMenu) > 1)
                            @if($key === 0)
                                <li class="dashboard__bottom__list__item has-children @if(in_array(\Request::route()->getName(), $activeRoutes)) active open @endif">
                            @endif

                                    @if(empty($convert_to_array['parent']) && !$flag)
                                        @php
                                            $flag = true;
                                        @endphp
                                        <a href="javascript:void(0)">
                                            <i class="{{$icon}}"></i>
                                            <span class="icon_title">{{ $convert_to_array['label'] }} <span class="badge bg-danger">{{ __('Plugin') }}</span> </span>
                                        </a>
                                        <ul class="submenu" style=" @if(in_array(\Request::route()->getName(), $activeRoutes)) display:block; @endif">
                                    @endif
                                            @if($key !== 0 && $flag)
                                                <li class="dashboard__bottom__list__item  @if(request()->routeIs($routeName) == $routeName) selected @endif">
                                                    <a href="{{ route($routeName) }}">{{ $convert_to_array['label'] }}</a>
                                                </li>
                                            @endif
                                            @if($key === count($externalMenu)-1)
                                        </ul>
                                </li>
                            @endif
                        @else
                            <li class="dashboard__bottom__list__item @if(request()->routeIs($routeName)) active open @endif">
                                <a href="{{ route($routeName) }}">  <i class="{{$icon}}"></i>  {{ $convert_to_array['label'] }} <span class="badge bg-danger">{{ __('Plugin') }}</span> </a>
                            </li>
                        @endif
                    @endforeach
                @endforeach
                <!-- Render all module route end -->

                <li
                    class="dashboard__bottom__list__item has-children @if (request()->is('admin/location*')) active open show @endif">
                    <a href="javascript:void(0)"> <i class="fa-solid fa-earth-americas"></i> {{ __('Country Manage') }}
                    </a>
                    <ul class="submenu">
                        @can('country-list')
                            <li class="dashboard__bottom__list__item @if (request()->routeIs(['admin.country.all'])) selected @endif">
                                <a href="{{ route('admin.country.all') }}"> {{ __('Country') }} </a>
                            </li>
                        @endcan
                        @can('country-csv-file-import')
                            <li class="dashboard__bottom__list__item @if (request()->routeIs(['admin.country.import.csv.settings'])) selected @endif">
                                <a href="{{ route('admin.country.import.csv.settings') }}"> {{ __('Import Country') }}
                                </a>
                            </li>
                        @endcan
                        @can('state-list')
                            <li class="dashboard__bottom__list__item @if (request()->routeIs(['admin.state.all'])) selected @endif">
                                <a href="{{ route('admin.state.all') }}"> {{ __('State') }} </a>
                            </li>
                        @endcan
                        @can('state-csv-file-import')
                            <li class="dashboard__bottom__list__item @if (request()->routeIs(['admin.state.import.csv.settings'])) selected @endif">
                                <a href="{{ route('admin.state.import.csv.settings') }}"> {{ __('Import States') }} </a>
                            </li>
                        @endcan
                        @can('city-list')
                            <li class="dashboard__bottom__list__item @if (request()->routeIs(['admin.city.all'])) selected @endif">
                                <a href="{{ route('admin.city.all') }}"> {{ __('City') }} </a>
                            </li>
                        @endcan
                        @can('city-csv-file-import')
                            <li class="dashboard__bottom__list__item @if (request()->routeIs(['admin.city.import.csv.settings'])) selected @endif">
                                <a href="{{ route('admin.city.import.csv.settings') }}"> {{ __('Import Cities') }} </a>
                            </li>
                        @endcan
                    </ul>
                </li>

                <li
                    class="dashboard__bottom__list__item has-children @if (request()->is('admin/service*')) active open show @endif">
                    <a href="javascript:void(0)"> <i class="fa-solid fa-list"></i>{{ __('Catalogue Manage') }} </a>
                    <ul class="submenu">
                        @can('category-list')
                            <li class="dashboard__bottom__list__item @if (request()->routeIs(['admin.category.all'])) selected @endif">
                                <a href="{{ route('admin.category.all') }}"> {{ __('Category') }} </a>
                            </li>
                        @endcan
                        @can('subcategory-list')
                            <li class="dashboard__bottom__list__item @if (request()->routeIs(['admin.subcategory.all'])) selected @endif">
                                <a href="{{ route('admin.subcategory.all') }}"> {{ __('Sub Category') }} </a>
                            </li>
                        @endcan
                        @if(moduleExists('CoinPaymentGateway'))
                            @can('category-list')
                                <li class="dashboard__bottom__list__item @if (request()->routeIs(['admin.category.select'])) selected @endif">
                                    <a href="{{ route('admin.category.select') }}"> {{ __('Select Navbar Category') }} </a>
                                </li>
                            @endcan
                        @endif

                        <li class="dashboard__bottom__list__item @if (request()->routeIs(['admin.category.enable.disable.settings'])) selected @endif">
                            <a href="{{ route('admin.category.enable.disable.settings') }}"> {{ __('Enable Disable Section') }} </a>
                        </li>
                    </ul>
                </li>

                <li class="dashboard__bottom__list__item @if (request()->routeIs(['admin.feedback.all'])) active @endif">
                    <a href="{{ route('admin.feedback.all') }}"> <i
                                class="fa-solid fa-chart-simple"></i>{{ __('Feedback Manage') }}</a>
                </li>

                @can('skill-list')
                    <li class="dashboard__bottom__list__item @if (request()->routeIs(['admin.skill'])) active @endif">
                        <a href="{{ route('admin.skill') }}"> <i class="fa-solid fa-user-gear"></i>{{ __('Skills') }}</a>
                    </li>
                @endcan

                <li class="dashboard__bottom__list__item @if (request()->routeIs(['admin.length.all'])) active @endif">
                    <a href="{{ route('admin.length.all') }}"> <i class="fa-regular fa-clock"></i>{{ __('Job & Project Length') }}</a>
                </li>

                <li class="dashboard__bottom__list__item @if (request()->routeIs(['admin.experience.level.all'])) active @endif">
                    <a href="{{ route('admin.experience.level.all') }}"> <i class="fa-solid fa-user-gear"></i>{{ __('Experience Level') }}</a>
                </li>

                <li
                    class="dashboard__bottom__list__item has-children @if (request()->is('admin/project*')) active open show @endif">
                    <a href="javascript:void(0)"> <i class="fa-solid fa-file-word"></i>{{ __('Services') }} </a>
                    <ul class="submenu">
                        @can('project-list')
                            <li class="dashboard__bottom__list__item @if (request()->routeIs(['admin.project'])) selected @endif">
                                <a href="{{ route('admin.project') }}"> {{ __('All Services') }} </a>
                            </li>
                        @endcan
                        @can('project-history-list')
                            <li class="dashboard__bottom__list__item @if (request()->routeIs(['admin.project.history'])) selected @endif">
                                <a href="{{ route('admin.project.history') }}"> {{ __('Service History') }} </a>
                            </li>
                            <li class="dashboard__bottom__list__item @if (request()->routeIs(['admin.project.approval.settings'])) selected @endif">
                                <a href="{{ route('admin.project.approval.settings') }}"> {{ __('Auto Approval Settings') }} </a>
                            </li>
                        @endcan
                    </ul>
                </li>

                <li
                    class="dashboard__bottom__list__item has-children @if (request()->is('admin/job*')) active open show @endif">
                    <a href="javascript:void(0)"> <i class="fa-solid fa-file-word"></i>{{ __('Requests') }} </a>
                    <ul class="submenu">
                        @can('job-list')
                            <li class="dashboard__bottom__list__item @if (request()->routeIs(['admin.jobs'])) selected @endif">
                                <a href="{{ route('admin.jobs') }}"> {{ __('All Requests') }} </a>
                            </li>
                        @endcan
                        @can('job-history-list')
                            <li class="dashboard__bottom__list__item @if (request()->routeIs(['admin.job.history'])) selected @endif">
                                <a href="{{ route('admin.job.history') }}"> {{ __('Request History') }} </a>
                            </li>
                        @endcan
                        @can('job-auto-approval')
                            <li class="dashboard__bottom__list__item @if (request()->routeIs(['admin.job.approval.settings'])) selected @endif">
                                <a href="{{ route('admin.job.approval.settings') }}"> {{ __('Auto Approval Settings') }}
                                </a>
                            </li>
                        @endcan
                    </ul>
                </li>

                <li
                    class="dashboard__bottom__list__item has-children @if (request()->is('admin/wallet*')) active open show @endif">
                    <a href="javascript:void(0)"> <i class="fa-solid fa-briefcase"></i> {{ __('Wallet') }}</a>
                    <ul class="submenu">
                        @can('deposit-settings-view')
                            <li class="dashboard__bottom__list__item @if (request()->routeIs(['admin.wallet.deposit.settings'])) selected @endif">
                                <a href="{{ route('admin.wallet.deposit.settings') }}">
                                    {{ __('Maximum Deposit Settings') }} </a>
                            </li>
                        @endcan
                        @can('deposit-list')
                            <li class="dashboard__bottom__list__item @if (request()->routeIs(['admin.wallet.history'])) selected @endif">
                                <a href="{{ route('admin.wallet.history') }}"> {{ __('Wallet History') }} </a>
                            </li>
                        @endcan
                    </ul>
                </li>

                <li
                    class="dashboard__bottom__list__item has-children @if (request()->is('admin/withdraw*')) active open show @endif">
                    <a href="javascript:void(0)"> <i class="fa-solid fa-briefcase"></i> {{ __('Withdraw') }}</a>
                    <ul class="submenu">
                        @can('withdraw-settings-view')
                            <li class="dashboard__bottom__list__item @if (request()->routeIs(['admin.wallet.withdraw.settings'])) selected @endif">
                                <a href="{{ route('admin.wallet.withdraw.settings') }}"> {{ __('Withdraw Settings') }}
                                </a>
                            </li>
                        @endcan
                        @can('withdraw-payment-gateway-list')
                            <li class="dashboard__bottom__list__item @if (request()->routeIs(['admin.wallet.withdraw.gateway'])) selected @endif">
                                <a href="{{ route('admin.wallet.withdraw.gateway') }}">
                                    {{ __('Withdraw Payment Gateway') }} </a>
                            </li>
                        @endcan
                        @can('withdraw-list')
                            <li class="dashboard__bottom__list__item @if (request()->routeIs(['admin.wallet.withdraw.request'])) selected @endif">
                                <a href="{{ route('admin.wallet.withdraw.request') }}"> {{ __('Withdraw Request') }} </a>
                            </li>
                        @endcan
{{--                        @can('withdraw-settings-view')--}}
{{--                            <li class="dashboard__bottom__list__item @if (request()->routeIs(['admin.client.withdraw.enable.disable.settings'])) selected @endif">--}}
{{--                                <a href="{{ route('admin.client.withdraw.enable.disable.settings') }}"> {{ __('Client Withdraw Enable Disable') }} </a>--}}
{{--                            </li>--}}
{{--                        @endcan--}}
                    </ul>
                </li>

                <li
                    class="dashboard__bottom__list__item has-children @if (request()->is('admin/subscription*')) active open show @endif">
                    <a href="javascript:void(0)"> <i class="fa-solid fa-list"></i>{{ __('Subscription Manage') }}
                    </a>
                    <ul class="submenu">
                        @can('subscription-type-list')
                            <li class="dashboard__bottom__list__item @if (request()->routeIs(['admin.subscription.type.all'])) selected @endif">
                                <a href="{{ route('admin.subscription.type.all') }}"> {{ __('Subscription Type') }} </a>
                            </li>
                        @endcan
                        @can('subscription-list')
                            <li class="dashboard__bottom__list__item @if (request()->routeIs(['admin.subscription.all', 'admin.subscription.add', 'admin.subscription.edit'])) selected @endif">
                                <a href="{{ route('admin.subscription.all') }}"> {{ __('All Subscriptions') }} </a>
                            </li>
                        @endcan
                        @can('subscription-connect-settings-view')
                            <li class="dashboard__bottom__list__item @if (request()->routeIs(['admin.subscription.limit.settings'])) selected @endif">
                                <a href="{{ route('admin.subscription.limit.settings') }}">
                                    {{ __('Subscription Connect Settings') }} </a>
                            </li>
                        @endcan
                        @can('user-subscription-list')
                            <li class="dashboard__bottom__list__item @if (request()->routeIs(['admin.user.subscription.all'])) selected @endif">
                                <a href="{{ route('admin.user.subscription.all') }}"> {{ __('User Subscriptions') }} </a>
                            </li>
                        @endcan
                        <li class="dashboard__bottom__list__item @if (request()->routeIs(['admin.free.subscription.settings'])) selected @endif">
                            <a href="{{ route('admin.free.subscription.settings') }}">
                                {{ __('Free Subscription Settings') }} </a>
                        </li>
                        <li class="dashboard__bottom__list__item @if (request()->routeIs(['admin.subscription.enable.disable.settings'])) selected @endif">
                            <a href="{{ route('admin.subscription.enable.disable.settings') }}">
                                {{ __('Subscription Enable Disable') }} </a>
                        </li>
                    </ul>
                </li>

                @can('transaction-manage')
                    <li
                        class="dashboard__bottom__list__item has-children @if (request()->is('admin/transaction*')) active open show @endif">
                        <a href="javascript:void(0)"> <i class="fa-solid fa-list"></i>{{ __('Transaction Manage') }}
                        </a>
                        <ul class="submenu">
                            <li
                                class="dashboard__bottom__list__item @if (request()->routeIs(['admin.commission.settings'])) selected @endif">
                                <a href="{{ route('admin.commission.settings') }}">
                                    {{ __('Admin Commission Settings') }} </a>
                            </li>
                            <li
                                class="dashboard__bottom__list__item @if (request()->routeIs(['admin.transaction.fee.settings'])) selected @endif">
                                <a href="{{ route('admin.transaction.fee.settings') }}">
                                    {{ __('Transaction Fee Settings') }} </a>
                            </li>
                            <li
                                class="dashboard__bottom__list__item @if (request()->routeIs(['admin.withdraw.fee.settings'])) selected @endif">
                                <a href="{{ route('admin.withdraw.fee.settings') }}">
                                    {{ __('Withdraw Fee Settings') }} </a>
                            </li>

                        </ul>
                    </li>
                @endcan

                <li
                    class="dashboard__bottom__list__item has-children @if (request()->is('admin/order*')) active open show @endif">
                    <a href="javascript:void(0)"> <i class="fa-solid fa-list"></i>{{ __('Order Manage') }} </a>
                    <ul class="submenu">
                        @can('order-list')
                            <li class="dashboard__bottom__list__item @if (request()->routeIs(['admin.order.all'])) selected @endif">
                                <a href="{{ route('admin.order.all') }}"> {{ __('All Orders') }} </a>
                            </li>
                            <li class="dashboard__bottom__list__item @if (request()->routeIs(['admin.order.enable.disable.description.settings'])) selected @endif">
                                <a href="{{ route('admin.order.enable.disable.description.settings') }}"> {{ __('Order Description Settings') }} </a>
                            </li>
                            <li class="dashboard__bottom__list__item @if (request()->routeIs(['admin.order.enable.disable.milestone.settings'])) selected @endif">
                                <a href="{{ route('admin.order.enable.disable.milestone.settings') }}"> {{ __('Order Milestone Settings') }} </a>
                            </li>

{{--                            <li class="dashboard__bottom__list__item @if (request()->routeIs(['admin.order.enable.disable.settings'])) selected @endif">--}}
{{--                                <a href="{{ route('admin.order.enable.disable.settings') }}">--}}
{{--                                    {{ __('Order Enable Disable') }} </a>--}}
{{--                            </li>--}}
                            <li class="dashboard__bottom__list__item @if (request()->routeIs(['admin.order.approval.settings'])) selected @endif">
                                <a href="{{ route('admin.order.approval.settings') }}">
                                    {{ __('Auto Approval Settings') }} </a>
                            </li>
                        @endcan
                    </ul>
                </li>

                <li class="dashboard__bottom__list__item has-children @if (request()->is('admin/user-report*')) active open show @endif">
                    <a href="javascript:void(0)"> <i class="fa-solid fa-list"></i>{{ __('User Report Manage') }} </a>
                    <ul class="submenu">
                        <li class="dashboard__bottom__list__item @if (request()->routeIs(['admin.user.report.all'])) selected @endif">
                            <a href="{{ route('admin.user.report.all') }}"> {{ __('All Reports') }} </a>
                        </li>
                    </ul>
                </li>

                <li class="dashboard__bottom__list__item has-children @if (request()->is('admin/newsletter*')) active open show @endif">
                    <a href="javascript:void(0)"> <i class="fa-solid fa-list"></i>{{ __('Newsletter Manage') }} </a>
                    <ul class="submenu">
                        <li class="dashboard__bottom__list__item @if (request()->routeIs(['admin.newsletter.email.all'])) selected @endif">
                            <a href="{{ route('admin.newsletter.email.all') }}"> {{ __('All Emails') }} </a>
                        </li>
                        <li class="dashboard__bottom__list__item @if (request()->routeIs(['admin.newsletter.email.send.to.all'])) selected @endif">
                            <a href="{{ route('admin.newsletter.email.send.to.all') }}"> {{ __('Email to All') }} </a>
                        </li>
                    </ul>
                </li>

                @can('blog-list')
                <li class="dashboard__bottom__list__item has-children @if (request()->is('admin/blog*')) active open show @endif">
                    <a href="javascript:void(0)"> <i class="fa fa-blog"></i> {{ __('Blog Manage') }} </a>
                    <ul class="submenu">
                        @can('blog-list')
                        <li class="dashboard__bottom__list__item @if (request()->routeIs(['admin.blog.all'])) selected @endif">
                            <a href="{{ route('admin.blog.all') }}"> {{ __('All Blogs') }} </a>
                        </li>
                        @endcan
                        @can('blog-add')
                        <li class="dashboard__bottom__list__item @if (request()->routeIs(['admin.blog.create'])) selected @endif">
                            <a href="{{ route('admin.blog.create') }}"> {{ __('Add New Blog') }} </a>
                        </li>
                        @endcan
                    </ul>
                </li>
                @endcan

                <li
                    class="dashboard__bottom__list__item has-children @if (request()->is('admin/support-ticket/*')) active open show @endif">
                    <a href="javascript:void(0)"> <i class="fa-solid fa-list"></i>{{ __('Support') }} </a>
                    <ul class="submenu">
                        @can('department-list')
                            <li class="dashboard__bottom__list__item @if (request()->routeIs(['admin.department'])) selected @endif">
                                <a href="{{ route('admin.department') }}"> {{ __('Department') }} </a>
                            </li>
                        @endcan
                        @can('support-ticket-list')
                            <li class="dashboard__bottom__list__item @if (request()->routeIs(['admin.ticket'])) selected @endif">
                                <a href="{{ route('admin.ticket') }}"> {{ __('Support Ticket') }} </a>
                            </li>
                        @endcan
                    </ul>
                </li>
                <li class="dashboard__bottom__list__item has-children @if (request()->is('admin/notification/*')) active open show @endif">
                    <a href="javascript:void(0)"> <i class="fa-solid fa-list"></i>{{ __('Notifications') }} </a>
                    <ul class="submenu">
                        @can('notification-list')
                            <li class="dashboard__bottom__list__item @if (request()->routeIs(['admin.notification.all'])) active @endif">
                                <a href="{{ route('admin.notification.all') }}"> <i class="fa-solid fa-bell"></i>{{ __('All Notifications') }}</a>
                            </li>
                            <li class="dashboard__bottom__list__item @if (request()->routeIs(['admin.notification.settings'])) active @endif">
                                <a href="{{ route('admin.notification.settings') }}"> <i class="fa-solid fa-bell"></i>{{ __('Notification Settings') }}</a>
                            </li>
                        @endcan
                    </ul>
                </li>

                <li class="dashboard__bottom__list__item @if (request()->routeIs(['admin.pusher.settings'])) active @endif">
                    <a href="{{ route('admin.pusher.settings') }}"> <i
                            class="fa-regular fa-message"></i>{{ __('Chat Settings') }}</a>
                </li>

                <li
                    class="dashboard__bottom__list__item has-children @if (request()->is('admin/page-settings*')) active open show @endif">
                    <a href="javascript:void(0)"> <i class="fa-solid fa-gear"></i> {{ __('Page Settings') }} </a>
                    <ul class="submenu">
                        @can('login-page-settings-view')
                            <li class="dashboard__bottom__list__item @if (request()->routeIs(['admin.page.settings.login'])) selected @endif">
                                <a href="{{ route('admin.page.settings.login') }}"> {{ __('Login Page Settings') }} </a>
                            </li>
                        @endcan
                        @can('register-page-settings-view')
                            <li class="dashboard__bottom__list__item @if (request()->routeIs(['admin.page.settings.register'])) selected @endif">
                                <a href="{{ route('admin.page.settings.register') }}">
                                    {{ __('Register Page Settings') }} </a>
                            </li>
                        @endcan
                            <li class="dashboard__bottom__list__item @if (request()->routeIs(['admin.page.settings.register.recaptcha'])) selected @endif">
                                <a href="{{ route('admin.page.settings.register.recaptcha') }}">
                                    {{ __('Register Recaptcha Settings') }} </a>
                            </li>
                        <li
                            class="dashboard__bottom__list__item has-children @if (request()->is('admin/page-settings/account*')) active open show @endif">
                            <a href="javascript:void(0)"> {{ __('Account Settings') }} </a>
                            <ul class="submenu">
                                @can('account-page-settings-view')
                                    <li
                                        class="dashboard__bottom__list__item @if (request()->routeIs(['admin.page.account.main.page'])) selected @endif">
                                        <a href="{{ route('admin.page.account.main.page') }}">
                                            {{ __('Account Page Settings') }} </a>
                                    </li>
                                @endcan
                                @can('introduction-page-settings-view')
                                    <li
                                        class="dashboard__bottom__list__item @if (request()->routeIs(['admin.page.account.introduction'])) selected @endif">
                                        <a href="{{ route('admin.page.account.introduction') }}">
                                            {{ __('Introduction Settings') }} </a>
                                    </li>
                                @endcan
                                @can('experience-page-settings-view')
                                    <li
                                        class="dashboard__bottom__list__item @if (request()->routeIs(['admin.page.account.experience'])) selected @endif">
                                        <a href="{{ route('admin.page.account.experience') }}">
                                            {{ __('Experience Settings') }} </a>
                                    </li>
                                @endcan
                                @can('education-page-settings-view')
                                    <li
                                        class="dashboard__bottom__list__item @if (request()->routeIs(['admin.page.account.education'])) selected @endif">
                                        <a href="{{ route('admin.page.account.education') }}">
                                            {{ __('Education Settings') }} </a>
                                    </li>
                                @endcan
                                @can('work-page-settings-view')
                                    <li
                                        class="dashboard__bottom__list__item @if (request()->routeIs(['admin.page.account.work'])) selected @endif">
                                        <a href="{{ route('admin.page.account.work') }}"> {{ __('Work Settings') }}
                                        </a>
                                    </li>
                                @endcan
                                @can('skill-page-settings-view')
                                    <li
                                        class="dashboard__bottom__list__item @if (request()->routeIs(['admin.page.account.skill'])) selected @endif">
                                        <a href="{{ route('admin.page.account.skill') }}"> {{ __('Skill Settings') }}
                                        </a>
                                    </li>
                                @endcan
                                @can('photo-page-settings-view')
                                    <li
                                        class="dashboard__bottom__list__item @if (request()->routeIs(['admin.page.account.rate.photo'])) selected @endif">
                                        <a href="{{ route('admin.page.account.rate.photo') }}">
                                            {{ __('Rate & Photo Settings') }} </a>
                                    </li>
                                @endcan
                            </ul>
                        </li>
                    </ul>
                </li>

                <li
                    class="dashboard__bottom__list__item has-children @if (request()->is('admin/general-settings*')) active open show @endif">
                    <a href="javascript:void(0)"> <i class="fa-solid fa-gear"></i> {{ __('General Settings') }}</a>
                    <ul class="submenu">
                        @can('reading')
                            <li class="dashboard__bottom__list__item @if (request()->routeIs(['admin.general.settings.reading'])) selected @endif">
                                <a href="{{ route('admin.general.settings.reading') }}"> {{ __('Reading') }} </a>
                            </li>
                        @endcan
                        @can('navbar-global-variant')
                            <li class="dashboard__bottom__list__item @if (request()->routeIs(['admin.general.settings.navbar.global.variant'])) selected @endif">
                                <a href="{{ route('admin.general.settings.navbar.global.variant') }}">
                                    {{ __('Navbar Global Variant') }} </a>
                            </li>
                        @endcan
                        @can('footer-global-variant')
                            <li class="dashboard__bottom__list__item @if (request()->routeIs(['admin.general.settings.footer.global.variant'])) selected @endif">
                                <a href="{{ route('admin.general.settings.footer.global.variant') }}">
                                    {{ __('Footer Global Variant') }} </a>
                            </li>
                        @endcan
                        @can('site-identity')
                            <li class="dashboard__bottom__list__item @if (request()->routeIs(['admin.general.settings.site.identity'])) selected @endif">
                                <a href="{{ route('admin.general.settings.site.identity') }}">
                                    {{ __('Site Identity') }} </a>
                            </li>
                        @endcan
                        @can('basic-settings')
                            <li class="dashboard__bottom__list__item @if (request()->routeIs(['admin.general.settings.basic'])) selected @endif">
                                <a href="{{ route('admin.general.settings.basic') }}"> {{ __('Basic Settings') }} </a>
                            </li>
                        @endcan
                        @can('color-settings')
                            <li class="dashboard__bottom__list__item @if (request()->routeIs(['admin.general.settings.color'])) selected @endif">
                                <a href="{{ route('admin.general.settings.color') }}"> {{ __('Color Settings') }} </a>
                            </li>
                        @endcan
                        @can('typography-settings')
                            <li class="dashboard__bottom__list__item @if (request()->routeIs(['admin.general.settings.typography'])) selected @endif">
                                <a href="{{ route('admin.general.settings.typography') }}">
                                    {{ __('Typography Settings') }} </a>
                            </li>
                        @endcan
                        @can('seo-settings')
                            <li class="dashboard__bottom__list__item @if (request()->routeIs(['admin.general.settings.seo'])) selected @endif">
                                <a href="{{ route('admin.general.settings.seo') }}"> {{ __('Seo Settings') }} </a>
                            </li>
                        @endcan
                        @can('third-party-script-settings')
                            <li class="dashboard__bottom__list__item @if (request()->routeIs(['admin.general.settings.third.party.script'])) selected @endif">
                                <a href="{{ route('admin.general.settings.third.party.script') }}">
                                    {{ __('Third Party Scripts') }} </a>
                            </li>
                        @endcan
                        @can('social-login-settings')
                            <li class="dashboard__bottom__list__item @if (request()->routeIs(['admin.general.settings.social.login'])) selected @endif">
                                <a href="{{ route('admin.general.settings.social.login') }}"> {{ __('Social Login') }}
                                </a>
                            </li>
                        @endcan
                        @can('email-template-settings')
                            <li class="dashboard__bottom__list__item @if (request()->routeIs(['admin.general.settings.email.template'])) selected @endif">
                                <a href="{{ route('admin.general.settings.email.template') }}">
                                    {{ __('Email Template') }} </a>
                            </li>
                        @endcan
                        @can('smtp-settings')
                            <li class="dashboard__bottom__list__item @if (request()->routeIs(['admin.general.settings.smtp'])) selected @endif">
                                <a href="{{ route('admin.general.settings.smtp') }}"> {{ __('SMTP Settings') }} </a>
                            </li>
                        @endcan
                        @can('custom-css-settings')
                            <li class="dashboard__bottom__list__item @if (request()->routeIs(['admin.general.settings.custom.css'])) selected @endif">
                                <a href="{{ route('admin.general.settings.custom.css') }}"> {{ __('Custom CSS') }} </a>
                            </li>
                        @endcan
                        @can('custom-js-settings')
                            <li class="dashboard__bottom__list__item @if (request()->routeIs(['admin.general.settings.custom.js'])) selected @endif">
                                <a href="{{ route('admin.general.settings.custom.js') }}"> {{ __('Custom JS') }} </a>
                            </li>
                        @endcan
                        @can('gdpr-settings')
                            <li class="dashboard__bottom__list__item @if (request()->routeIs(['admin.general.settings.gdpr'])) selected @endif">
                                <a href="{{ route('admin.general.settings.gdpr') }}"> {{ __('GDPR Settings') }} </a>
                            </li>
                        @endcan
                        @can('cache-settings')
                            <li class="dashboard__bottom__list__item @if (request()->routeIs(['admin.general.settings.cache'])) selected @endif">
                                <a href="{{ route('admin.general.settings.cache') }}"> {{ __('Cache Settings') }} </a>
                            </li>
                        @endcan
                        @can('database-upgrade')
                            <li class="dashboard__bottom__list__item @if (request()->routeIs(['admin.general.settings.database.upgrade'])) selected @endif">
                                <a href="{{ route('admin.general.settings.database.upgrade') }}">
                                    {{ __('Database Upgrade') }} </a>
                            </li>
                        @endcan
                        @can('generate-license-key')
                        <li class="dashboard__bottom__list__item @if (request()->routeIs(['admin.license.settings'])) selected @endif">
                            <a href="{{ route('admin.license.settings') }}">
                                {{ __('License Settings') }} </a>
                        </li>
                        <li class="dashboard__bottom__list__item @if (request()->routeIs(['admin.software.update.settings'])) selected @endif">
                            <a href="{{ route('admin.software.update.settings') }}">
                                {{ __('Check Update') }} </a>
                        </li>
                        @endcan
                    </ul>
                </li>

                <li class="dashboard__bottom__list__item has-children @if (request()->is('admin/payment-settings*')) active open show @endif">
                    <a href="javascript:void(0)"> <i class="fa-solid fa-briefcase"></i>
                        {{ __('Payment Settings') }}</a>
                    <ul class="submenu">
                        @can('payment-info-settings')
                            <li class="dashboard__bottom__list__item @if (request()->routeIs(['admin.payment.settings.info'])) selected @endif">
                                <a href="{{ route('admin.payment.settings.info') }}"> {{ __('Payment Info Settings') }}
                                </a>
                            </li>
                        @endcan
                        @can('payment-gateway-settings')
                            <li class="dashboard__bottom__list__item @if (request()->routeIs(['admin.payment.settings.gateway'])) selected @endif">
                                <a href="{{ route('admin.payment.settings.gateway') }}">
                                    {{ __('Payment Gateway Settings') }} </a>
                            </li>
                        @endcan
                    </ul>
                </li>

                <li class="dashboard__bottom__list__item has-children @if (request()->is('admin/plugins*')) active open show @endif">
                    <a href="javascript:void(0)"> <i class="fa-solid fa-gear"></i> {{ __('Appearance Settings') }}
                    </a>
                    <ul class="submenu">
                        @can('menu-list')
                            <li class="dashboard__bottom__list__item @if (request()->routeIs(['admin.menu']) || request()->routeIs(['admin.menu.edit'])) selected @endif">
                                <a href="{{ route('admin.menu') }}"> {{ __('Menu Builder') }} </a>
                            </li>
                        @endcan
                        @can('form-list')
                            <li class="dashboard__bottom__list__item @if (request()->routeIs(['admin.form', 'admin.form.edit'])) selected @endif">
                                <a href="{{ route('admin.form') }}"> {{ __('Form Builder') }} </a>
                            </li>
                        @endcan
                        @can('widget-list')
                            <li class="dashboard__bottom__list__item @if (request()->routeIs(['admin.widget'])) selected @endif">
                                <a href="{{ route('admin.widget') }}"> {{ __('Widget Builder') }} </a>
                            </li>
                        @endcan
                    </ul>
                </li>

                <li class="dashboard__bottom__list__item has-children @if (request()->is('admin/additional-settings*')) active open show @endif">
                    <a href="javascript:void(0)"> <i class="fa-solid fa-list"></i>{{ __('Additional Settings') }}</a>
                    <ul class="submenu">
                        <li class="dashboard__bottom__list__item @if (request()->routeIs(['admin.loader.settings'])) selected @endif">
                            <a href="{{ route('admin.loader.settings') }}">
                                {{ __('Loader Settings') }} </a>
                        </li>
                        <li class="dashboard__bottom__list__item @if (request()->routeIs(['admin.mouse.settings'])) selected @endif">
                            <a href="{{ route('admin.mouse.settings') }}">
                                {{ __('Mouse Pointer Settings') }} </a>
                        </li>
                        <li class="dashboard__bottom__list__item @if (request()->routeIs(['admin.bottom.to.top.settings'])) selected @endif">
                            <a href="{{ route('admin.bottom.to.top.settings') }}">
                                {{ __('Bottom to Top Button Settings') }} </a>
                        </li>
                        <li class="dashboard__bottom__list__item @if (request()->routeIs(['admin.bottom.to.top.settings'])) selected @endif">
                            <a href="{{ route('admin.sticky.menu.settings') }}">
                                {{ __('Sticky Menu Settings') }} </a>
                        </li>
                        <li class="dashboard__bottom__list__item @if (request()->routeIs(['admin.commission.display.settings'])) selected @endif">
                            <a href="{{ route('admin.commission.display.settings') }}">
                                {{ __('Display Commission Settings') }} </a>
                        </li>
                        <li class="dashboard__bottom__list__item @if (request()->routeIs(['admin.home.animation.settings'])) selected @endif">
                            <a href="{{ route('admin.home.animation.settings') }}">
                                {{ __('Home Page Animation Settings') }} </a>
                        </li>
                        <li class="dashboard__bottom__list__item @if (request()->routeIs(['admin.project.enable.disable.settings'])) selected @endif">
                            <a href="{{ route('admin.project.enable.disable.settings') }}">
                                {{ __('Project Enable Disable') }} </a>
                        </li>
                        <li class="dashboard__bottom__list__item @if (request()->routeIs(['admin.job.enable.disable.settings'])) selected @endif">
                            <a href="{{ route('admin.job.enable.disable.settings') }}">
                                {{ __('Job Enable Disable') }} </a>
                        </li>
                        <li class="dashboard__bottom__list__item @if (request()->routeIs(['admin.chat.email.settings'])) selected @endif">
                            <a href="{{ route('admin.chat.email.settings') }}">
                                {{ __('Chat Email Enable Disable') }} </a>
                        </li>
                        <li class="dashboard__bottom__list__item @if (request()->routeIs(['admin.file.extension.settings'])) selected @endif">
                            <a href="{{ route('admin.file.extension.settings') }}">
                                {{ __('File Extension Settings') }} </a>
                        </li>
                        <li class="dashboard__bottom__list__item @if (request()->routeIs(['admin.profile.switch.settings'])) selected @endif">
                            <a href="{{ route('admin.profile.switch.settings') }}">
                                {{ __('Profile Switch Settings') }} </a>
                        </li>
                    </ul>
                </li>

                <li class="dashboard__bottom__list__item has-children @if (request()->is('admin/email-template*')) active open show @endif">
                    <a href="javascript:void(0)"> <i class="fa-solid fa-envelope"></i> {{ __('Email Template') }}
                    </a>
                    <ul class="submenu">
                        @can('email-template-list')
                            <li class="dashboard__bottom__list__item @if (request()->routeIs(['admin.email.template.all'])) selected @endif">
                                <a href="{{ route('admin.email.template.all') }}"> {{ __('All Templates') }} </a>
                            </li>
                        @endcan
                    </ul>
                </li>

                <li
                    class="dashboard__bottom__list__item has-children @if (request()->is('admin/dynamic-pages*')) active open show @endif">
                    <a href="javascript:void(0)"> <i class="fa-solid fa-file-circle-plus"></i> {{ __('Pages') }}
                    </a>
                    <ul class="submenu">
                        @can('page-list')
                            <li class="dashboard__bottom__list__item @if (request()->routeIs(['admin.page.all'])) selected @endif">
                                <a href="{{ route('admin.page.all') }}"> {{ __('All Pages') }} </a>
                            </li>
                        @endcan
                        @can('page-create-new')
                            <li class="dashboard__bottom__list__item @if (request()->routeIs(['admin.page.new'])) selected @endif">
                                <a href="{{ route('admin.page.new') }}"> {{ __('Add New Page') }} </a>
                            </li>
                        @endcan
                        @can('manage-404-page')
                            <li class="dashboard__bottom__list__item @if (request()->routeIs(['admin.page.404'])) selected @endif">
                                <a href="{{ route('admin.page.404') }}"> {{ __('Manage 404 Page') }} </a>
                            </li>
                        @endcan
                        @can('manage-maintenance-page')
                            <li class="dashboard__bottom__list__item @if (request()->routeIs(['admin.page.maintenance'])) selected @endif">
                                <a href="{{ route('admin.page.maintenance') }}"> {{ __('Manage Maintenance Page') }}
                                </a>
                            </li>
                        @endcan
                    </ul>
                </li>

                <li class="dashboard__bottom__list__item @if (request()->is(['admin/faq*'])) active @endif">
                    <a href="{{ route('admin.faq.all') }}"> <i
                                class="fa-solid fa-question"></i>{{ __('Faq') }}</a>
                </li>

                @can('language-list')
                    <li class="dashboard__bottom__list__item @if (request()->is(['admin/languages*'])) active @endif">
                        <a href="{{ route('admin.languages') }}"> <i
                                class="fa-solid fa-chart-simple"></i>{{ __('Languages') }}</a>
                    </li>
                @endcan
                <li class="dashboard__bottom__list__item">
                    <a href="{{ route('admin.logout') }}"> <i
                            class="fa-solid fa-arrow-right-to-bracket"></i>{{ __('Log Out') }}</a>
                </li>
            </ul>
        </div>
    </div>
</div>
