<script>
    (function($){
        "use strict";
        $(document).ready(function(){
            //user identity details
            $(document).on('click','.user_identity_details',function(e){
                e.preventDefault();
                let user_id = $(this).data('user_id');
                $.ajax({
                    url:"{{ route('admin.user.identity.details') }}",
                    method:'post',
                    data:{user_id:user_id},
                    success:function(res){
                        $('#user_identity_details').html(res);
                    }
                });
            })

            //user identity verify status
            $(document).on('click','.user_verify_status',function(e){
                e.preventDefault();
                let user_id = $('.compare-profile-and-identity #user_id_for_verified_status').val();
                Swal.fire({
                    title: 'Are you sure?',
                    text: "To change user verified status",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, change it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url:"{{ route('admin.user.identity.verify.status') }}",
                            method:'post',
                            data:{user_id:user_id},
                            success:function(res){
                                toastr_success_js("{{ __('Status successfully updated') }}")
                                $('.table_activation').load(location.href + ' .table_activation');
                            }
                        });
                        Swal.fire(
                            'Updated!',
                            'Status successfully updated.',
                            'success'
                        )
                    }
                    $('#userIdentityModal').modal('hide');
                })
            })

            //user identity decline
            $(document).on('click','.user_identity_decline',function(e){
                e.preventDefault();
                let user_id = $('.compare-profile-and-identity #user_id_for_verified_status').val();
                Swal.fire({
                    title: 'Are you sure?',
                    text: "To decline the user identity verify request",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, decline it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url:"{{ route('admin.user.identity.verify.decline') }}",
                            method:'post',
                            data:{user_id:user_id},
                            success:function(res){
                                toastr_warning_js("{{ __('Identity verify request successfully decline') }}")
                                $('.table_activation').load(location.href + ' .table_activation');
                            }
                        });
                        Swal.fire(
                            'Updated!',
                            'Request successfully decline.',
                            'success'
                        )
                    }
                    $('#userIdentityModal').modal('hide');
                })
            })

            // pagination
            $(document).on('click', '.pagination a', function(e){
                e.preventDefault();
                let page = $(this).attr('href').split('page=')[1];
                countries(page);
            });
            function countries(page){
                $.ajax({
                    url:"{{ route('admin.user.identity.request.paginate.data').'?page='}}" + page,
                    success:function(res){
                        $('.search_result').html(res);
                    }
                });
            }

            // search state
            $(document).on('keyup','#string_search',function(){
                let string_search = $(this).val();
                $.ajax({
                    url:"{{ route('admin.user.identity.request.search') }}",
                    method:'GET',
                    data:{string_search:string_search},
                    success:function(res){
                        if(res.status=='nothing'){
                            $('.search_result').html('<h3 class="text-center text-danger">'+"{{ __('Nothing Found') }}"+'</h3>');
                        }else{
                            $('.search_result').html(res);
                        }
                    }
                });
            })

        });
    }(jQuery));

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
