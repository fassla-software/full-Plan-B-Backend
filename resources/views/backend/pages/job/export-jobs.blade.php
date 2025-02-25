<table>
    <thead>
    <tr>
        <th>ID</th>
{{--        <th>Name</th>--}}
        <th>Equipment Name</th>
        <th>User Name</th>
        <th>Category</th>
        <th>Created At</th>
        <th>Max Arrival Date</th>
        <th>Max Offer Deadline</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($jobs as $job)
        <tr>
            <td>{{ $job['id'] }}</td>
{{--            <td>{{ $job['name'] ?? 'N/A' }}</td>--}}
            <td>{{ $job['equipment_name'] ?? 'N/A' }}</td>
            <td>{{ $job['user_name'] ?? 'N/A' }}</td>
            <td>{{ $job['category_name'] ?? 'N/A' }}</td>
            <td>{{ $job['created_at'] ?? 'N/A' }}</td>
            <td>{{ $job['max_arrival_date'] ?? 'N/A' }}</td>
            <td>{{ $job['max_offer_deadline'] ?? 'N/A' }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
