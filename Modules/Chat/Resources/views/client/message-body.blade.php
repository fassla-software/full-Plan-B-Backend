@foreach($data->messages as $message)
    <x-chat::client.message :$message :$data />
@endforeach
