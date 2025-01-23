<x-validation.error/>
<table class="DataTable_activation">
    <thead>
    <tr>
        <th class="no-sort">
            <div class="mark-all-checkbox">
                <input type="checkbox" class="all-checkbox">
            </div>
        </th>
        <th>{{__('ID')}}</th>
        <th>{{__('Category')}}</th>
        <th>{{__('Meta Title')}}</th>
        <th>{{__('Meta Description')}}</th>
        <th>{{__('Short Description')}}</th>
        <th>{{__('Status')}}</th>
        <th>{{__('Image')}}</th>
        <th>{{__('Action')}}</th>
    </tr>
    </thead>
    <tbody>
    @foreach($all_categories as $cat)
        <tr>
            <td> <x-bulk-action.bulk-delete-checkbox :id="$cat->id"/> </td>
            <td>{{ $cat->id }}</td>
            <td>{{ $cat->category }}</td>
            <td>{{ $cat->meta_title }}</td>
            <td>{{ $cat->meta_description }}</td>
            <td>{{ $cat->short_description }}</td>
            <td><x-status.table.active-inactive :status="$cat->status"/></td>
            <td>
                <span class="img_100">
                    {!! render_image_markup_by_attachment_id($cat->image); !!}
                </span>
                @php $cat_img = get_attachment_image_by_id($cat->image,null,true); @endphp
                @if (!empty($cat_img))
                    @php  $img_url = $cat_img['img_url']; @endphp
                @endif
            </td>
            <td>
                <x-status.table.select-action :title="__('Select Action')"/>
                <ul class="dropdown-menu status_dropdown__list">
                    @can('category-edit')
                    <li class="status_dropdown__item">
                        <a
                            class="btn dropdown-item status_dropdown__list__link edit_category_modal"
                            data-bs-toggle="modal"
                            data-bs-target="#editCategoryModal"
                            data-category="{{ $cat->category }}"
                            data-meta_title="{{ $cat->meta_title }}"
                            data-meta_description="{{ $cat->meta_description }}"
                            data-short_description="{{ $cat->short_description }}"
                            data-slug="{{ $cat->slug }}"
                            data-id="{{ $cat->id }}"
                            data-img_id="{{ $cat->image }}"
                            data-img_url="{{ $img_url }}">
                            {{ __('Edit Category') }}
                        </a>
                    </li>
                    @endcan
                    @can('category-delete')
                    <li class="status_dropdown__item"><x-popup.delete-popup :title="__('Delete Category')" :url="route('admin.category.delete',$cat->id)"/></li>
                    @endcan
                    @can('category-status-change')
                    <li class="status_dropdown__item"><x-status.table.status-change :title="__('Change Status')" :url="route('admin.category.status',$cat->id)"/></li>
                    @endcan
                </ul>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
<x-pagination.laravel-paginate :allData="$all_categories"/>
