<script>
    (function($){
        "use strict";
        $(document).ready(function(){

            // add country
            $(document).on('click','.add_department',function(e){
                let name = $('#name').val();
                if(name == ''){
                    toastr_warning_js("{{ __('Please enter a department name !') }}");
                    return false;
                }
            });

            // show country in edit modal
            $(document).on('click','.edit_department_modal',function(){
                let department = $(this).data('department');
                let department_id = $(this).data('department_id');
                $('#edit_name').val(department);
                $('#department_id').val(department_id);
            });

            // update country
            $(document).on('click','.update_department',function(){
                let department = $('#edit_department').val();
                if(department == ''){
                    toastr_warning_js("{{ __('Please enter a department name !') }}");
                    return false;
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
