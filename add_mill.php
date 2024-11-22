<?php

// Include the database connection
include 'connection.php';
header('Content-Type: application/json');

// Get POST data
$name = $_POST['name'];
$type_id = $_POST['type_id'];
$district = $_POST['district'];
$name_of_amc = $_POST['name_of_amc'];
$address = $_POST['address'];
$location_link = $_POST['location_link'] ?? null;
$latitude = $_POST['latitude'] ?? null;
$longitude = $_POST['longitude'] ?? null;
$cpo_name = $_POST['cpo_name'] ?? null;
$cpo_mobile = $_POST['cpo_mobile'] ?? null;
$market_secretary_name = $_POST['market_secretary_name'] ?? null;
$market_secretary_mobile = $_POST['market_secretary_mobile'] ?? null;
$amc_name = $_POST['amc_name'] ?? null;
$amc_mobile = $_POST['amc_mobile'] ?? null;
$deo_name = $_POST['deo_name'] ?? null;
$deo_mobile = $_POST['deo_mobile'] ?? null;

// Initialize an array to track missing fields
$missing_fields = [];

// Validate mandatory fields
if (empty($name)) $missing_fields[] = 'name';
if (empty($type_id)) $missing_fields[] = 'type_id';
if (empty($district)) $missing_fields[] = 'district';
if (empty($name_of_amc)) $missing_fields[] = 'name_of_amc';

// Ensure location is valid
if (empty($location_link) && (empty($latitude) || empty($longitude))) {
    $missing_fields[] = 'location_link or (latitude and longitude)';
}

// Check if the image file was uploaded
$image = null;
if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
    $image_name = $_FILES['image']['name'];
    $image_tmp = $_FILES['image']['tmp_name'];
    $image_size = $_FILES['image']['size'];
    $image_type = $_FILES['image']['type'];

    // Validate image file type (only allow jpg, png, jpeg, gif)
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
    if (!in_array($image_type, $allowed_types)) {
        $missing_fields[] = 'image type is not valid (only jpg, png, gif allowed)';
    }

    // Validate image size (example: max 2MB)
    if ($image_size > 2 * 1024 * 1024) {
        $missing_fields[] = 'image size exceeds the maximum limit of 2MB';
    }

    // Directory where the image will be stored
    $upload_dir = 'uploads/';

    // Check if the directory exists, if not, create it
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true); // Create the directory with full permissions
    }

    // Generate a unique name for the image to avoid overwriting existing files
    $image = $upload_dir . uniqid('img_', true) . '.' . pathinfo($image_name, PATHINFO_EXTENSION);

    // Move the uploaded file to the "uploads" directory
    if (!move_uploaded_file($image_tmp, $image)) {
        $missing_fields[] = 'Failed to upload image';
    }
}

// Check if there are any missing fields and return an error response
if (!empty($missing_fields)) {
    echo json_encode([
        "success" => false,
        "message" => "The following fields are required: " . implode(", ", $missing_fields)
    ]);
    exit;
}

// Check if the name already exists
$check_sql = "SELECT id FROM industry_information WHERE name = '$name'";
$check_result = $con->query($check_sql);

if ($check_result->num_rows > 0) {
    echo json_encode([
        "success" => false,
        "message" => "The name already exists in the database."
    ]);
    exit;
}

// Build the SQL query for insertion
$sql = "INSERT INTO industry_information (
            name, type_id, district, name_of_amc, address, location_link, latitude, longitude, 
            image, cpo_name, cpo_mobile, market_secretary_name, market_secretary_mobile, 
            amc_name, amc_mobile, deo_name, deo_mobile
        ) VALUES (
            '$name', '$type_id', '$district', '$name_of_amc', '$address', '$location_link', 
            '$latitude', '$longitude', '$image', '$cpo_name', '$cpo_mobile', 
            '$market_secretary_name', '$market_secretary_mobile', '$amc_name', 
            '$amc_mobile', '$deo_name', '$deo_mobile'
        )";

// Execute the query and check for success
if ($con->query($sql) === TRUE) {
    echo json_encode(["success" => true, "message" => "Mill added successfully."]);
} else {
    echo json_encode(["success" => false, "message" => "Mill insertion failed: " . $con->error]);
}

// Close the database connection
$con->close();
?>
