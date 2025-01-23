<x-validation.error />
<table class="DataTable_activation">
    <thead>
    <tr>
        <th>{{__('ID')}}</th>
        <th>{{__('Image')}}</th>
        <th>{{__('Blog Title')}}</th>
        <th>{{__('Blog Category')}}</th>
        <th>{{__('Status (change by admin)')}}</th>
        <th>{{__('Create Date')}}</th>
        <th>{{__('Action')}}</th>
    </tr>
    </thead>
    <tbody>
    @foreach($all_blogs as $blog)
        <tr>
            <td>#000{{ $blog->id }}</td>
            <td><span class="img_100">{!! render_image_markup_by_attachment_id($blog->image) !!}</span></td>
            <td>{{ $blog->title }}</td>
            <td>{{ $blog?->category?->category }}</td>
            <td>
                @if($blog->status === 0)
                    <span class="alert alert-warning">{{__('Inactive')}}</span>
                @else
                    <span class="alert alert-success" >{{__('Active')}}</span>
                @endif
            </td>
            <td>
                {{ $blog->created_at->toFormattedDateString() }}
            </td>
            <td>
                <x-status.table.select-action :title="__('Select Action')"/>
                <ul class="dropdown-menu status_dropdown__list">
                    @can('blog-edit')
                        <li class="status_dropdown__item">
                            <a href="{{ route('admin.blog.edit',$blog->id) }}" class="btn dropdown-item status_dropdown__list__link">{{ __('Edit Blog') }}</a>
                        </li>
                    @endcan
                    @can('blog-delete')
                        <li class="status_dropdown__item">
                            <x-popup.delete-popup :title="__('Delete Blog')" :url="route('admin.blog.destroy',$blog->id)"/>
                        </li>
                    @endcan
                </ul>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
<x-pagination.laravel-paginate :allData="$all_blogs"/>
