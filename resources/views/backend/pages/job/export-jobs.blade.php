<table>
    <thead>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>User Name</th>
        <th>Category</th>
        <th>Created At</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($jobs as $job)
        <tr>
            <td>{{ $job['id'] }}</td>
            <td>{{ $job['name'] ?? 'N/A' }}</td>
            <td>{{ $job['user_name'] ?? 'N/A' }}</td>
            <td>{{ $job['category_name'] ?? 'N/A' }}</td>
            <td>{{ $job['created_at'] ?? 'N/A' }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
