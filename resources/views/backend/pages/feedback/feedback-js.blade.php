<script>
    (function($){
        "use strict";
        $(document).ready(function(){
            // slug generate
            //slug
            function makeSlug(slug){
                let finalSlug = slug.replace(/[^a-zA-Z0-9]/g, ' ');
                finalSlug = slug.replace(/  +/g, ' ');
                finalSlug = slug.replace(/\s/g, '-').toLowerCase().replace(/[^\w-]+/g, '-');
                return finalSlug;
            }

            $(document).on('keyup', '#category', function (e) {
                let slug = makeSlug($(this).val());
                $('#slug').val(slug);

                let url = `{{url('/')}}/` + slug;
                $('.full-slug-show').text(url);
            });

            // add category
            $(document).on('click','.add_category',function(){
                let category = $('#category').val();
                let short_description = $('#short_description').val();
                let slug = $('#slug').val();
                if(category == '' || short_description == '' || slug == ''){
                    toastr_warning_js("{{ __('Please fill all field !') }}");
                    return false;
                }
            });

            // show category in edit modal
            $(document).on('click','.edit_feedback_modal',function(){
                $('input[name="feedback_id"]').val($(this).data('id'));
                $('input[name="title"]').val($(this).data('title'));
                $('textarea[name="description"]').val($(this).data('description'));
                $('input[name="rating"]').val($(this).data('rating'));
            });

            // update feedback
            $(document).on('click', '.update_rating', function(e){
                    e.preventDefault();
                    let feedback_id = $('input[name="feedback_id"]').val();
                    let title = $('#reviewForm input[name="title"]').val();
                    let description = $('#reviewForm textarea[name="description"]').val();
                    let rating = $('#reviewForm input[name="rating"]').val();
                    let erContainer = $(".error-message");
                    erContainer.html('');
                    $.ajax({
                        url:"{{ route('admin.feedback.edit')}}",
                        data:{feedback_id:feedback_id,title:title,description:description,rating:rating},
                        method:'POST',
                        error:function(res){
                            let errors = res.responseJSON;
                            erContainer.html('<div class="alert alert-danger"></div>');
                            $.each(errors.errors, function(index,value){
                                erContainer.find('.alert.alert-danger').append('<p>'+value+'</p>');
                            });
                        },
                        success: function(res){
                            if(res.status=='success'){
                                $("#editFeedbackModal").modal('hide');
                                $('#reviewForm')[0].reset();
                                location.reload();
                                toastr_success_js("{{ __('Feedback Successfully Updated.') }}")
                            }
                            if(res.status == 'failed'){
                                erContainer.html('<div class="alert alert-danger">'+res.msg+'</div>');
                            }
                        }

                    });
                });

            // pagination
            $(document).on('click', '.pagination a', function(e){
                e.preventDefault();
                let page = $(this).attr('href').split('page=')[1];
                let string_search = $('#string_search').val();
                feedbacks(page,string_search);
            });
            function feedbacks(page,string_search){
                $.ajax({
                     url:"{{ route('admin.feedback.paginate.data').'?page='}}" + page,
                    data:{string_search:string_search},
                    success:function(res){
                        $('.search_result').html(res);
                    }
                });
            }

            // search category
            $(document).on('keyup','#string_search',function(){
                let string_search = $(this).val();
                $.ajax({
                    url:"{{ route('admin.feedback.search') }}",
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
