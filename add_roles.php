<?php
include 'connection.php';
include 'response.php';


// Get role from POST request
if (!isset($_POST['role']) || empty($_POST['role'])) {
    response("Role is required.", null, false);
}

$role = $_POST['role'];

// Sanitize the input to prevent SQL injection
$role = mysqli_real_escape_string($con, $role);

// Check if the role already exists in `user_roles`
$check_sql = "SELECT * FROM user_roles WHERE role = '$role'";
$check_result = $con->query($check_sql);

if ($check_result->num_rows > 0) {
    // Role already exists
    response("Role already exists.", null, false);
} else {
    // Insert the new role into the `user_roles` table
    $insert_sql = "INSERT INTO user_roles (role) VALUES ('$role')";

    if ($con->query($insert_sql) === TRUE) {
        response("New role added successfully.", ["role" => $role], true);
    } else {
        response("Error adding role.", $con->error, false);
    }
}

// Close the database connection
$con->close();
?>
