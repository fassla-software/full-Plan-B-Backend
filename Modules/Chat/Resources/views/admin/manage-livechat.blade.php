@extends("backend.admin-master")

@section('site-title', __('Livechat settings'))

@section('style')

@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h2 class="title">{{ __("Live Chat settings") }}</h2>
        </div>
        <div class="card-body">
            <x-msg.flash />
            <x-msg.error />
            <form action="{{ route('admin.livechat.settings') }}" method="post" class="col-md-6">
                @csrf
                <div class="form-group">
                    <label for="">{{ __("Pusher App Id") }}</label>
                    <input type="text" value="{{ env("pusher_app_id") }}" name="PUSHER_APP_ID" class="form-control" placeholder="{{ __("Write pusher app id") }}">
                </div>
                <div class="form-group">
                    <label for="">{{ __("Pusher App Key") }}</label>
                    <input type="text" value="{{ env("pusher_app_key") }}" name="PUSHER_APP_KEY" class="form-control" placeholder="{{ __("Write pusher app key") }}">
                </div>
                <div class="form-group">
                    <label for="">{{ __("Pusher App Secret") }}</label>
                    <input type="text" value="{{ env("pusher_app_secret") }}" name="PUSHER_APP_SECRET" class="form-control" placeholder="{{ __("Write pusher app secret") }}">
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-info">{{ __("Submit") }}</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('script')

@endsection