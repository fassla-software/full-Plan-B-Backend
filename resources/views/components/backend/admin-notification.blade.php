@if($notification->type =='Create Project' || $notification->type =='Edit Project')
    <a href="{{ route('admin.project.details',$notification->identity) }}" class="dashboard__notification__list__item click-notification">
        <div class="dashboard__notification__list__left">
            <div class="dashboard__notification__list__icon decline">
                <i class="fas fa-edit"></i>
            </div>
        </div>
        <div class="dashboard__notification__list__content">
            <span class="dashboard__notification__list__content__title">{{ $notification->message ?? '' }} - <strong>#{{ $notification->identity }}</strong></span> <br>
            <span class="dashboard__notification__list__content__time">{{ $notification->created_at->toFormattedDateString() }}</span>
        </div>
    </a>
@endif

@if($notification->type =='Deposit Amount')
    <a href="{{ route('admin.wallet.history.details',$notification->identity) }}" class="dashboard__notification__list__item click-notification">
        <div class="dashboard__notification__list__left">
            <div class="dashboard__notification__list__icon decline">
                <i class="fas fa-dollar"></i>
            </div>
        </div>
        <div class="dashboard__notification__list__content">
            <span class="dashboard__notification__list__content__title">{{ $notification->message ?? '' }} - <strong>#{{ $notification->identity }}</strong></span> <br>
            <span class="dashboard__notification__list__content__time">{{ $notification->created_at->toFormattedDateString() }}</span>
        </div>
    </a>
@endif

@if($notification->type =='Create Job' || $notification->type =='Edit Job')
    <a href="{{ route('admin.job.details',$notification->identity) }}" class="dashboard__notification__list__item click-notification">
        <div class="dashboard__notification__list__left">
            <div class="dashboard__notification__list__icon decline">
                <i class="fa-solid fa-file-circle-plus"></i>
            </div>
        </div>
        <div class="dashboard__notification__list__content">
            <span class="dashboard__notification__list__content__title">{{ $notification->message ?? '' }} - <strong>#{{ $notification->identity }}</strong></span> <br>
            <span class="dashboard__notification__list__content__time">{{ $notification->created_at->toFormattedDateString() }}</span>
        </div>
    </a>
@endif

@if($notification->type =='Buy Subscription')
    <a href="{{ route('admin.user.subscription.read.unread',$notification->identity) }}" class="dashboard__notification__list__item click-notification">
        <div class="dashboard__notification__list__left">
            <div class="dashboard__notification__list__icon decline">
                {{ site_currency_symbol() }}
            </div>
        </div>
        <div class="dashboard__notification__list__content">
            <span class="dashboard__notification__list__content__title">{{ $notification->message ?? '' }} - <strong>#{{ $notification->identity }}</strong></span> <br>
            <span class="dashboard__notification__list__content__time">{{ $notification->created_at->toFormattedDateString() }}</span>
        </div>
    </a>
@endif

@if($notification->type =='Order')
    <a href="{{ route('admin.order.details',$notification->identity) }}" class="dashboard__notification__list__item click-notification">
        <div class="dashboard__notification__list__left">
            <div class="dashboard__notification__list__icon decline">
                <i class="fa-solid fa-clipboard-list"></i>
            </div>
        </div>
        <div class="dashboard__notification__list__content">
            <span class="dashboard__notification__list__content__title">{{ $notification->message ?? '' }} - <strong>#{{ $notification->identity }}</strong></span> <br>
            <span class="dashboard__notification__list__content__time">{{ $notification->created_at->toFormattedDateString() }}</span>
        </div>
    </a>
@endif

@if($notification->type =='Ticket')
    <a href="{{ route('admin.ticket.details',$notification->identity) }}" class="dashboard__notification__list__item click-notification">
        <div class="dashboard__notification__list__left">
            <div class="dashboard__notification__list__icon decline">
                <i class="fa-solid fa-ticket"></i>
            </div>
        </div>
        <div class="dashboard__notification__list__content">
            <span class="dashboard__notification__list__content__title">{{ $notification->message ?? '' }} - <strong>#{{ $notification->identity }}</strong></span> <br>
            <span class="dashboard__notification__list__content__time">{{ $notification->created_at->toFormattedDateString() }}</span>
        </div>
    </a>
@endif

@if($notification->type =='Withdraw')
    <a href="{{ route('admin.wallet.withdraw.request',$notification->identity) }}" class="dashboard__notification__list__item click-notification">
        <div class="dashboard__notification__list__left">
            <div class="dashboard__notification__list__icon decline">
                {{ site_currency_symbol() }}
            </div>
        </div>
        <div class="dashboard__notification__list__content">
            <span class="dashboard__notification__list__content__title">{{ $notification->message ?? '' }} - <strong>#{{ $notification->identity }}</strong></span> <br>
            <span class="dashboard__notification__list__content__time">{{ $notification->created_at->toFormattedDateString() }}</span>
        </div>
    </a>
@endif
