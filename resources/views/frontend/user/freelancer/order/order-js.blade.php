<script>
    (function($){
        "use strict";
        $(document).ready(function(){

            $('.order_sort[data-val=all]').trigger("click");


            $(document).on('click','.accept_and_change_order_status',function(e){
                e.preventDefault();
                Swal.fire({
                    title: '{{__("Accept Order ?")}}',
                    text: '{{__("You would not change it again.")}}',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: "{{__('Yes, Accept it!')}}"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $(this).next().find('.swal_form_submit_btn').trigger('click');
                    }
                });
            });

            $(document).on('click','.decline_and_change_order_status',function(e){
                e.preventDefault();
                Swal.fire({
                    title: '{{__("Decline Order ?")}}',
                    text: '{{__("You would not change it again.")}}',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: "{{__('Yes, Decline it!')}}"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $(this).next().find('.swal_form_submit_btn').trigger('click');
                    }
                });
            });

            $(document).on('click','.cancel_and_change_order_status',function(e){
                e.preventDefault();
                Swal.fire({
                    title: '{{__("Cancel Order ?")}}',
                    text: '{{__("You would not change it again.")}}',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: "{{__('Yes, Cancel it!')}}"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $(this).next().find('.swal_form_submit_btn').trigger('click');
                    }
                });
            });

            $(document).on('click','.order_submit',function(e){
                let order_id = $(this).data('order_id');
                let order_milestone_id = $(this).data('order_milestone_id'); //if a order milestone available
                let client_id = $(this).data('client_id');
                $('#order_id_for_submit_order').val(order_id);
                $('#order_milestone_id').val(order_milestone_id);
                $('#client_id_for_notification').val(client_id);
            });

            $(document).on('click','.submit_order_details',function(e){
                let attachment = $('#attachment').val();
                let description = $('#description').val();

                if(attachment == '' || description == ''){
                    toastr_warning_js("{{ __('Please fill both fields') }}");
                    return false;
                }
            });

            $(document).on('click','.order_submit_description',function(){
                let milestone_id = $(this).data('order_milestone_id');
                let description = $(this).data('description');
                if(milestone_id != ''){
                    $('.show_milestone_id').text('#' + milestone_id);
                }
                $('.show_order_submit_description').text(description);
            });

            //show revision details
            $(document).on('click','.show_revision_details',function(){
                let revision_id = $(this).data('revision_id');
                let revision_description = $(this).data('revision_description');
                $('#display_request_revision_description').html(revision_description)
            });

            //order report
            $(document).on('click','.open_order_report_modal',function(){
                let order_id = $(this).data('order-id');
                let client_id = $(this).data('client-id');

                $('#report_order_id').val(order_id);
                $('#report_to_client_id').val(client_id);
            });
            $(document).on('click','.freelancer_order_report',function(){
                let report_title = $('#report_title').val();
                let report_description = $('#report_description').val();

                if(report_title == '' || report_description == ''){
                    toastr_warning_js("{{ __('Please fill both fields.') }}")
                    return false;
                }
            });

            //order sort
            $(document).on('click','.order_sort',function(e){
                e.preventDefault();
                let order_type = $(this).data('val');
                $(this).addClass('btn-profile btn-bg-1');
                $(this).siblings().removeClass('btn-profile btn-bg-1');
                $('#set_order_type_value').val(order_type);

                $.ajax({
                    url:"{{ route('freelancer.order.sort.by')}}",
                    data:{order_type:order_type},
                    success:function(res){
                        $('.search_result').html(res);
                    }
                });

            });

            //paginate
            $(document).on('click', '.pagination a', function(e){
                e.preventDefault();
                let page = $(this).attr('href').split('page=')[1];
                let order_type = $('#set_order_type_value').val();
                $.ajax({
                    url:"{{ route('freelancer.order.paginate.data').'?page='}}" + page,
                    data:{order_type:order_type},
                    success:function(res){
                        $('.search_result').html(res);
                    }
                });
            });

        });
    }(jQuery));

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
    //toastr success
    function toastr_success_js(msg){
        Command: toastr["success"](msg, "Success !")
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
