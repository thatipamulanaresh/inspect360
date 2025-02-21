<?php

// Include the database connection
include 'connection.php';
header('Content-Type: application/json');

// Get POST data
$user_id = $_POST['user_id'] ?? null;
$type_id = $_POST['type_id'] ?? null;
$dist_id = $_POST['dist_id'] ?? null;
$industry_id = $_POST['industry_id'] ?? null;
$start_date = $_POST['start_date'] ?? null;
$end_date = $_POST['end_date'] ?? null;

// Initialize an array to track missing fields
$missing_fields = [];

// Validate mandatory fields
if (empty($user_id)) $missing_fields[] = 'user_id';
if (empty($type_id)) $missing_fields[] = 'type_id';
if (empty($dist_id)) $missing_fields[] = 'dist_id';
if (empty($industry_id)) $missing_fields[] = 'industry_id';

// Check if there are any missing fields and return an error response
if (!empty($missing_fields)) {
    echo json_encode([
        "status" => false,
        "message" => "The following fields are required: " . implode(", ", $missing_fields)
    ]);
    exit;
}

// Check if the record with the same industry_id already exists
$check_sql = "SELECT * FROM inspection_assign WHERE industry_id = ?";
$stmt = $con->prepare($check_sql);
$stmt->bind_param("s", $industry_id);
$stmt->execute();
$check_result = $stmt->get_result();

if ($check_result->num_rows > 0) {
    echo json_encode([
        "status" => false,
        "message" => "An entry with the same industry_id already exists."
    ]);
    exit;
}

// Build the SQL query for insertion using prepared statements
$insert_sql = "INSERT INTO inspection_assign (
                    industry_id, type_id, dist_id, user_id, start_date, end_date, assigned_datetime
                ) VALUES (?, ?, ?, ?, ?, ?, NOW())";  // Using NOW() for the current timestamp
$stmt = $con->prepare($insert_sql);
$stmt->bind_param("ssssss", $industry_id, $type_id, $dist_id, $user_id, $start_date, $end_date);

// Execute the query and check for success
if ($stmt->execute()) {
    echo json_encode([
        "status" => true,
        "message" => "Assigned successfully."
    ]);
} else {
    echo json_encode([
        "status" => false,
        "message" => "Assigned failed: " . $stmt->error
    ]);
}

// Close the prepared statement and database connection
$stmt->close();
$con->close();

?>
