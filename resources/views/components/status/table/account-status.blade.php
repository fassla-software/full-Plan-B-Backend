<style>
    .alert-warning {
        border-color: #f2f2f2;
        border-left: 3px solid #e0a800;
        background-color: #f2f2f2;
        color: #333;
        border-radius: 0;
        padding: 5px;
    }

</style>

@if($status === 1)
    <span class="alert alert-warning" >{{__('Suspended')}}</span>
@endif
