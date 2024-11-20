<?php
include 'connection.php';
date_default_timezone_set('Asia/Kolkata');
$date = date('Y-m-d H:i:s');

// Check if the POST request contains the required data
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve POST data
    $name = isset($_POST['name']) ? $_POST['name'] : '';
    $role = isset($_POST['role']) ? $_POST['role'] : '';
    $role_id = isset($_POST['role_id']) ? $_POST['role_id'] : '';
    $mobile_number = isset($_POST['mobile_number']) ? $_POST['mobile_number'] : '';
    $otp = isset($_POST['otp']) ? $_POST['otp'] : '';
    $last_login = isset($_POST['last_login']) ? $_POST['last_login'] : '';

    // Check if the mandatory fields are filled
    if ($name && $role && $role_id && $mobile_number) {
        // Check if mobile number already exists
        $check_sql = "SELECT * FROM user_details WHERE mobile_number = '$mobile_number'";
        $check_result = $con->query($check_sql);

        if ($check_result->num_rows > 0) {
            // Mobile number already exists
            echo json_encode(["status" => "error", "message" => "Mobile number already exists"]);
        } else {
            // SQL query to insert the data into the table
            $sql = "INSERT INTO user_details (name, role, role_id, mobile_number, otp, last_login)
                    VALUES ('$name', '$role', '$role_id', '$mobile_number', '$otp', '$date')";

            if ($con->query($sql) === TRUE) {
                echo json_encode(["status" => "success", "message" => "User added successfully"]);
            } else {
                echo json_encode(["status" => "error", "message" => "Error: " . $sql . "<br>" . $con->error]);
            }
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Mandatory fields are missing"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method"]);
}

// Close connection
$con->close();  
?>
