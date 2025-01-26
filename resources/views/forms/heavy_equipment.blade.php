@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-center mb-8">Heavy Equipment Form</h1>
    <div class="max-w-lg mx-auto bg-white rounded-lg shadow-lg p-6">
        <form method="POST" action="{{ route('form.store', 'heavy_equipment') }}" enctype="multipart/form-data">
            @csrf
            <div class="mb-4">
                <label for="name" class="block text-lg font-medium text-gray-700 mb-2">Equipment Name:</label>
                <input type="text" name="name" id="name" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>
            <div class="mb-4">
                <label for="image" class="block text-lg font-medium text-gray-700 mb-2">Upload Image:</label>
                <input type="file" name="image" id="image" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <button type="submit" class="w-full mt-4 px-4 py-2 bg-blue-500 text-white rounded-lg shadow hover:bg-blue-600">
                Submit
            </button>
        </form>
    </div>
</div>
@endsection
