<!-- resources/views/forms/heavy_equipment.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-center my-4">Site Service Car</h1>
    <form method="POST" action="{{ route('form.store', 'heavy_equipment') }}" enctype="multipart/form-data">
        @csrf
        <!-- Add your form fields for heavy equipment here -->
        <div class="form-group mb-3">
            <label for="name" class="form-label">Equipment Name:</label>
            <input type="text" name="name" id="name" class="form-control" required>
        </div>
        <div class="form-group mb-3">
            <label for="image" class="form-label">Upload Image:</label>
            <input type="file" name="image" id="image" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>
@endsection
