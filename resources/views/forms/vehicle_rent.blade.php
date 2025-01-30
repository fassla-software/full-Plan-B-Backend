<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vehicle Rent Form</title>
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
            max-width: 700px;
            margin: 2rem auto;
            padding: 1.5rem;
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

        input, button {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 1rem;
        }

        input:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.3);
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
        <h1>Site Service Cars Form</h1>
        <div class="form-container">
            <form method="POST" action="{{ route('form.store', 'vehicleRent') }}" enctype="multipart/form-data">
                @csrf
                <!-- Category ID -->
                <div class="form-group">
                    <label for="category_id">Category ID:</label>
                    <input type="number" name="category_id" id="category_id" required>
                </div>

                <!-- Size -->
                <div class="form-group">
                    <label for="size">Size:</label>
                    <input type="text" name="size" id="size">
                </div>

                <!-- Model -->
                <div class="form-group">
                    <label for="model">Model:</label>
                    <input type="text" name="model" id="model">
                </div>

                <!-- Year of Manufacture -->
                <div class="form-group">
                    <label for="year_of_manufacture">Year of Manufacture:</label>
                    <input type="number" name="year_of_manufacture" id="year_of_manufacture">
                </div>

                <!-- Image -->
                <div class="form-group">
                    <label for="image">Image:</label>
                    <input type="file" name="image" id="image">
                </div>

                <!-- Submit Button -->
                <button type="submit">Submit</button>
            </form>
        </div>
    </div>
</body>
</html>
