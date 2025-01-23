<script>
    (function ($) {
        "use strict";
        $(document).ready(function () {
            $('.country_select2').select2();

            //star rating filter
            $(document).on('click', '.active-list .list', function() {

                let ratings = $(".active-list .list");
                ratings.each(function (){
                    $(this).removeClass('active');
                });
                $(this).addClass('active');
                projects();
            });

            $(document).on('click','#job_search_by_text',function(){
                if ($('#job_search_string').val() == '') {
                    return false;
                }else{
                    projects();
                }
            })

            //project filter
            $(document).on('change', '#country , #level , #delivery_day', function() {
                projects();
            });

            $(document).on('click', '#set_price_range', function() {
                projects();
            });

            // pagination
            $(document).on('click', '.pagination a', function(e){
                e.preventDefault();
                let page = $(this).attr('href').split('page=')[1];
                projects(page);
            });

            function projects(page=1){
                let category = $('#category_id').val();
                let country = $('#country').val();
                let level = $('#level').val();
                let min_price = $('#min_price').val();
                let max_price = $('#max_price').val();
                let delivery_day = $('#delivery_day').val();
                let job_search_string = $('#job_search_string').val();

                let get_pro_projects;
                let rating = $('.filter-lists .list.active').attr('data-rating');

                if($('#get_pro_projects').prop('checked')){
                    $('#get_pro_projects').val('1')
                    get_pro_projects = $('#get_pro_projects').val()
                }else{
                    $('#get_pro_projects').val('0')
                    get_pro_projects = $('#get_pro_projects').val()
                }

                $.ajax({
                    url:"{{ route('category.project.pagination').'?page='}}" + page,
                    method:'GET',
                    data:{category:category,country:country,level:level,min_price:min_price,max_price:max_price,delivery_day:delivery_day,rating:rating,get_pro_projects:get_pro_projects,job_search_string:job_search_string},
                    success:function(res){
                        if(res.status=='nothing'){
                            $('.search_category_result').html(
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
                                </div>`
                            );
                        }else{
                            $('.search_category_result').html(res);
                            $('html, body').animate({ scrollTop: 0 }, 'smooth');
                        }
                    }

                });
            }

            //get pro projects
            $(document).on('change', '#get_pro_projects', function(e){
                e.preventDefault();
                projects();
            });

            // filter reset
            $(document).on('click', '#category_project_filter_reset', function(e){
                $('#country').val('').trigger('change');
                $('#level').val('');
                $('#min_price').val('');
                $('#max_price').val('');
                $('#delivery_day').val('');
                $('#job_search_string').val('');
                $('.active-list .list').removeClass('active');
                projects();
            });

        });
    }(jQuery));
</script>
