<a tabindex="0" class="swal_delete_button_restore {{ $class ?? '' }}">{{ $title }}</a>
<form method='post' action='{{$url}}' class="d-none">
    <input type='hidden' name='_token' value='{{csrf_token()}}'>
    <br>
    <button type="submit" class="swal_form_submit_btn_restore d-none"></button>
</form>
