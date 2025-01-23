<script>
    (function ($){
        "use strict";

        $(document).ready(function(){

            $(document).on('click', '.pagination a', function(e){
                e.preventDefault();
                let page = $(this).attr('href').split('page=')[1];
                let project_id = "{{ $project->id }}";

                $.ajax({
                    url:"{{ route('project.review.load.more').'?page='}}" + page,
                    method:'GET',
                    data:{project_id:project_id},
                    success:function(res){
                        $('.project-reviews').html(res);
                        // $('html, body').animate({ scrollTop: 0 }, 500);
                        $('html, body').animate({
                            scrollTop: $('.project-reviews-scroll').offset().top
                        }, 500);
                    }
                });
            });
        });

    }(jQuery));
</script>
