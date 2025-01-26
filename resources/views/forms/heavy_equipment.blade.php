<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Heavy Equipment Form</title>
    <style>
        /* Global Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f8fafc;
            color: #333;
            line-height: 1.6;
        }

        .container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 1rem;
        }

        h1 {
            text-align: center;
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 1.5rem;
            color: #1f2937;
        }

        .form-container {
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 2rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        label {
            display: block;
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: #4b5563;
        }

        input, textarea, button {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 1rem;
        }

        input:focus, textarea:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.3);
        }

        textarea {
            resize: none;
        }

        button {
            background-color: #2563eb;
            color: #ffffff;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s ease;
            border: none;
        }

        button:hover {
            background-color: #1d4ed8;
        }

        /* Responsive Styling */
        @media (max-width: 768px) {
            .container {
                padding: 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Heavy Equipment Form</h1>
        <div class="form-container">
            <form method="POST" action="{{ route('form.store', 'heavy_equipment') }}" enctype="multipart/form-data">
                @csrf
                <!-- Category ID -->
                <div class="form-group">
                    <label for="category_id">Category ID:</label>
                    <input type="number" id="category_id" name="category_id" required>
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

                <!-- Data Certificate Image -->
                <div class="form-group">
                    <label for="data_certificate_image">Data Certificate Image:</label>
                    <input type="file" id="data_certificate_image" name="data_certificate_image">
                </div>

                <!-- Driver License Front Image -->
                <div class="form-group">
                    <label for="driver_license_front_image">Driver License Front Image:</label>
                    <input type="file" id="driver_license_front_image" name="driver_license_front_image">
                </div>

                <!-- Driver License Back Image -->
                <div class="form-group">
                    <label for="driver_license_back_image">Driver License Back Image:</label>
                    <input type="file" id="driver_license_back_image" name="driver_license_back_image">
                </div>

                <!-- Special Rental Conditions -->
                <div class="form-group">
                    <label for="special_rental_conditions">Special Rental Conditions:</label>
                    <textarea id="special_rental_conditions" name="special_rental_conditions" rows="4"></textarea>
                </div>

                <!-- Submit Button -->
                <button type="submit">Submit</button>
            </form>
        </div>
    </div>
</body>
</html>
