<script>
    (function($){
        "use strict";
        $(document).ready(function(){

            $(document).on('click','.get_skill_val',function(){
                let skill_val = $(this).data('skill_val');
                 $('#skill_rating').val(skill_val);
                rating_average($('#skill_rating').val(), $('#availability_rating').val(), $('#communication_rating').val(), $('#work_quality_rating').val(), $('#deadline_rating').val(), $('#co_operation_rating').val());
            });

            $(document).on('click','.get_availability_val',function(){
                let availability_val = $(this).data('availability_val');
                $('#availability_rating').val(availability_val);
                rating_average($('#skill_rating').val(), $('#availability_rating').val(), $('#communication_rating').val(), $('#work_quality_rating').val(), $('#deadline_rating').val(), $('#co_operation_rating').val());
            });

            $(document).on('click','.get_communication_val',function(){
                let communication_val = $(this).data('communication_val');
                $('#communication_rating').val(communication_val);
                rating_average($('#skill_rating').val(), $('#availability_rating').val(), $('#communication_rating').val(), $('#work_quality_rating').val(), $('#deadline_rating').val(), $('#co_operation_rating').val());
            });

            $(document).on('click','.get_work_quality_val',function(){
                let work_quality_val = $(this).data('work_quality_val');
                $('#work_quality_rating').val(work_quality_val);
                rating_average($('#skill_rating').val(), $('#availability_rating').val(), $('#communication_rating').val(), $('#work_quality_rating').val(), $('#deadline_rating').val(), $('#co_operation_rating').val());
            });

            $(document).on('click','.get_deadline_val',function(){
                let deadline = $(this).data('deadline_val');
                $('#deadline_rating').val(deadline);
                rating_average($('#skill_rating').val(), $('#availability_rating').val(), $('#communication_rating').val(), $('#work_quality_rating').val(), $('#deadline_rating').val(), $('#co_operation_rating').val());
            });

            $(document).on('click','.get_co_operation_val',function(){
                let co_operation = $(this).data('co_operation_val');
                $('#co_operation_rating').val(co_operation);
                rating_average($('#skill_rating').val(), $('#availability_rating').val(), $('#communication_rating').val(), $('#work_quality_rating').val(), $('#deadline_rating').val(), $('#co_operation_rating').val());
            });

            $(document).on('click','.submit_rating',function(e){
                let skill = $('#skill_rating').val();
                let availability = $('#availability_rating').val();
                let communication = $('#communication_rating').val();
                let quality = $('#work_quality_rating').val();
                let deadline = $('#deadline_rating').val();
                let co_operation = $('#co_operation_rating').val();
                if(skill < 1 && availability < 1 && communication < 1 && quality < 1  && deadline < 1 && co_operation < 1){
                    toastr_warning_js("{{ __('Please select at least one rating item') }}")
                    return false;
                }
            });

        });
    }(jQuery));

    function rating_average(skill=0, availability=0, communication=0, work_quality=0, deadline=0, co_operation=0){
        let count = 0;
        count = Number(skill) > 0 ? count + 1 : count;
        count = Number(availability) > 0 ? count + 1 : count;
        count = Number(communication) > 0 ? count + 1 : count;
        count = Number(work_quality) > 0 ? count + 1 : count;
        count = Number(deadline) > 0 ? count + 1 : count;
        count = Number(co_operation) > 0 ? count + 1 : count;

        let rating_average = (Number(skill) + Number(availability) + Number(communication) + Number(work_quality) + Number(deadline) + Number(co_operation))/Number(count);
        let show_rating_average = rating_average.toFixed(1);
        $('.show_average_score').text(Number(show_rating_average) || 0.0);
    }

    // todo toastr warning
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
