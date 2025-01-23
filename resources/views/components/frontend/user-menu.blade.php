@if (Auth::guard('web')->check())
    <div class="navbar-right-content show-nav-content">
        <div class="single-right-content">
            <div class="navbar-right-flex">

                <x-frontend.menu-searchbar />
                <x-frontend.menu-chat-bookmark />
                <x-frontend.menu-notification />
                <x-frontend.menu-user-items />
            </div>
        </div>
    </div>
@else
    <x-frontend.menu-search-login-register />
@endif