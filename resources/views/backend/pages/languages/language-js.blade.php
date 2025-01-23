<script>
    (function($){
        "use strict";
        $(document).ready(function(){

            $(document).on('change', 'select[name="language_select"]', function () {
                let el = $(this);
                let name = el.parent().find('select[name="language_select"] option[value="'+el.val()+'"]' ).text()
                el.parent().find('input[name="name"]').val(name)
                el.parent().parent().find('input[name="slug"]').val(el.val())
            });

            // add category
            $(document).on('click','.add_language',function(){
                let name = $('#name').val();
                let direction = $('#direction').val();
                let status = $('#status').val();
                if(name == '' || direction == '' || status==''){
                    toastr_warning_js("{{ __('Please fill all fields !') }}");
                    return false;
                }
            });

            $(document).on('click', '.edit_language_modal', function () {
                let el = $(this);
                let id = el.data('id');
                let name = el.data('name');
                let slug = el.data('slug');
                let form = $('#editLanguageModal');
                form.find('#lang_id').val(id);
                form.find('input[name="name"]').val(name);
                form.find('select[name="language_select"] option[value="'+slug+'"]').attr('selected',true);
                form.find('#edit_slug').val(slug);
                form.find('#edit_direction option[value="' + el.data('direction') + '"]').prop('selected', true);
                form.find('#edit_status option[value="' + el.data('status') + '"]').prop('selected', true);
            });

            // update language
            $(document).on('click','.update_language',function(){
                let name = $('#edit_name').val();
                let direction = $('#direction').val();
                let status = $('#status').val();
                if(name == '' || direction == '' || status==''){
                    toastr_warning_js("{{ __('Please fill all fields') }}");
                    return false;
                }
            });
        });
    }(jQuery));

    //toastr warning
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
