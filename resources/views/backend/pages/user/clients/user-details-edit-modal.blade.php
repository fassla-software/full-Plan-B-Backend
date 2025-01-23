<!-- State Edit Modal -->
<div class="modal fade" id="userDetailsEditModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">{{ __('Edit User Info') }}</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{route('admin.user.info.edit')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body" id="edit_user_details">
                    <div class="edit_user_detailsInfo">
                        <span class="email_send_message d-none"></span>
                        <input type="hidden" name="edit_user_id" id="edit_user_id" value="">
                        <x-form.text :title="__('First Name')" :type="'text'" :name="'edit_first_name'" :id="'edit_first_name'" :placeholder="__('Enter first name')"/>
                        <x-form.text :title="__('Last Name')" :type="'text'" :name="'edit_last_name'" :id="'edit_last_name'" :placeholder="__('Enter last name')"/>
                        <x-form.text :title="__('User Name')" :type="'text'" :name="'edit_username'" :id="'edit_username'" :placeholder="__('Enter last name')"/>
                        <span id="user_name_availability"></span>
                        <x-form.text :title="__('Email')" :type="'text'" :name="'edit_email'" :id="'edit_email'" :placeholder="__('Enter email')"/>
                        <span id="email_availability"></span>
                        <x-form.text :title="__('Phone')" :type="'number'" :name="'edit_phone'" :id="'edit_phone'" :placeholder="__('Enter phone number')"/>
                        <span id="phone_availability"></span>
                        <div class="single-input mb-3">
                            <label class="label-title">{{ __('Select Country') }}</label>
                            <select name="edit_country" id="edit_country" class="form-control country_select2">
                                <option value="">{{ __('Select Country') }}</option>
                                @foreach($all_countries = \Modules\CountryManage\Entities\Country::all_countries() as $country)
                                    <option value="{{ $country->id }}">{{ $country->country }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="single-input mb-3">
                            <label class="label-title">{{ __('Select State') }}</label>
                            <select name="edit_state" id="edit_state" class="form-control get_country_state state_select2">
                                <option value="">{{ __('Select State') }}</option>
                                @foreach($all_states = \Modules\CountryManage\Entities\State::all_states() as $state)
                                    <option value="{{ $state->id }}">{{ $state->state }}</option>
                                @endforeach
                            </select>
                            <span class="state_info"></span>
                        </div>
                        <div class="single-input">
                            <label class="label-title">{{ __('Select City') }}</label>
                            <select name="edit_city" id="edit_city" class="form-control get_state_city city_select2">
                                <option value="">{{ __('Select City') }}</option>
                                @foreach($all_cities = \Modules\CountryManage\Entities\City::all_cities() as $city)
                                    <option value="{{ $city->id }}">{{ $city->city }}</option>
                                @endforeach
                            </select>
                            <span class="city_info"></span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary mt-4" data-bs-dismiss="modal">{{ __('Close') }}</button>
                    <x-btn.submit :title="__('Update')" :class="'btn btn-primary mt-4 pr-4 pl-4 update_user_info'" />
                </div>
            </form>
        </div>
    </div>
</div>
