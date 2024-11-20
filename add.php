<?php
include 'connection.php';

// Get POST data
$name = $_POST['name'] ?? null;
$type_id = $_POST['type_id'] ?? null;
$district = $_POST['district'] ?? null;
$name_of_amc = $_POST['name_of_amc'] ?? null;
$address = $_POST['address'] ?? null;
$location_link = $_POST['location_link'] ?? null;
$latitude = $_POST['latitude'];
$longitude = $_POST['longitude'];
$image = $_POST['image'] ?? null;
$cpo_name = $_POST['cpo_name'] ?? null;
$cpo_mobile = $_POST['cpo_mobile'] ?? null;
$market_secretary_name = $_POST['market_secretary_name'] ?? null;
$market_secretary_mobile = $_POST['market_secretary_mobile'] ?? null;
$amc_name = $_POST['amc_name'] ?? null;
$amc_mobile = $_POST['amc_mobile'] ?? null;
$deo_name = $_POST['deo_name'] ?? null;
$deo_mobile = $_POST['deo_mobile'] ?? null;

// Validate mandatory fields
if (!$name || !$type_id) {
    echo json_encode(["success" => false, "message" => "Name and type_id are required."]);
    exit;
}

// Escape input to prevent basic SQL injection
$name = $con->real_escape_string($name);
$type_id = $con->real_escape_string($type_id);
$district = $con->real_escape_string($district);
$name_of_amc = $con->real_escape_string($name_of_amc);
$address = $con->real_escape_string($address);
$location_link = $con->real_escape_string($location_link);
$latitude = $con->real_escape_string($latitude);
$longitude = $con->real_escape_string($longitude);
$image = $con->real_escape_string($image);
$cpo_name = $con->real_escape_string($cpo_name);
$cpo_mobile = $con->real_escape_string($cpo_mobile);
$market_secretary_name = $con->real_escape_string($market_secretary_name);
$market_secretary_mobile = $con->real_escape_string($market_secretary_mobile);
$amc_name = $con->real_escape_string($amc_name);
$amc_mobile = $con->real_escape_string($amc_mobile);
$deo_name = $con->real_escape_string($deo_name);
$deo_mobile = $con->real_escape_string($deo_mobile);

// Build the SQL query
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

// Execute the query
if ($con->query($sql) === TRUE) {
    echo json_encode(["success" => true, "message" => "Mill Added successfully."]);
} else {
    echo json_encode(["success" => false, "message" => "Data insertion failed: " . $con->error]);
}

// Close the database conection
$con->close();
?>
