<script>
    (function($) {
        "use strict";
        $(document).ready(function() {
            //show operation details in modal
            $(document).on('click', '.operation_details', function() {
                let operation_id = $(this).data('operation_id');
                let operation_type = $(this).data('operation_type');
                let category_slug = $(this).data('category_slug');
                let cost = $(this).data('cost');

                $('#operation_details .operation_type').text(operation_type);
                $('#operation_details .category_slug').text(category_slug);
                $('#operation_details .cost').text(cost);

                //edit operation info
                $('#edit_operation_details #edit_operation_id').val(operation_id);
                $('#edit_operation_details #edit_cost').val(cost);
            });

            // operation info edit
            $(document).on('click', '.operation_info_edit', function(e) {
                e.preventDefault();
                $('#operatinoDetailsModal').modal('hide');
                $('#operationDetailsEditModal').modal('show');
            });

            //validation while update operations info
            $(document).on('click', '.update_operation_info', function() {
                let cost = $('#edit_operation_details #edit_cost').val();

                if (cost == '') {
                    toastr_warning_js("{{ __('Please fill all fields') }}")
                    return false;
                }
            });

            // pagination
            $(document).on('click', '.pagination a', function(e) {
                e.preventDefault();
                let page = $(this).attr('href').split('page=')[1];
                countries(page);
            });

            function countries(page) {
                $.ajax({
                    url: "{{ route('admin.operation-costs.paginate.data') . '?page=' }}" + page,
                    success: function(res) {
                        $('.search_result').html(res);
                    }
                });
            }

            // search state
            $(document).on('keyup', '#string_search', function() {
                let string_search = $(this).val();
                $.ajax({
                    url: "{{ route('admin.operation-costs.search') }}",
                    method: 'GET',
                    data: {
                        string_search: string_search
                    },
                    success: function(res) {
                        if (res.status == 'nothing') {
                            $('.search_result').html(
                                '<h3 class="text-center text-danger">' +
                                "{{ __('Nothing Found') }}" + '</h3>');
                        } else {
                            $('.search_result').html(res);
                        }
                    }
                });
            })
        });
    }(jQuery));

    //toastr success
    function toastr_success_js(msg) {
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

    //toastr warning
    function toastr_warning_js(msg) {
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
