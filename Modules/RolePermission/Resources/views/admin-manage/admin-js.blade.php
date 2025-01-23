<script>
    (function($){
        "use strict";
        $(document).ready(function(){
            // password in edit modal
            $(document).on('click','.change_admin_password',function(){
                let admin_id = $(this).data('admin-id')
                $('input[name=admin_id_for_change_password]').val(admin_id)
            });

            // password validate
            $(document).on('click','.update_admin_password',function(){
                let password = $('input[name=password]').val()

                if (password == '') {
                    toastr_warning_js("{{ __('Password field is required.') }}")
                    return false
                }
            });

        });
    }(jQuery));

    // toastr warning
    function toastr_warning_js(msg){
        Command: toastr["warning"](msg, "Warning !")
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "newestOnTop": false,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "preventDuplicates": false,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        }
    }
</script>
