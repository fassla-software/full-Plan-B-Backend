<style>
    .alert-warning {
        border-color: #f2f2f2;
        border-left: 3px solid #e0a800;
        background-color: #f2f2f2;
        color: #333;
        border-radius: 0;
        padding: 5px;
    }
    .alert-success {
        border-color: #f2f2f2;
        border-left: 3px solid #319a31;
        background-color: #f2f2f2;
        color: #333;
        border-radius: 0;
        padding: 5px;
    }
    .alert-danger {
        border-color: #f2f2f2;
        border-left: 3px solid #dd0000;
        background-color: #f2f2f2;
        color: #333;
        border-radius: 0;
        padding: 5px;
    }
</style>

@if($status === 0)
    <span class="alert alert-warning">{{__('Pending')}}</span>
@elseif($status === 1)
    <span class="alert alert-success" >{{__('Approved')}}</span>
@elseif($status === 2)
    <span class="alert alert-danger" >{{__('Rejected')}}</span>
@endif
