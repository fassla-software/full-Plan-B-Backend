<!-- State Edit Modal -->
<div class="modal fade" id="userDetailsModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-header-edit d-flex gap-2">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">{{ __('User Details') }} </h1>
                    <a class="user_info_edit btn btn-sm btn-primary" href=""><i class="fas fa-pencil"></i></a>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            @csrf
            <div class="modal-body" id="user_details">
                <div class="userDetails__wrapper">
                    <p class="userDetails__wrapper__item"><strong>{{ __('User Type: ') }}</strong> <span
                            class="user_type"></span></p>

                    <p class="userDetails__wrapper__item"><strong>{{ __('Total Commas: ') }}</strong> <span
                            class="commas"></span></p>

                    <p class="userDetails__wrapper__item"><strong>{{ __('Shifted Commas: ') }}</strong> <span
                            class="remaining_commas"></span></p>

                    <p class="userDetails__wrapper__item"><strong>{{ __('Full Name: ') }}</strong><span
                            class="full_name"></span></p>

                    <p class="userDetails__wrapper__item"><strong>{{ __('Username: ') }}</strong><span
                            class="username"></span></p>

                    <p class="userDetails__wrapper__item"><strong>{{ __('Email: ') }}</strong><span
                            class="email"></span></p>

                    <p class="userDetails__wrapper__item"><strong>{{ __('Phone: ') }}</strong><span
                            class="phone"></span></p>

                    <p class="userDetails__wrapper__item"><strong>{{ __('Country: ') }}</strong><span
                            class="country"></span></p>

                    <p class="userDetails__wrapper__item"><strong>{{ __('State: ') }}</strong><span
                            class="state"></span></p>

                    <p class="userDetails__wrapper__item"><strong>{{ __('City: ') }}</strong><span
                            class="city"></span></p>
                    <p class="userDetails__wrapper__item">
                        <strong>{{ __('Total Requests: ') }}</strong> <span class="total_jobs"></span>
                        <a href="#" id="view_requests_btn" class="btn btn-sm btn-primary">{{ __('Show') }}</a>
                    </p>
                    <p class="userDetails__wrapper__item">
                        <strong>{{ __('Total Equipment: ') }}</strong> <span class="total_equipment"></span>
                        <a href="#" id="view_equipment_btn"
                            class="btn btn-sm btn-primary">{{ __('Show') }}</a>
                    </p>


                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary mt-4"
                    data-bs-dismiss="modal">{{ __('Close') }}</button>
            </div>
        </div>
    </div>
</div>
