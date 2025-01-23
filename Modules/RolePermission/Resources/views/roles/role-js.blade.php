<script>
    (function($){
        "use strict";
        $(document).ready(function(){
            // show category in edit modal
            $(document).on('click','.edit_role_modal',function(){
                let id = $(this).data('role-id');
                let name = $(this).data('role-name');
                $('#role_id').val(id);
                $('#role_name').val(name);
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
