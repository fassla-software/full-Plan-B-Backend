<script>
    (function($){
        "use strict";
        $(document).ready(function(){
            //hold order status
            $(document).on('click','.suspend_user_account',function(e){
                e.preventDefault();
                Swal.fire({
                    title: '{{__("Suspend Account ?")}}',
                    text: '{{__("Are you sure to suspend?")}}',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: "{{__('Yes, Suspend it!')}}"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $(this).next().find('.swal_form_submit_btn').trigger('click');
                    }
                });
            });
        });
    }(jQuery));

</script>
