<x-validation.error />
<table class="DataTable_activation">
    <thead>
    <tr>
        <th>{{__('ID')}}</th>
        <th>{{__('Faq')}}</th>
        <th>{{__('Create Date')}}</th>
    </tr>
    </thead>
    <tbody>
    @foreach($all_faqs as $faq)
        <tr>
            <td>{{ $faq->id }}</td>
            <td>{{ $faq->question }}</td>
            <td>{{ $faq->created_at->toFormattedDateString() }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
<x-pagination.laravel-paginate :allData="$all_faqs"/>
