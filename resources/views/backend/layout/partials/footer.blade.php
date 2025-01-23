
<!-- Mouse Cursor start -->
<div class="mouse-move mouse-outer"></div>
<div class="mouse-move mouse-inner"></div>
<!-- Mouse Cursor Ends -->

<!-- jquery -->
<script src="{{ asset('assets/common/js/jquery-3.7.1.min.js') }}"></script>
<!-- jquery Migrate -->
<script src="{{ asset('assets/common/js/jquery-migrate-3.4.0.min.js') }}"></script>
<!-- bootstrap -->
<script src="{{ asset('assets/backend/js/bootstrap.bundle.min.js') }}"></script>
<!-- All Plugin Js -->
<script src="{{ asset('assets/backend/js/all_plugin.js') }}"></script>
<!-- Chart Js -->
<script src="{{ asset('assets/backend/js/chart.js') }}"></script>
<!-- Map Js -->
<script src="{{ asset('assets/backend/js/maps/jquery.mousewheel.js') }}"></script>
<script src="{{ asset('assets/backend/js/maps/raphael.min.js') }}"></script>
<script src="{{ asset('assets/backend/js/maps/jquery.mapael.js') }}"></script>
<script src="{{ asset('assets/backend/js/maps/world_countries.js') }}"></script>
<!-- Dashboard main js -->
<script src="{{ asset('assets/backend/js/dashboard_main.js') }}"></script>

<!-- Toastr js -->
<script src="{{ asset('assets/common/js/toastr.min.js') }}"></script>
{!! Toastr::message() !!}

<!-- global ajax setup -->
<script> $.ajaxSetup({headers: {'X-CSRF-TOKEN': '{{csrf_token()}}'} }) </script>

@yield('script')

<script>
    (function($){
        "use strict";
        $(document).on('mouseup', function (e) {
            if ($(e.target).closest('.dashboard__notification').find('.dashboard__notification__wrapper').length === 0) {
                $('.dashboard__notification__wrapper').removeClass('active');
            }
        });
        $(document).on('click', '.dashboard__notification__icon', function () {
            $('.dashboard__notification__wrapper').toggleClass('active');
            $.ajax({
                url:"{{ route('admin.notification.read') }}",
                method:'POST',
                success: function(res){
                    if(res.status == 'success'){
                        $('.dashboard__notification__icon span').text(0);
                    }
                }
            });
        });
        $(document).on('click','.swal_delete_button',function(e){
            e.preventDefault();
            Swal.fire({
                title: '{{__("Are you sure to delete?")}}',
                text: '{{__("You would not be able to revert this item!")}}',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: "{{__('Yes, Delete it!')}}"
            }).then((result) => {
                if (result.isConfirmed) {
                    $(this).next().find('.swal_form_submit_btn').trigger('click');
                }
            });
        });
        $(document).on('click','.swal_delete_button_restore',function(e){
            e.preventDefault();
            Swal.fire({
                title: '{{__("Are you sure to restore?")}}',
                text: '{{__("You could delete this item anytime!")}}',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: "{{__('Yes, Restore it!')}}"
            }).then((result) => {
                if (result.isConfirmed) {
                    $(this).next().find('.swal_form_submit_btn_restore').trigger('click');
                }
            });
        });
        $(document).on('click','.swal_status_change_button',function(e){
            e.preventDefault();
            Swal.fire({
                title: '{{__("Are you sure?")}}',
                text: '{{__("You would change status any time")}}',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: "{{__('Yes, Change it!')}}"
            }).then((result) => {
                if (result.isConfirmed) {
                    $(this).next().find('.swal_form_submit_btn').trigger('click');
                }
            });
        });
        $(document).on('click','.wallet_history_status_change',function(e){
            e.preventDefault();
            Swal.fire({
                title: '{{__("Are you sure?")}}',
                text: '{{__("You would not change it again.")}}',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: "{{__('Yes, Change it!')}}"
            }).then((result) => {
                if (result.isConfirmed) {
                    $(this).next().find('.swal_form_submit_btn').trigger('click');
                }
            });
        });
        $(document).on('click','.reject_project',function(e){
            e.preventDefault();
            Swal.fire({
                title: '{{__("Are you sure?")}}',
                text: '{{__("To reject this project. you would activate any time")}}',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: "{{__('Yes, Reject it!')}}"
            }).then((result) => {
                if (result.isConfirmed) {
                    $(this).next().find('.swal_form_submit_btn').trigger('click');
                }
            });
        });
        $(document).on('click','.reject_job',function(e){
            e.preventDefault();
            Swal.fire({
                title: '{{__("Are you sure?")}}',
                text: '{{__("To reject this job. you would activate any time")}}',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: "{{__('Yes, Reject it!')}}"
            }).then((result) => {
                if (result.isConfirmed) {
                    $(this).next().find('.swal_form_submit_btn').trigger('click');
                }
            });
        });
        $(document).on('click','.swal_disable_2fa_button',function(e){
            e.preventDefault();
            Swal.fire({
                title: '{{__("Are you sure?")}}',
                text: '{{__("Disable 2FA for this user")}}',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: "{{__('Yes, disable it!')}}"
            }).then((result) => {
                if (result.isConfirmed) {
                    $(this).next().find('.swal_form_submit_btn').trigger('click');
                }
            });
        });
        $(document).on('click','.swal_email_verify_button',function(e){
            e.preventDefault();
            Swal.fire({
                title: '{{__("Are you sure?")}}',
                text: '{{__("To verify this user email.")}}',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: "{{__('Yes, verify it!')}}"
            }).then((result) => {
                if (result.isConfirmed) {
                    $(this).next().find('.swal_form_submit_btn').trigger('click');
                }
            });
        });
        $(document).on("click", ".media_upload_form_btn", function (){
            let prevModal = $(this).closest(".modal");
            if(prevModal.length > 0){
                $(document).on("click", ".media_upload_modal_submit_btn , .modal .close-select-button", function (){
                    $(".media_upload_modal_submit_btn").closest('.modal').hide();
                    prevModal.modal("show");
                })
            }
        });
    }(jQuery));
</script>

<script>
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

    //toastr warning
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

</body>
</html>
