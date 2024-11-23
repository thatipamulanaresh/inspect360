<?php
include 'connection.php';
include 'response.php';

// Function to validate and sanitize inputs
function sanitize_input($data) {
    return htmlspecialchars(trim($data));
}

// Function to check and update the status
function updateStatus($table, $status, $id, $con) {
    // Prepare and execute the check query
    $check_sql = "SELECT status FROM $table WHERE id = ?";
    $check_stmt = $con->prepare($check_sql);
    $check_stmt->bind_param('i', $id);
    $check_stmt->execute();
    $check_stmt->store_result();

    if ($check_stmt->num_rows > 0) {
        $check_stmt->bind_result($current_status);
        $check_stmt->fetch();
        $check_stmt->close();

        // Only update if the status is different
        if ($current_status !== $status) {
            $update_sql = "UPDATE $table SET status = ? WHERE id = ?";
            $stmt = $con->prepare($update_sql);
            $stmt->bind_param('si', $status, $id);

            if ($stmt->execute()) {
                response("Update Successful", [], "True");
            } else {
                response("Update Failed", [], "False");
            }
            $stmt->close();
        } else {
            response("No change in status", [], "True");
        }
    } else {
        response("Record not found", [], "False");
    }
}

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate required parameters
    $status = isset($_POST['status']) ? sanitize_input($_POST['status']) : null;
    $id = isset($_POST['id']) ? (int) $_POST['id'] : null;
    $type = isset($_POST['data_type']) ? sanitize_input($_POST['data_type']) : null;

    if ($status && $id && $type) {
        // Validate the data type
        $valid_types = ['industry_information', 'user_details'];
        if (in_array($type, $valid_types)) {
            // Execute the update operation
            updateStatus($type, $status, $id, $con);
        } else {
            response("Invalid data type", [], "False");
        }
    } else {
        response("Missing parameters", [], "False");
    }
} else {
    response("Invalid request method", [], "False");
}

// Close the database connection
$con->close();
?>
