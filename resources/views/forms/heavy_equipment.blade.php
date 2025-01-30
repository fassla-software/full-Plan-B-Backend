<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Heavy Equipment Form</title>
    <style>
        /* Add your CSS here (already included in your original example) */
        body {
            font-family: Arial, sans-serif;
            background-color: #f8fafc;
        }
        .container {
            max-width: 800px;
            margin: 2rem auto;
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            margin-bottom: 1.5rem;
            color: #1f2937;
        }
        .form-group {
            margin-bottom: 1rem;
        }
        label {
            display: block;
            font-weight: bold;
            margin-bottom: 0.5rem;
        }
        input, textarea, select, button {
            width: 100%;
            padding: 0.5rem;
            font-size: 1rem;
            margin-bottom: 1rem;
        }
        button {
            background-color: #2563eb;
            color: white;
            border: none;
            cursor: pointer;
            padding: 0.75rem;
            font-size: 1rem;
        }
        button:hover {
            background-color: #1d4ed8;
        }
    </style>
</head>
<body>
    <div class="container">
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <h1>Heavy Equipment Form</h1>
        <form method="POST" action="{{ route('form.store', ['subCategory' => $subCategory, 'subSubCategory' => $equipment_type]) }}" enctype="multipart/form-data">
        @csrf

            <!-- Category ID -->
            <div class="form-group">
                <label for="category_id">Category ID:</label>
                <input type="number" id="category_id" name="category_id" value="1" readonly>
            </div>

            <!-- Name -->
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required>
            </div>

            <!-- Size -->
            <div class="form-group">
                <label for="size">Size:</label>
                <input type="text" id="size" name="size">
            </div>

            <!-- Model -->
            <div class="form-group">
                <label for="model">Model:</label>
                <input type="text" id="model" name="model">
            </div>

            <!-- Year of Manufacture -->
            <div class="form-group">
                <label for="year_of_manufacture">Year of Manufacture:</label>
                <input type="number" id="year_of_manufacture" name="year_of_manufacture">
            </div>

            <!-- Moves On -->
            <div class="form-group">
                <label for="moves_on">Moves On:</label>
                <input type="text" id="moves_on" name="moves_on">
            </div>

            <!-- Current Equipment Location -->
            <div class="form-group">
                <label for="current_equipment_location">Current Equipment Location:</label>
                <input type="text" id="current_equipment_location" name="current_equipment_location">
            </div>

            <!-- Images -->
            <div class="form-group">
                <label for="data_certificate_image">Data Certificate Image:</label>
                <input type="file" id="data_certificate_image" name="data_certificate_image">
            </div>
            <div class="form-group">
                <label for="driver_license_front_image">Driver License Front Image:</label>
                <input type="file" id="driver_license_front_image" name="driver_license_front_image">
            </div>
            <div class="form-group">
                <label for="driver_license_back_image">Driver License Back Image:</label>
                <input type="file" id="driver_license_back_image" name="driver_license_back_image">
            </div>
            <div class="form-group">
                <label for="additional_equipment_images">Additional Equipment Images:</label>
                <input type="file" id="additional_equipment_images" name="additional_equipment_images[]" multiple>
            </div>

            <!-- Special Rental Conditions -->
            <div class="form-group">
                <label for="special_rental_conditions">Special Rental Conditions:</label>
                <textarea id="special_rental_conditions" name="special_rental_conditions" rows="4"></textarea>
            </div>

            <!-- Numeric Fields -->
            @foreach ([
                'blade_width', 'blade_width_near_digging_arm', 'engine_power', 'tank_capacity',
                'panda_width', 'max_equipment_load', 'boom_length', 'load_at_max_boom_height',
                'load_at_max_horizontal_boom_extension',
            ] as $field)
            <div class="form-group">
                <label for="{{ $field }}">{{ ucwords(str_replace('_', ' ', $field)) }}:</label>
                <input type="number" step="any" id="{{ $field }}" name="{{ $field }}">
            </div>
            @endforeach

            <!-- Boolean Fields -->
            @foreach ([
                'has_bitumen_temp_gauge', 'has_bitumen_level_gauge',
                'has_discharge_pump_with_liters_meter'
            ] as $field)
            <div class="form-group">
                <label for="{{ $field }}">{{ ucwords(str_replace('_', ' ', $field)) }}:</label>
                <select id="{{ $field }}" name="{{ $field }}">
                    <option value="1">Yes</option>
                    <option value="0">No</option>
                </select>
            </div>
            @endforeach

            <!-- Submit Button -->
            <button type="submit">Submit</button>
        </form>
    </div>
</body>
</html>
