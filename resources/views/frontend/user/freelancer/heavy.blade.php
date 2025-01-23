<!DOCTYPE html>
<html>
<head>
    <title>Equipment Form</title>
</head>
<body>
<form action="{{ route('heavy.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <label for="category">Category:</label>
    <input type="text" id="category" name="category" required><br>

    <label for="size">Size:</label>
    <input type="text" id="size" name="size" required maxlength="191"><br>

    <label for="model">Model:</label>
    <input type="text" id="model" name="model" required maxlength="191"><br>

    <label for="year_of_manufacture">Year of Manufacture:</label>
    <input type="number" id="year_of_manufacture" name="year_of_manufacture" required maxlength="4"><br>

    <label for="moves_on">Moves On:</label>
    <input type="text" id="moves_on" name="moves_on" required maxlength="191"><br>

    <label for="current_equipment_location">Current Equipment Location:</label>
    <input type="text" id="current_equipment_location" name="current_equipment_location" required maxlength="191"><br>

    <label for="data_certificate_image">Data Certificate Image:</label>
    <input type="file" id="data_certificate_image" name="data_certificate_image" ><br>

    <label for="driver_license_front_image">Driver License Front Image:</label>
    <input type="file" id="driver_license_front_image" name="driver_license_front_image" ><br>

    <label for="driver_license_back_image">Driver License Back Image:</label>
    <input type="file" id="driver_license_back_image" name="driver_license_back_image" ><br>

    <label for="additional_equipment_images">Additional Equipment Images:</label>
    <input type="file" id="additional_equipment_images" name="additional_equipment_images" ><br>

    <label for="special_rental_conditions">Special Rental Conditions:</label>
    <input type="text" id="special_rental_conditions" name="special_rental_conditions"><br>

    <label for="blade_width">Blade Width:</label>
    <input type="number" id="blade_width" name="blade_width"><br>

    <label for="blade_width_near_digging_arm">Blade Width Near Digging Arm:</label>
    <input type="number" id="blade_width_near_digging_arm" name="blade_width_near_digging_arm"><br>

    <label for="engine_power">Engine Power:</label>
    <input type="number" id="engine_power" name="engine_power"><br>

    <label for="milling_blade_width">Milling Blade Width:</label>
    <input type="number" id="milling_blade_width" name="milling_blade_width"><br>

    <label for="sprinkler_system_type">Sprinkler System Type:</label>
    <input type="text" id="sprinkler_system_type" name="sprinkler_system_type" maxlength="191"><br>

    <label for="tank_capacity">Tank Capacity:</label>
    <input type="number" id="tank_capacity" name="tank_capacity"><br>

    <label for="panda_width">Panda Width:</label>
    <input type="number" id="panda_width" name="panda_width"><br>

    <label for="has_bitumen_temp_gauge">Has Bitumen Temp Gauge:</label>
    <input type="checkbox" id="has_bitumen_temp_gauge" name="has_bitumen_temp_gauge" value="1"><br>

    <label for="has_bitumen_level_gauge">Has Bitumen Level Gauge:</label>
    <input type="checkbox" id="has_bitumen_level_gauge" name="has_bitumen_level_gauge" value="1"><br>

    <label for="paving_range">Paving Range:</label>
    <input type="text" id="paving_range" name="paving_range" maxlength="191"><br>

    <label for="max_equipment_load">Max Equipment Load:</label>
    <input type="number" id="max_equipment_load" name="max_equipment_load"><br>

    <label for="boom_length">Boom Length:</label>
    <input type="number" id="boom_length" name="boom_length"><br>

    <label for="load_at_max_boom_height">Load at Max Boom Height:</label>
    <input type="number" id="load_at_max_boom_height" name="load_at_max_boom_height"><br>

    <label for="load_at_max_horizontal_boom_extension">Load at Max Horizontal Boom Extension:</label>
    <input type="number" id="load_at_max_horizontal_boom_extension" name="load_at_max_horizontal_boom_extension"><br>

    <label for="max_lifting_point">Max Lifting Point:</label>
    <input type="number" id="max_lifting_point" name="max_lifting_point"><br>

    <label for="attachments">Attachments:</label>
    <input type="text" id="attachments" name="attachments"><br>

    <label for="has_tank_discharge_pump">Has Tank Discharge Pump:</label>
    <input type="checkbox" id="has_tank_discharge_pump" name="has_tank_discharge_pump" value="1"><br>

    <label for="has_band_sprinkler_bar">Has Band Sprinkler Bar:</label>
    <input type="checkbox" id="has_band_sprinkler_bar" name="has_band_sprinkler_bar" value="1"><br>

    <label for="has_discharge_pump_with_liters_meter">Has Discharge Pump With Liters Meter:</label>
    <input type="checkbox" id="has_discharge_pump_with_liters_meter" name="has_discharge_pump_with_liters_meter" value="1"><br>

    <button type="submit">Submit</button>
</form>
</body>
</html>
