<style>
    .queue-order {
        border-color: #f2f2f2;
        border-left: 3px solid #e0a800;
        background-color: #f2f2f2;
        color: #333;
        border-radius: 0;
        padding: 5px;
    }
    .active-order, .complete-order {
        border-color: #f2f2f2;
        border-left: 3px solid #3aad3a;
        background-color: #f2f2f2;
        color: #333;
        border-radius: 0;
        padding: 5px;
    }
    .deliver-order {
        border-color: #f2f2f2;
        border-left: 3px solid #33BBC5;
        background-color: #f2f2f2;
        color: #333;
        border-radius: 0;
        padding: 5px;
    }
    .cancel-order, .decline-order {
        border-color: #f2f2f2;
        border-left: 3px solid #dd0000;
        background-color: #f2f2f2;
        color: #333;
        border-radius: 0;
        padding: 5px;
    }
    .cancel-order {
        border-color: #f2f2f2;
        border-left: 3px solid #cb801e;
        background-color: #f2f2f2;
        color: #333;
        border-radius: 0;
        padding: 5px;
    }
</style>

@if($status === 0)
    <span class="queue-order" >{{__('Queue')}}</span>
@elseif($status === 1)
    <span class="active-order" >{{__('Active')}}</span>
@elseif($status === 2)
    <span class="deliver-order" >{{__('Delivered')}}</span>
@elseif($status === 3)
    <span class="complete-order" >{{__('Complete')}}</span>
@elseif($status === 4)
    <span class="cancel-order" >{{__('Cancel')}}</span>
@elseif($status === 5)
    <span class="decline-order" >{{__('Decline')}}</span>
@elseif($status === 7)
    <span class="hold-order" >{{__('Hold')}}</span>
@endif
