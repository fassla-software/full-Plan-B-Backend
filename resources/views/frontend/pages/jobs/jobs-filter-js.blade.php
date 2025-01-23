<script>
    (function ($) {
        "use strict";
        $(document).ready(function () {
            $('.country_select2').select2();
            $('.category_select2').select2();
            $('.subcategory_select2').select2();

            // change category and get subcategory
            $('#subcategory_info').hide();
            $(document).on('change','#category', function() {
                let category = $(this).val();
                let jsonOldCategory = @json(old('subcategory', []));

                $('#subcategory_info').show();
                $.ajax({
                    method: 'post',
                    url: "{{ route('au.subcategory.all') }}",
                    data: {
                        category: category,
                        old_sub_categories: '{{ json_encode(old('subcategory') ?? []) }}'
                    },
                    success: function(res) {
                        if (res.status == 'success') {
                            let all_options = "<option value=''>{{__('Select Sub Category')}}</option>";
                            let all_subcategories = res.subcategories;

                            $.each(all_subcategories, function(index, value) {
                                all_options += `<option ${jsonOldCategory?.includes(value?.id?.toString() ?? 0) ? 'selected=\'selected\'' : ''} value='${value.id}'>${value.sub_category ?? ''}</option>`;
                            });

                            $("#subcategory").next('.select2-container').find('.select2-selection__rendered ').html('');
                            $(".get_subcategory").html(all_options);

                            $("#subcategory_info").html('');
                            if(all_subcategories.length <= 0){
                                $("#subcategory_info").html('<span class="text-danger"> {{ __('No sub categories found for selected category!') }} <span>');
                            }
                        }
                    }
                })
            })

            //star rating filter
            $(document).on('click', '.active-list .list', function() {
                $(this).siblings().removeClass('active');
                $(this).addClass('active');
            });

            $(document).on('click','#job_search_by_text',function(){
                if ($('#job_search_string').val() == '') {
                    return false;
                }else{
                    let category = $('#category').val();
                    let subcategory = $('#subcategory').val();
                    let country = $('#country').val();
                    let type = $('#type').val();
                    let level = $('#level').val();
                    let min_price = $('#min_price').val();
                    let max_price = $('#max_price').val();
                    let duration = $('#duration').val();
                    let job_search_string = $('#job_search_string').val();
                    $.ajax({
                        url:"{{ route('jobs.filter')}}",
                        method:'GET',
                        data:{category:category,subcategory:subcategory,country:country,type:type,level:level,min_price:min_price,max_price:max_price,duration:duration,job_search_string:job_search_string},
                        success:function(res){
                            if(res.status=='nothing'){
                                $('.search_job_result').html(
                                    `<div class="congratulation-area section-bg-2 pat-100 pab-100">
                                    <div class="container">
                                        <div class="congratulation-wrapper">
                                            <div class="congratulation-contents center-text">
                                                <div class="congratulation-contents-icon bg-danger wow  zoomIn animated" data-wow-delay=".5s" style="visibility: visible; animation-delay: 0.5s; animation-name: zoomIn;">
                                                    <i class="fas fa-times"></i>
                                                </div>
                                                <h4 class="congratulation-contents-title"> {{ __('OPPS!') }} </h4>
                                                <p class="congratulation-contents-para">{{ __('Nothing') }} <strong>{{ __('Found') }}</strong> </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>`);
                            }else{
                                $('.search_job_result').html(res);
                            }
                        }
                    });
                }
            })

            //job filter
            $(document).on('change', '#category, #subcategory, #country , #type , #level , #duration', function() {
                let category = $('#category').val();
                let subcategory = $('#subcategory').val();
                let country = $('#country').val();
                let type = $('#type').val();
                let level = $('#level').val();
                let min_price = $('#min_price').val();
                let max_price = $('#max_price').val();
                let duration = $('#duration').val();
                let job_search_string = $('#job_search_string').val();
                console.log(category);
                $.ajax({
                    url:"{{ route('jobs.filter')}}",
                    method:'GET',
                    data:{category:category,subcategory:subcategory,country:country,type:type,level:level,min_price:min_price,max_price:max_price,duration:duration,job_search_string:job_search_string},
                    success:function(res){
                        if(res.status=='nothing'){
                            $('.search_job_result').html(
                                `<div class="congratulation-area section-bg-2 pat-100 pab-100">
                                    <div class="container">
                                        <div class="congratulation-wrapper">
                                            <div class="congratulation-contents center-text">
                                                <div class="congratulation-contents-icon bg-danger wow  zoomIn animated" data-wow-delay=".5s" style="visibility: visible; animation-delay: 0.5s; animation-name: zoomIn;">
                                                    <i class="fas fa-times"></i>
                                                </div>
                                                <h4 class="congratulation-contents-title"> {{ __('OPPS!') }} </h4>
                                                <p class="congratulation-contents-para">{{ __('Nothing') }} <strong>{{ __('Found') }}</strong> </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>`);
                        }else{
                            $('.search_job_result').html(res);
                        }
                    }
                });
            });

            $(document).on('click', '#set_price_range', function() {
                let category = $('#category').val();
                let subcategory = $('#subcategory').val();
                let country = $('#country').val();
                let type = $('#type').val();
                let level = $('#level').val();
                let min_price = $('#min_price').val();
                let max_price = $('#max_price').val();
                let duration = $('#duration').val();
                let job_search_string = $('#job_search_string').val();

                $.ajax({
                    url:"{{ route('jobs.filter')}}",
                    method:'GET',
                    data:{category:category,subcategory:subcategory,country:country,type:type,level:level,min_price:min_price,max_price:max_price,duration:duration,job_search_string:job_search_string},
                    success:function(res){
                        if(res.status=='nothing'){
                            $('.search_job_result').html(
                                `<div class="congratulation-area section-bg-2 pat-100 pab-100">
                                    <div class="container">
                                        <div class="congratulation-wrapper">
                                            <div class="congratulation-contents center-text">
                                                <div class="congratulation-contents-icon bg-danger wow  zoomIn animated" data-wow-delay=".5s" style="visibility: visible; animation-delay: 0.5s; animation-name: zoomIn;">
                                                    <i class="fas fa-times"></i>
                                                </div>
                                                <h4 class="congratulation-contents-title"> {{ __('OPPS!') }} </h4>
                                                <p class="congratulation-contents-para">{{ __('Nothing') }} <strong>{{ __('Found') }}</strong> </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>`);
                        }else{
                            $('.search_job_result').html(res);
                        }
                    }
                });
            });

            // pagination
            $(document).on('click', '.pagination a', function(e){
                e.preventDefault();
                let page = $(this).attr('href').split('page=')[1];
                let category = $('#category').val();
                let subcategory = $('#subcategory').val();
                let country = $('#country').val();
                let type = $('#type').val();
                let level = $('#level').val();
                let min_price = $('#min_price').val();
                let max_price = $('#max_price').val();
                let duration = $('#duration').val();
                let job_search_string = $('#job_search_string').val();

                jobs(page,category,subcategory,country,type,level,min_price,max_price,duration,job_search_string);
            });
            function jobs(page,category,subcategory,country,type,level,min_price,max_price,duration,job_search_string){
                $.ajax({
                    url:"{{ route('jobs.pagination').'?page='}}" + page,
                    method:'GET',
                    data:{country:country,category:category,subcategory:subcategory,type:type,level:level,min_price:min_price,max_price:max_price,duration:duration,job_search_string:job_search_string},
                    success:function(res){
                        if(res.status=='nothing'){
                            $('.search_job_result').html('<h3 class="text-center text-danger">'+"{{ __('Nothing Found') }}"+'</h3>');
                        }else{
                            $('.search_job_result').html(res);
                            $('html, body').animate({ scrollTop: 0 }, 'smooth');
                        }
                    }

                });
            }

            // filter reset
            $(document).on('click', '#job_filter_reset', function(e){
                e.preventDefault();
                $('#country').val('').trigger('change');
                $('#category').val('').trigger('change');
                $('#subcategory').val('').trigger('change');
                $('#type').val('');
                $('#level').val('');
                $('#min_price').val('');
                $('#max_price').val('');
                $('#duration').val('');
                $('#job_search_string').val('');

                $.ajax({
                    url:"{{ route('jobs.filter.reset')}}",
                    method:'GET',
                    success:function(res){
                        if(res.status=='nothing'){
                            $('.search_job_result').html('<h3 class="text-center text-danger">'+"{{ __('Nothing Found') }}"+'</h3>');
                        }else{
                            $('.search_job_result').html(res);
                        }
                    }

                });
            });
        });
    }(jQuery));
</script>
