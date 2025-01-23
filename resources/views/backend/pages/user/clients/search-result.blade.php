<x-validation.error />
<table class="table_activation">
    <thead>
    <tr>
        <th class="no-sort">
            <div class="mark-all-checkbox">
                <input type="checkbox" class="all-checkbox">
            </div>
        </th>
        <th>{{__('ID')}}</th>
        <th>{{__('Name')}}</th>
        <th>{{__('Email')}}</th>
        <th>{{__('Phone')}}</th>
        <th>{{__('Account Status')}}</th>
        <th>{{__('Identity Verify')}}</th>
        <th>{{__('Action')}}</th>
    </tr>
    </thead>
    <tbody>
    @if($all_users->total() >=1)
        @foreach($all_users as $user)
            <tr>
                <td> <x-bulk-action.bulk-delete-checkbox :id="$user->id"/> </td>
                <td>{{ $user->id }}</td>
                <td>{{ $user->first_name.' '.$user->last_name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->phone }} </td>
                @if($user->is_suspend == 1)
                <td> <x-status.table.account-status :status="$user->is_suspend"/> </td>
                @else
                <td> <x-status.table.active-inactive :status="$user->user_active_inactive_status"/> </td>
                @endif
                <td class="verified_status_load_{{$user->id}}">
                    <x-status.table.verified-status :status="$user->user_verified_status"/>
                    @if(!empty($user->identity_verify) && $user->identity_verify?->status == null)
                        <span class="badge bg-danger" >{{__('new')}}</span>
                    @endif
                </td>
                <td>
                    <x-status.table.select-action :title="__('Select Action')"/>
                    <ul class="dropdown-menu status_dropdown__list">
                        @can('user-details')
                        <li class="status_dropdown__item">
                            <a class="btn dropdown-item status_dropdown__list__link user_details"
                               data-bs-toggle="modal"
                               data-bs-target="#userDetailsModal"
                               data-user_id="{{ $user->id }}"
                               data-type="{{ $user->user_type }}"
                               data-hourly_rate="{{ $user->hourly_rate }}"
                               data-first_name="{{ $user->first_name }}"
                               data-last_name="{{ $user->last_name }}"
                               data-username="{{ $user->username }}"
                               data-email="{{ $user->email }}"
                               data-phone="{{ $user->phone }}"
                               data-country="{{ optional($user->user_country)->country }}"
                               data-country_id="{{ $user->country_id }}"
                               data-state="{{ optional($user->user_state)->state }}"
                               data-state_id="{{ $user->state_id }}"
                               data-city="{{ optional($user->user_city)->city }}"
                               data-city_id="{{ $user->city_id }}">
                                {{ __('View User Details') }}
                            </a>
                        </li>
                        @endcan
                        @can('user-identity-details')
                        <li class="status_dropdown__item">
                            <a class="btn dropdown-item status_dropdown__list__link user_identity_details"
                               data-bs-toggle="modal"
                               data-bs-target="#userIdentityModal"
                               data-user_id="{{ $user->id }}">
                                {{ __('View Identity Details') }}
                            </a>
                        </li>
                        @endcan
                        @can('user-password-change')
                        <li class="status_dropdown__item">
                            <a class="btn dropdown-item status_dropdown__list__link user_password_update_modal"
                               data-bs-toggle="modal"
                               data-bs-target="#userPasswordModal"
                               data-user_id_for_change_password="{{ $user->id }}">
                                {{ __('Change Password') }}
                            </a>
                        </li>
                        @endcan
                        @if($user->google_2fa_enable_disable_disable == 1)
                        <li class="status_dropdown__item">
                            <x-status.table.-2fa :title="__('Disable 2FA')" :url="route('admin.user.disable._2fa',$user->id)"/>
                        </li>
                        @endif
                        @if($user->is_email_verified == 0)
                            <li class="status_dropdown__item">
                                <x-status.table.email-verify :title="__('Verify User Email')" :url="route('admin.user.verify.email',$user->id)"/>
                            </li>
                        @endif
                        @can('user-delete')
                        <li class="status_dropdown__item">
                            <x-popup.delete-popup :title="__('Delete User')" :url="route('admin.user.delete',$user->id)"/>
                        </li>
                        @endcan
                        @can('user-account-status-change')
                        <li class="status_dropdown__item">
                            <x-status.table.status-change :title="__('Change Account Status')" :url="route('admin.user.status',$user->id)"/>
                        </li>
                        @endcan
                        @can('user-account-status-change')
                        <li class="status_dropdown__item">
                            @if($user->is_suspend == 1)
                                <x-status.table.status-change :class="'btn dropdown-item status_dropdown__list__link unsuspend_user_account'" :title="__('Unsuspend User')" :url="route('admin.account.unsuspend',$user->id)"/>
                            @else
                                <x-status.table.status-change :class="'btn dropdown-item status_dropdown__list__link suspend_user_account'" :title="__('Suspend User')" :url="route('admin.account.suspend',$user->id)"/>
                            @endif
                        </li>
                        @endcan
                        {{--security manage code--}}
                        @if(moduleExists('SecurityManage'))
                            @php $user->freeze_job == 'freeze' ? $is_job_freeze = 'Job Create Edit Unfreeze' : $is_job_freeze = 'Job Create Edit Freeze'; @endphp
                            @php $user->freeze_order_create == 'freeze' ? $is_order_freeze = 'New Order Create Unfreeze' : $is_order_freeze = 'New Order Create Freeze'; @endphp
                            @php $user->freeze_chat == 'freeze' ? $is_chat_freeze = 'Chat Unfreeze' : $is_chat_freeze = 'Chat Freeze'; @endphp

                            <x-status.table.status-change :title="__($is_job_freeze)" :url="route('admin.client.job.freeze',$user->id)"/>
                            <x-status.table.status-change :title="__($is_order_freeze)" :url="route('admin.client.new.order.freeze',$user->id)"/>
                            <x-status.table.status-change :title="__($is_chat_freeze)" :url="route('admin.user.chat.freeze',$user->id)"/>
                        @endif
                    </ul>
                </td>
            </tr>
        @endforeach
    @else
        <x-table.no-data-found :colspan="'7'" :class="'text-danger text-center py-5'" />
    @endif
    </tbody>
</table>
<x-pagination.laravel-paginate :allData="$all_users"/>
