@foreach($data->messages as $message)
    <x-chat::freelancer.message :$message :$data />
@endforeach
