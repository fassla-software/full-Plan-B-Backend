<table class="DataTable_activation">
    <thead>
    <tr>
        <th class="no-sort">
            <div class="mark-all-checkbox">
                <input type="checkbox" class="all-checkbox">
            </div>
        </th>
        <th>{{__('ID')}}</th>
        <th>{{__('Type')}}</th>
        <th>{{__('Title')}}</th>
        <th>{{__('Logo')}}</th>
        <th>{{__('Price')}}</th>
        <th>{{__('Connect')}}</th>
        <th>{{__('Status')}}</th>
        <th>{{__('Action')}}</th>
    </tr>
    </thead>
    <tbody>
    @foreach($all_subscriptions as $subs)
        <tr>
            <td> <x-bulk-action.bulk-delete-checkbox :id="$subs->id"/> </td>
            <td>{{ $subs->id }}</td>
            <td>{{ $subs->subscription_type?->type }}</td>
            <td>{{ $subs->title }}</td>
            <td>
                <span class="img_100">
                    {!! render_image_markup_by_attachment_id($subs->logo); !!}
                </span>
                @php $subscription_logo = get_attachment_image_by_id($subs->logo,null,true); @endphp
                @if (!empty($subscription_logo))
                    @php  $img_url = $subscription_logo['img_url']; @endphp
                @endif
            </td>
            <td>{{ float_amount_with_currency_symbol($subs->price) }}</td>
            <td>{{ $subs->limit }}</td>
            <td><x-status.table.active-inactive :status="$subs->status"/></td>
            <td>
                <x-status.table.select-action :title="__('Select Action')"/>
                <ul class="dropdown-menu status_dropdown__list">
                    @can('subscription-edit')
                    <li class="status_dropdown__item">
                        <a href="{{ route('admin.subscription.edit',$subs->id) }}" class="btn dropdown-item status_dropdown__list__link">{{ __('Edit Subscription') }}</a>
                    </li>
                    @endcan
                    @can('subscription-status-change')
                    <li class="status_dropdown__item"><x-status.table.status-change :title="__('Change Status')" :url="route('admin.subscription.status',$subs->id)"/></li>
                    @endcan
                    @can('subscription-delete')
                    <li class="status_dropdown__item"><x-popup.delete-popup :title="__('Delete Subscription')" :url="route('admin.subscription.delete',$subs->id)"/></li>
                    @endcan
                </ul>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
<x-pagination.laravel-paginate :allData="$all_subscriptions"/>
