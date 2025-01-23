<!-- State Edit Modal -->
<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">{{ __('Add New State') }}</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{route('admin.state.all')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <x-form.text :title="__('State')" :type="__('text')" :name="'state'" :id="'state'" :placeholder="__('Enter state name')"/>
                    <x-form.select2-country-dropdown :title="__('Select Country')" :name="'country'" :id="'country'" :allData="$all_countries" />
                    <x-form.active-inactive :title="__('Select Status')" :info="__('If you select inactive the services will off for the country')" />
                    <x-form.timezone :title="__('Select Timezone')" :name="'timezone'" :id="'timezone'" :class="'form-control timezone_select2_add'"  />
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary mt-4" data-bs-dismiss="modal">{{ __('Close') }}</button>
                    <x-btn.submit :title="__('save')" :class="'btn btn-primary mt-4 pr-4 pl-4 add_country'" />
                </div>
            </form>
        </div>
    </div>
</div>
