<table class="DataTable_activation">
    <thead>
    <tr>
        <th>{{ __('ID') }}</th>
        <th>{{ __('Email') }}</th>
        <th>{{ __('Verified') }}</th>
        <th>{{ __('Action') }}</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($all_newsletter as $newsletter)
        <tr>
            <td>{{ $newsletter->id }}</td>
            <td>{{ $newsletter->email }}</td>
            <td>
                @if($newsletter->verified == 'yes')
                    <span>{{ __('yes') }}</span>
                @else
                    <span>{{ __('no') }}</span>
                @endif
            </td>
            <td>
                <x-status.table.select-action :title="__('Select Action')"/>
                <ul class="dropdown-menu status_dropdown__list">
                    <li class="status_dropdown__item">
                        <a class="btn dropdown-item status_dropdown__list__link send_email_to_userr"
                           data-bs-toggle="modal"
                           data-bs-target="#edit-request-modal"
                           data-id="{{ $newsletter->id }}"
                           data-email="{{ $newsletter->email }}">
                            {{ __('Send Email') }}
                        </a>
                    </li>
                    <li class="status_dropdown__item">
                        <a href="{{ route('admin.newsletter.verify.email.send',$newsletter->id) }}" class="btn dropdown-item status_dropdown__list__link">
                            {{ __('Send Verify Email') }}
                        </a>
                    </li>
                    <li class="status_dropdown__item"><x-popup.delete-popup :title="__('Delete Subscriber')" :url="route('admin.newsletter.email.delete',$newsletter->id)"/></li>
                </ul>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
<x-pagination.laravel-paginate :allData="$all_newsletter"/>
