
    <button class="order_sort btn-profile btn-bg-1" data-val="all">
        {{ __('All') }} <span> ({{ $orders->total() ?? '' }}) </span>
    </button>
    <button class="order_sort" data-val="active">
        {{ __('Active') }} <span>({{ $active_orders }}) </span>
    </button>
    <button class="order_sort" data-val="queue">
        {{ __('Queue') }} <span> ({{ $queue_orders }}) </span>
    </button>
    <button class="order_sort" data-val="cancel">
        {{ __('Cancelled') }} <span>({{ $cancel_orders }}) </span>
    </button>
    <button class="order_sort" data-val="complete">
        {{ __('Completed') }} <span>({{ $complete_orders }}) </span>
    </button>

    <input type="hidden" id="set_order_type_value" value="all">

