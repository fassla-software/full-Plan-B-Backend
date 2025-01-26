<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Choose a Category</title>
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
            max-width: 600px;
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

        select, button {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 1rem;
        }

        select:focus {
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
        <h1>Choose a Category</h1>
        <div class="form-container">
            <form method="GET" action="{{ route('form.show', '') }}">
                <!-- Select Category -->
                <div class="form-group">
                    <label for="category">Select a Category:</label>
                    <select name="subCategory" id="category" onchange="redirectToForm(this)">
                        <option value="" disabled selected>-- Choose a Category --</option>
                        <option value="heavy_equipment">Heavy Equipment</option>
                        <option value="site_service_car">Site Service Car</option>
                        <!-- Add more categories here -->
                    </select>
                </div>

                <!-- Proceed Button -->
                <button type="button" onclick="redirectToForm(document.getElementById('category'))">
                    Proceed
                </button>
            </form>
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
</body>
</html>
