<?php
include 'connection.php';
include 'response.php';

// Function to validate and sanitize inputs
function sanitize_input($data) {
    // Trim unnecessary whitespace and escape special characters
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
            response("Update Failed: " . $stmt->error, [], "False");
        }
        $stmt->close();
    } else {
        response("No change in status", [], "True");
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if required parameters exist
    if (isset($_POST['status'], $_POST['id'], $_POST['data_type'])) {
        // Sanitize inputs to prevent XSS or other malicious code
        $status = sanitize_input($_POST['status']);
        $id = (int) $_POST['id'];  // Cast to integer for id to prevent non-numeric input
        $type = sanitize_input($_POST['data_type']);

        // Validate the data type to prevent unwanted inputs
        if (!in_array($type, ['industry_information', 'user_details'])) {
            response("Invalid data type", null, "False");
        }

        // Execute update based on data type
        switch ($type) {
            case 'industry_information':
                updateStatus('industry_information', $status, $id, $con);
                break;
            case 'user_details':
                updateStatus('user_details', $status, $id, $con);
                break;
            default:
                response("Invalid data type", null, "False");
                break;
        }
    } else {
        response("Missing parameters", null, "False");
    }
} else {
    response("Invalid request method", null, "False");
}

$con->close();
