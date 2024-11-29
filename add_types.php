<?php
include 'connection.php';
include 'response.php';


// Get role from POST request
if (!isset($_POST['inspection_type']) || empty($_POST['inspection_type'])) {
    response("inspection-type is required.", null, false);
}

$inspection_type = $_POST['inspection_type'];

// Sanitize the input to prevent SQL injection
$inspection_type = mysqli_real_escape_string($con, $inspection_type);

$check_sql = "SELECT * FROM inspection_types WHERE inspection_type = '$inspection_type'";
$check_result = $con->query($check_sql);

if ($check_result->num_rows > 0) {
    response("inspection_type already exists.", null, false);
} else {
    $insert_sql = "INSERT INTO inspection_types (inspection_type) VALUES ('$inspection_type')";

    if ($con->query($insert_sql) === TRUE) {
        response("New  inspection-type added successfully.", ["inspection_type" => $inspection_type], true);
    } else {
        response("Error adding role.", $con->error, false);
    }
}

// Close the database connection
$con->close();
?>
