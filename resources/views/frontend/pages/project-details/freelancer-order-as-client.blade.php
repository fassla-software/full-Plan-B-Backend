<form action="{{ route('client.message.send') }}" method="post"
      enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="freelancer_id" id="freelancer_id"
           value="{{ $project->user_id }}">
    <input type="hidden" name="from_user" id="from_user"
           value="1">
    <input type="hidden" name="project_id" id="project_id"
           value="{{ $project->id }}">
    <button type="submit" class="btn-profile btn-outline-gray"><i
                class="fa-regular fa-comments"></i>
        {{ __('Contact Me') }}</button>
</form>
@if(moduleExists('SecurityManage'))
    @if(Auth::guard('web')->user()->freeze_order_create == 'freeze')
        <a href="#/" class="btn-profile btn-bg-1 @if(Auth::guard('web')->user()->freeze_order_create == 'freeze') disabled-link @endif">
            {{ __('Continue to Order') }}
        </a>
    @else
        <a href="#/"
           class="btn-profile btn-bg-1 basic_standard_premium"
           data-project_id="{{ $project->id }}" data-bs-toggle="modal"
           data-bs-target="#paymentGatewayModal">{{ __('Continue to Order') }}
        </a>
    @endif
@else
    <a href="#/"
       class="btn-profile btn-bg-1 basic_standard_premium"
       data-project_id="{{ $project->id }}" data-bs-toggle="modal"
       data-bs-target="#paymentGatewayModal">{{ __('Continue to Order') }}
    </a>
@endif