<style>
    .alert-success {
        border-color: #f2f2f2;
        border-left: 5px solid #319a31;
        background-color: #f2f2f2;
        color: #333;
        border-radius: 0;
        padding: 5px;
    }
    .alert-danger {
        border-color: #f2f2f2;
        border-left: 5px solid #c69500;
        background-color: #f2f2f2;
        color: #333;
        border-radius: 0;
        padding: 5px;
    }
    .alert-cancel {
        border-color: #f2f2f2;
        border-left: 5px solid #f44336;
        background-color: #f2f2f2;
        color: #333;
        border-radius: 0;
        padding: 5px;
    }
</style>

@if($status === 1)
    <span class="alert alert-danger" >{{__('Pending')}}</span>
@elseif($status === 2)
    <span class="alert alert-success" >{{__('Complete')}}</span>
@elseif($status === 3)
    <span class="alert alert-cancel" >{{__('Cancel')}}</span>
@elseif($status === 4)
    <span class="alert alert-danger" >{{__('Processing')}}</span>
@endif
