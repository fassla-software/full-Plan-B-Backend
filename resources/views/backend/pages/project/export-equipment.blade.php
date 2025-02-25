<table>
    <thead>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>User Name</th>
        <th>Category</th>
        <th>Model</th>
        <th>Created At</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($equipments as $equipment)
        <tr>
            <td>{{ $equipment['id'] }}</td>
            <td>{{ $equipment['name'] ?? 'N/A' }}</td>
            <td>{{ $equipment['user_name'] ?? 'N/A' }}</td>
            <td>{{ $equipment['category_name'] ?? 'N/A' }}</td>
            <td>{{ $equipment['model'] ?? 'N/A' }}</td>
            <td>{{ $equipment['created_at'] ?? 'N/A' }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
