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
            <form method="GET" action="{{ route('form.show', '') }}" id="categoryForm">
                <!-- Select Category -->
                <div class="form-group">
                    <label for="category">Select a Category:</label>
                    <select name="category" id="category" onchange="populateSubCategory(this.value)">
                        <option value="" disabled selected>-- Choose a Category --</option>
                        <option value="heavyEquipment">Heavy Equipment</option>
                        <option value="vehicleRental">Vehicle Rental</option>
                    </select>
                </div>

                <!-- Sub Category -->
                <div class="form-group">
                    <label for="subCategory">Select Equipment Type:</label>
                    <select name="equipment_type" id="subCategory" disabled>
                        <option value="" disabled selected>-- First Select a Category --</option>
                    </select>
                </div>

                <!-- Proceed Button -->
                <button type="button" onclick="redirectToForm(document.getElementById('category'))" id="proceedBtn" disabled>
                    Proceed
                </button>
            </form>
        </div>
    </div>

        <script>
        const equipmentOptions = {
            heavyEquipment: [
                { value: 'loader', label: 'Loader' },
                { value: 'excavator', label: 'Excavator' },
                { value: 'backhoeLoader', label: 'Backhoe Loader' },
                { value: 'bulldozer', label: 'Bulldozer' },
                { value: 'grader', label: 'Grader' },
                { value: 'harrow', label: 'Harrow' },
                { value: 'asphaltScraper', label: 'Asphalt Scraper' },
                { value: 'bitumenSprayerTruck', label: 'Bitumen Sprayer Truck' },
                { value: 'finisher', label: 'Finisher' },
                { value: 'telehandler', label: 'Telehandler' },
                { value: 'forklift', label: 'Forklift' },
                { value: 'agriculturalTractor', label: 'Agricultural Tractor' },
                { value: 'equipmentTransportFlatbed', label: 'Equipment Transport Flatbed' }
            ],
            vehicleRental: [
                // Add vehicle rental options here
            ]
        };

        function populateSubCategory(category) {
            const subCategorySelect = document.getElementById('subCategory');
            const proceedBtn = document.getElementById('proceedBtn');

            // Clear existing options
            subCategorySelect.innerHTML = '<option value="" disabled selected>-- Select Equipment Type --</option>';

            if (category) {
                // Enable the sub-category dropdown
                subCategorySelect.disabled = false;

                // Add new options based on selected category
                const options = equipmentOptions[category] || [];
                options.forEach(option => {
                    const optionElement = document.createElement('option');
                    optionElement.value = option.value;
                    optionElement.textContent = option.label;
                    subCategorySelect.appendChild(optionElement);
                });

                // Enable proceed button when category is selected
                proceedBtn.disabled = false;
            } else {
                subCategorySelect.disabled = true;
                proceedBtn.disabled = true;
            }
        }

        // Your original redirect function
        function redirectToForm(selectElement) {
            const selectedValue = selectElement.value;
            const equipmentType = document.getElementById('subCategory').value;
            if (selectedValue) {
                window.location.href = `{{ route('form.show', '') }}/${selectedValue}?equipment_type=${equipmentType}`;
            }
        }

        // Add event listener to sub-category select to handle selection
        document.getElementById('subCategory').addEventListener('change', function() {
            const category = document.getElementById('category').value;
            if (category && this.value) {
                // You could modify the redirect here if you want to include both values
                // Or handle it differently based on your needs
            }
        });
    </script>
    </body>
</html>
