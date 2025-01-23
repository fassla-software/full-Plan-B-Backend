<table class="DataTable_activation">
    <thead>
    <tr>
        <th>{{__('SN')}}</th>
        <th>{{__('Title')}}</th>
        <th>{{__('Action')}}</th>
    </tr>
    </thead>
    <tbody>
        <tr>
            <td><strong>1</strong></td>
            <td>
                {{ __('User Register Email') }}
                <p><small class="text-info">{{__('This email will send to the admin when a new user register in the system.')}}</small></p>
            </td>
            <td>
                @can('email-template-update')
                <a href="{{ route('admin.user.register') }}" class="btn-profile btn-bg-1"> {{ __('Edit Template') }}</a>
                @endcan
            </td>
        </tr>
        <tr>
            <td><strong>2</strong></td>
            <td>
                {{ __('User Register Welcome Email') }}
                <p><small class="text-info">{{__('This email will send to a user when a new user register in the system.')}}</small></p>
            </td>
            <td>
                @can('email-template-update')
                    <a href="{{ route('admin.user.register.welcome') }}" class="btn-profile btn-bg-1"> {{ __('Edit Template') }}</a>
                @endcan
            </td>
        </tr>
        <tr>
            <td><strong>3</strong></td>
            <td>
                {{ __('User Info and Username Update Email') }}
                <p><small class="text-info">{{__('This email will send when the user info and username will updated by admin.')}}</small></p>
            </td>
            <td>
                @can('email-template-update')
                <a href="{{ route('admin.user.info.update.mail') }}" class="btn-profile btn-bg-1"> {{ __('Edit Template') }}</a>
                @endcan
            </td>
        </tr>
        <tr>
            <td><strong>4</strong></td>
            <td>
                {{ __('User Password Change Email') }}
                <p><small class="text-info">{{__('This email will send when the user password will change by admin.')}}</small></p>
            </td>
            <td>
                @can('email-template-update')
                <a href="{{ route('admin.user.password.change.mail') }}" class="btn-profile btn-bg-1"> {{ __('Edit Template') }}</a>
                @endcan
            </td>
        </tr>
        <tr>
            <td><strong>5</strong></td>
            <td>
                {{ __('User Identity Verify Request Email') }}
                <p><small class="text-info">{{__('This email will send when the user make a verification request to verify him/her.')}}</small></p>
            </td>
            <td>
                @can('email-template-update')
                <a href="{{ route('admin.user.identity.verify') }}" class="btn-profile btn-bg-1"> {{ __('Edit Template') }}</a>
                @endcan
            </td>
        </tr>
        <tr>
            <td><strong>6</strong></td>
            <td>
                {{ __('User Identity Verify Confirm Email') }}
                <p><small class="text-info">{{__('This email will send when the user identity verification successfully done.')}}</small></p>
            </td>
            <td>
                @can('email-template-update')
                <a href="{{ route('admin.user.identity.verify.confirm') }}" class="btn-profile btn-bg-1"> {{ __('Edit Template') }}</a>
                @endcan
            </td>
        </tr>
        <tr>
            <td><strong>7</strong></td>
            <td>
                {{ __('User Identity Reverification Email') }}
                <p><small class="text-info">{{__('This email will send when the user will reverification his/her identity.')}}</small></p>
            </td>
            <td>
                @can('email-template-update')
                <a href="{{ route('admin.user.identity.reverification') }}" class="btn-profile btn-bg-1"> {{ __('Edit Template') }}</a>
                @endcan
            </td>
        </tr>
        <tr>
            <td><strong>8</strong></td>
            <td>
                {{ __('User Identity Decline Email') }}
                <p><small class="text-info">{{__('This email will send when the user identity verification failed.')}}</small></p>
            </td>
            <td>
                @can('email-template-update')
                <a href="{{ route('admin.user.identity.decline') }}" class="btn-profile btn-bg-1"> {{ __('Edit Template') }}</a>
                @endcan
            </td>
        </tr>

        <tr>
            <td><strong>9</strong></td>
            <td>
                {{ __('User Status Active Email') }}
                <p><small class="text-info">{{__('This email will send when the user status successfully activate.')}}</small></p>
            </td>
            <td>
                @can('email-template-update')
                <a href="{{ route('admin.user.status.active.mail') }}" class="btn-profile btn-bg-1"> {{ __('Edit Template') }}</a>
                @endcan
            </td>
        </tr>
        <tr>
            <td><strong>10</strong></td>
            <td>
                {{ __('User Status Inactive Email') }}
                <p><small class="text-info">{{__('This email will send when the user status successfully inactivate.')}}</small></p>
            </td>
            <td>
                @can('email-template-update')
                <a href="{{ route('admin.user.status.inactive.mail') }}" class="btn-profile btn-bg-1"> {{ __('Edit Template') }}</a>
                @endcan
            </td>
        </tr>
        <tr>
            <td><strong>11</strong></td>
            <td>
                {{ __('Disable 2FA Email') }}
                <p><small class="text-info">{{__('This email will send when the user 2fa(2 factor authentication) successfully inactivate.')}}</small></p>
            </td>
            <td>
                @can('email-template-update')
                <a href="{{ route('admin.user._2fa.disable.mail') }}" class="btn-profile btn-bg-1"> {{ __('Edit Template') }}</a>
                @endcan
            </td>
        </tr>
        <tr>
            <td><strong>12</strong></td>
            <td>
                {{ __('User Verified Email') }}
                <p><small class="text-info">{{__('This email will send when the admin verified user email.')}}</small></p>
            </td>
            <td>
                @can('email-template-update')
                <a href="{{ route('admin.user.verified.mail') }}" class="btn-profile btn-bg-1"> {{ __('Edit Template') }}</a>
                @endcan
            </td>
        </tr>
        <tr>
            <td><strong>13</strong></td>
            <td>
                {{ __('Project Create Email') }}
                <p><small class="text-info">{{__('This email will send to admin when the user create a new project and request to admin publish it.')}}</small></p>
            </td>
            <td>
                @can('email-template-update')
                <a href="{{ route('admin.user.project.create') }}" class="btn-profile btn-bg-1"> {{ __('Edit Template') }}</a>
                @endcan
            </td>
        </tr>
        <tr>
            <td><strong>14</strong></td>
            <td>
                {{ __('Project Activate Email') }}
                <p><small class="text-info">{{__('This email will send when the admin activate the project.')}}</small></p>
            </td>
            <td>
                @can('email-template-update')
                <a href="{{ route('admin.user.project.activate') }}" class="btn-profile btn-bg-1"> {{ __('Edit Template') }}</a>
                @endcan
            </td>
        </tr>
        <tr>
            <td><strong>15</strong></td>
            <td>
                {{ __('Project Inactivate Email') }}
                <p><small class="text-info">{{__('This email will send when the admin Inactivate the project.')}}</small></p>
            </td>
            <td>
                @can('email-template-update')
                <a href="{{ route('admin.user.project.inactivate') }}" class="btn-profile btn-bg-1"> {{ __('Edit Template') }}</a>
                @endcan
            </td>
        </tr>
        <tr>
            <td><strong>16</strong></td>
            <td>
                {{ __('Project Reject Email') }}
                <p><small class="text-info">{{__('This email will send when the admin reject the project.')}}</small></p>
            </td>
            <td>
                @can('email-template-update')
                <a href="{{ route('admin.user.project.decline') }}" class="btn-profile btn-bg-1"> {{ __('Edit Template') }}</a>
                @endcan
            </td>
        </tr>
        <tr>
            <td><strong>17</strong></td>
            <td>
                {{ __('Project Edit Email') }}
                <p><small class="text-info">{{__('This email will send to admin when the user edit a project.')}}</small></p>
            </td>
            <td>
                @can('email-template-update')
                <a href="{{ route('admin.user.project.edit') }}" class="btn-profile btn-bg-1"> {{ __('Edit Template') }}</a>
                @endcan
            </td>
        </tr>
        <tr>
            <td><strong>18</strong></td>
            <td>
                {{ __('Deposit Email to User') }}
                <p><small class="text-info">{{__('This email will send to user when a user successfully deposit his wallet.')}}</small></p>
            </td>
            <td>
                @can('email-template-update')
                <a href="{{ route('admin.user.wallet.deposit') }}" class="btn-profile btn-bg-1"> {{ __('Edit Template') }}</a>
                @endcan
            </td>
        </tr>
        <tr>
            <td><strong>19</strong></td>
            <td>
                {{ __('Deposit Email to Admin') }}
                <p><small class="text-info">{{__('This email will send to admin when the user deposit to his wallet.')}}</small></p>
            </td>
            <td>
                @can('email-template-update')
                <a href="{{ route('admin.user.wallet.deposit.receive.mail') }}" class="btn-profile btn-bg-1"> {{ __('Edit Template') }}</a>
                @endcan
            </td>
        </tr>

        <tr>
            <td><strong>20</strong></td>
            <td>
                {{ __('Job Create Email') }}
                <p><small class="text-info">{{__('This email will send to admin when the user create a new job and request to admin publish it.')}}</small></p>
            </td>
            <td>
                @can('email-template-update')
                <a href="{{ route('admin.user.job.create') }}" class="btn-profile btn-bg-1"> {{ __('Edit Template') }}</a>
                @endcan
            </td>
        </tr>
        <tr>
            <td><strong>21</strong></td>
            <td>
                {{ __('Job Activate Email') }}
                <p><small class="text-info">{{__('This email will send when the admin activate the job.')}}</small></p>
            </td>
            <td>
                @can('email-template-update')
                <a href="{{ route('admin.user.job.activate') }}" class="btn-profile btn-bg-1"> {{ __('Edit Template') }}</a>
                @endcan
            </td>
        </tr>
        <tr>
            <td><strong>22</strong></td>
            <td>
                {{ __('Job Inactivate Email') }}
                <p><small class="text-info">{{__('This email will send when the admin Inactivate the job.')}}</small></p>
            </td>
            <td>
                @can('email-template-update')
                <a href="{{ route('admin.user.job.inactivate') }}" class="btn-profile btn-bg-1"> {{ __('Edit Template') }}</a>
                @endcan
            </td>
        </tr>
        <tr>
            <td><strong>23</strong></td>
            <td>
                {{ __('Job Reject Email') }}
                <p><small class="text-info">{{__('This email will send when the admin reject the job.')}}</small></p>
            </td>
            <td>
                @can('email-template-update')
                <a href="{{ route('admin.user.job.decline') }}" class="btn-profile btn-bg-1"> {{ __('Edit Template') }}</a>
                @endcan
            </td>
        </tr>
        <tr>
            <td><strong>24</strong></td>
            <td>
                {{ __('Job Edit Email') }}
                <p><small class="text-info">{{__('This email will send to admin when the user edit a job.')}}</small></p>
            </td>
            <td>
                @can('email-template-update')
                <a href="{{ route('admin.user.job.edit') }}" class="btn-profile btn-bg-1"> {{ __('Edit Template') }}</a>
                @endcan
            </td>
        </tr>
        <tr>
            <td><strong>25</strong></td>
            <td>
                {{ __('Subscription Purchase Email to User') }}
                <p><small class="text-info">{{__('This email will send to user when the user buy a subscription.')}}</small></p>
            </td>
            <td>
                @can('email-template-update')
                <a href="{{ route('admin.user.subscription.buy') }}" class="btn-profile btn-bg-1"> {{ __('Edit Template') }}</a>
                @endcan
            </td>
        </tr>
        <tr>
            <td><strong>26</strong></td>
            <td>
                {{ __('Subscription Purchase Email to Admin') }}
                <p><small class="text-info">{{__('This email will send to admin when the user buy a subscription.')}}</small></p>
            </td>
            <td>
                @can('email-template-update')
                <a href="{{ route('admin.user.subscription.buy.receive.mail') }}" class="btn-profile btn-bg-1"> {{ __('Edit Template') }}</a>
                @endcan
            </td>
        </tr>
        <tr>
            <td><strong>27</strong></td>
            <td>
                {{ __('Manual Subscription Payment Complete Email To User') }}
                <p><small class="text-info">{{__('This email will send to user when the admin change the payment status pending to complete.')}}</small></p>
            </td>
            <td>
                @can('email-template-update')
                <a href="{{ route('admin.user.subscription.manual.payment.complete.to.user') }}" class="btn-profile btn-bg-1"> {{ __('Edit Template') }}</a>
                @endcan
            </td>
        </tr>
        <tr>
            <td><strong>28</strong></td>
            <td>
                {{ __('Manual Subscription Payment Complete Email To Admin') }}
                <p><small class="text-info">{{__('This email will send to super admin when any admin change the payment status pending to complete.')}}</small></p>
            </td>
            <td>
                @can('email-template-update')
                <a href="{{ route('admin.user.subscription.manual.payment.complete.to.admin') }}" class="btn-profile btn-bg-1"> {{ __('Edit Template') }}</a>
                @endcan
            </td>
        </tr>
        <tr>
            <td><strong>29</strong></td>
            <td>
                {{ __('Subscription Active Email To User') }}
                <p><small class="text-info">{{__('This email will send to user when the admin change the subscription status inactive to active.')}}</small></p>
            </td>
            <td>
                @can('email-template-update')
                <a href="{{ route('admin.user.subscription.active.email') }}" class="btn-profile btn-bg-1"> {{ __('Edit Template') }}</a>
                @endcan
            </td>
        </tr>
        <tr>
            <td><strong>30</strong></td>
            <td>
                {{ __('Subscription Inactive Email To User') }}
                <p><small class="text-info">{{__('This email will send to user when the admin change the subscription status active to inactive.')}}</small></p>
            </td>
            <td>
                @can('email-template-update')
                <a href="{{ route('admin.user.subscription.inactive.email') }}" class="btn-profile btn-bg-1"> {{ __('Edit Template') }}</a>
                @endcan
            </td>
        </tr>
        <tr>
            <td><strong>31</strong></td>
            <td>
                {{ __('Order Hold Email To User') }}
                <p><small class="text-info">{{__('This email will send to user when the admin hold an order.')}}</small></p>
            </td>
            <td>
                @can('email-template-update')
                <a href="{{ route('admin.order.hold.email') }}" class="btn-profile btn-bg-1"> {{ __('Edit Template') }}</a>
                @endcan
            </td>
        </tr>
        <tr>
            <td><strong>32</strong></td>
            <td>
                {{ __('Order Unhold Email To User') }}
                <p><small class="text-info">{{__('This email will send to user when the admin unhold an order.')}}</small></p>
            </td>
            <td>
                @can('email-template-update')
                <a href="{{ route('admin.order.unhold.email') }}" class="btn-profile btn-bg-1"> {{ __('Edit Template') }}</a>
                @endcan
            </td>
        </tr>
        <tr>
            <td><strong>33</strong></td>
            <td>
                {{ __('Account Suspend Email To User') }}
                <p><small class="text-info">{{__('This email will send to user when the admin suspend an account.')}}</small></p>
            </td>
            <td>
                @can('email-template-update')
                <a href="{{ route('admin.user.account.suspend.email') }}" class="btn-profile btn-bg-1"> {{ __('Edit Template') }}</a>
                @endcan
            </td>
        </tr>
        <tr>
            <td><strong>34</strong></td>
            <td>
                {{ __('Account Unsuspend Email To User') }}
                <p><small class="text-info">{{__('This email will send to user when the admin active an account.')}}</small></p>
            </td>
            <td>
                @can('email-template-update')
                <a href="{{ route('admin.user.account.unsuspend.email') }}" class="btn-profile btn-bg-1"> {{ __('Edit Template') }}</a>
                @endcan
            </td>
        </tr>
        <tr>
            <td><strong>35</strong></td>
            <td>
                {{ __('Order Manual Payment Complete Email To Client') }}
                <p><small class="text-info">{{__('This email will send to client when the admin change the payment status pending to complete.')}}</small></p>
            </td>
            <td>
                @can('email-template-update')
                <a href="{{ route('admin.order.manual.payment.complete.email') }}" class="btn-profile btn-bg-1"> {{ __('Edit Template') }}</a>
                @endcan
            </td>
        </tr>
        <tr>
            <td><strong>36</strong></td>
            <td>
                {{ __('Support Ticket Email') }}
                <p><small class="text-info">{{__('This email will send to user when the admin create a ticket. Also send to admin  when a user create a ticket.')}}</small></p>
            </td>
            <td>
                @can('email-template-update')
                <a href="{{ route('admin.support.ticket.email') }}" class="btn-profile btn-bg-1"> {{ __('Edit Template') }}</a>
                @endcan
            </td>
        </tr>
        <tr>
            <td><strong>37</strong></td>
            <td>
                {{ __('Support Ticket Email') }}
                <p><small class="text-info">{{__('This email will send to user when the admin send a support ticket message. Also send to admin  when a user send  a support ticket message.')}}</small></p>
            </td>
            <td>
                @can('email-template-update')
                <a href="{{ route('admin.support.ticket.message.email') }}" class="btn-profile btn-bg-1"> {{ __('Edit Template') }}</a>
                @endcan
            </td>
        </tr>
        @if(moduleExists('PromoteFreelancer'))
            <tr>
            <td><strong>38</strong></td>
            <td>
                {{ __('Promote Package Purchase Email to User') }}
                <p><small class="text-info">{{__('This email will send to user when the user buy a package for promotion.')}}</small></p>
            </td>
            <td>
                @can('email-template-update')
                    <a href="{{ route('admin.promote.package.email.settings.to.user') }}" class="btn-profile btn-bg-1"> {{ __('Edit Template') }}</a>
                @endcan
            </td>
        </tr>
        <tr>
            <td><strong>39</strong></td>
            <td>
                {{ __('Promote Package  Purchase Email to Admin') }}
                <p><small class="text-info">{{__('This email will send to admin when the user buy a package for promotion.')}}</small></p>
            </td>
            <td>
                @can('email-template-update')
                    <a href="{{ route('admin.promote.package.email.settings.to.admin') }}" class="btn-profile btn-bg-1"> {{ __('Edit Template') }}</a>
                @endcan
            </td>
        </tr>
        <tr>
            <td><strong>40</strong></td>
            <td>
                {{ __('Promotion Package Purchase Manual Payment Complete Email To User') }}
                <p><small class="text-info">{{__('This email will send to user when the admin change the payment status pending to complete.')}}</small></p>
            </td>
            <td>
                @can('email-template-update')
                    <a href="{{ route('admin.promote.package.manual.payment.pending.to.complete') }}" class="btn-profile btn-bg-1"> {{ __('Edit Template') }}</a>
                @endcan
            </td>
        </tr>
        @endif
    </tbody>
</table>
