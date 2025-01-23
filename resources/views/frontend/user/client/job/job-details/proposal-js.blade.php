<script>
    (function($){
        "use strict";
        $(document).ready(function(){

            // add to short list
            $(document).on('click', '.add_remove_shortlist', function(e){
                let proposal_id = $(this).data('proposal-id');
                $.ajax({
                    url:"{{ route('client.job.proposal.add.to.shortlist') }}",
                    method:"post",
                    data:{proposal_id:proposal_id},
                    success:function(res){
                        if(res.status == 1){
                            $('.add_remove_interview_location_'+ proposal_id).load(location.href + ' .add_remove_interview_location_' + proposal_id)
                            toastr_success_js("{{ __('Proposal short listed') }}");
                        }else{
                            $('.add_remove_interview_location_'+ proposal_id).load(location.href + ' .add_remove_interview_location_' + proposal_id)
                            toastr_success_js("{{ __('Proposal remove from short list') }}")
                        }
                    }
                })
            });

            // add to short list
            $(document).on('click', '.reject_proposal', function(e){
                let proposal_id = $(this).data('proposal-id');
                Swal.fire({
                    title: "{{ __('Are you sure to reject?') }}",
                    text: "{!! __("You won't be able to revert this!") !!}",
                    icon: "{{ __('warning') }}",
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: "{{ __('Yes, reject it!') }}"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url:"{{ route('client.job.proposal.reject') }}",
                            method:"post",
                            data:{proposal_id:proposal_id},
                            success:function(res){
                                if(res.status == 1){
                                    $('.rejected_interview_location_'+ proposal_id).load(location.href + ' .rejected_interview_location_' + proposal_id)
                                    $('.add_remove_interview_location_'+ proposal_id).load(location.href + ' .add_remove_interview_location_' + proposal_id)
                                    toastr_success_js("{{ __('Proposal rejected') }}");
                                }
                            }
                        })
                    }
                })
            });

            // job proposal filter
            $(document).on('click', '.filter_proposal', function(e){
                let filter_val = $(this).data('val');
                let job_id = $('#Job_id_for_filter_jobs').val();

                $.ajax({
                    url:"{{ route('client.job.proposal.filter') }}",
                    method:"post",
                    data:{filter_val:filter_val, job_id:job_id},
                    success:function(res){
                        $('.filter_job_proposal_result').html(res);
                    }
                })
            });

            // job proposal filter
            $(document).on('click', '.take_freelancer_interview', function(e){
                let job_id = $(this).data('job-id');
                let proposal_id = $(this).data('proposal-id');
                let freelancer_id = $(this).data('freelancer-id');
                let title = $(this).data('job-title');
                let level = $(this).data('job-level');
                let type = $(this).data('job-type');
                let created_at = $(this).data('job-create-date');
                $('#freelancer_id').val(freelancer_id)
                $('#proposal_id_for_check_interview').val(proposal_id)
            });

            // pagination
            $(document).on('click', '.pagination a', function(e){
                e.preventDefault();
                let page = $(this).attr('href').split('page=')[1];
                histories(page);
            });
            function histories(page){
                $.ajax({
                    url:"{{ route('client.job.paginate.data').'?page='}}" + page,
                    success:function(res){
                        $('.search_result').html(res);
                    }
                });
            }

            // job accept
            $(document).on('click', '.accept_proposal', function(e){
                let job_id = $(this).data('job-id-for-order');
                let proposal_id = $(this).data('proposal-id-for-order');
                $('#job_id_for_order').val(job_id)
                $('#proposal_id_for_order').val(proposal_id)
                $('#job_type_for_order').val('job')
            });

            //accept hourly job
            $(document).on('click', '.accept_hourly_proposal', function(e){
                $('#job_type_for_order').val('job')
                    Swal.fire({
                        title: '{{__("Are You Sure?")}}',
                        text: '{{__("To accept this proposal")}}',
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

            // add to short list
            $(document).on('click', '.job_open_close', function(e) {
                let job_id = $(this).data('job-id');
                let job_on_off = $(this).data('job-on-off');
                let title, text, confirmation;

                if(job_on_off == 0){
                    title = "{{ __('Are you sure to open this job?') }}";
                    text = "{{ __('If you open this job it will publicly visible and freelancer will send job proposal') }}";
                    confirmation = "{{ __('Yes, open it!') }}";
                }else{
                    title = "{{ __('Are you sure to close this job?') }}";
                    text = "{{ __('If you close this job it will not publicly visible and freelancer will not send job proposal') }}";
                    confirmation = "{{ __('Yes, close it!') }}";
                }

                Swal.fire({
                    title: title,
                    text: text,
                    icon: "{{ __('warning') }}",
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: confirmation
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url:"{{ route('client.job.open.close') }}",
                            method:"post",
                            data:{job_id:job_id},
                            success:function(res){
                                if(res.status == 1){
                                    $('.job_open_close_location').load(location.href + ' .job_open_close_location')
                                    $('.job_open_close_btn_location').load(location.href + ' .job_open_close_btn_location')
                                    toastr_success_js("{{ __('Job successfully open') }}");
                                }else{
                                    $('.job_open_close_location').load(location.href + ' .job_open_close_location')
                                    $('.job_open_close_btn_location').load(location.href + ' .job_open_close_btn_location')
                                    toastr_success_js("{{ __('Job successfully closed') }}")
                                }
                            }
                        })
                    }
                })
            });

        });

    }(jQuery));
</script>
