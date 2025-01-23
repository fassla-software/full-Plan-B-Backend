<script>
    (function ($) {
        "use strict";
        $(document).ready(function () {

            //star rating filter
            $(document).on('click', '.filter_blog', function(e) {
                e.preventDefault()
                $(this).siblings().removeClass('active');
                $(this).addClass('active');
                let category = $(this).data('blog-category');

                $.ajax({
                    url:"{{ route('blog.filter')}}",
                    method:'GET',
                    data:{category:category},
                    success:function(res){
                        if(res.status=='nothing'){
                            $('.search_result').html(
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
                            $('.search_result').html(res);
                        }
                    }
                });

            });

            // pagination
            $(document).on('click', '.pagination a', function(e){
                e.preventDefault();
                let page = $(this).attr('href').split('page=')[1];
                let category = $('.filter_blog.active').data('blog-category');
                blogs(page,category);
                console.log(page,category)
            });
            function blogs(page,category){
                $.ajax({
                    url:"{{ route('blog.pagination').'?page='}}" + page,
                    method:'GET',
                    data:{category:category},
                    success:function(res){
                        if(res.status=='nothing'){
                            $('.search_result').html('<h3 class="text-center text-danger">'+"{{ __('Nothing Found') }}"+'</h3>');
                        }else{
                            $('.search_result').html(res);
                        }
                    }

                });
            }

        });
    }(jQuery));
</script>
