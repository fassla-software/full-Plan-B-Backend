@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-center mb-8">Choose a Category</h1>
    <div class="flex justify-center">
        <div class="w-full max-w-md bg-white rounded-lg shadow-lg p-6">
            <form method="GET" action="{{ route('form.show', '') }}">
                <div class="mb-4">
                    <label for="category" class="block text-lg font-medium text-gray-700 mb-2">Select a Category:</label>
                    <select name="subCategory" id="category" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" onchange="redirectToForm(this)">
                        <option value="" disabled selected>-- Choose a Category --</option>
                        <option value="heavy_equipment">Heavy Equipment</option>
                        <option value="site_service_car">Site Service Car</option>
                        <!-- Add more categories here -->
                    </select>
                </div>
                <button type="button" class="w-full mt-4 px-4 py-2 bg-blue-500 text-white rounded-lg shadow hover:bg-blue-600" onclick="redirectToForm(document.getElementById('category'))">
                    Proceed
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    function redirectToForm(selectElement) {
        const selectedValue = selectElement.value;
        if (selectedValue) {
            window.location.href = `{{ route('form.show', '') }}/${selectedValue}`;
        }
    }
</script>
@endsection
