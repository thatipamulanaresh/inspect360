<?php

include 'connection.php';
date_default_timezone_set('Asia/Kolkata');
$date = date('Y-m-d H:i:s');

// Check if the POST request contains the required data
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve POST data
    $name = isset($_POST['name']) ? $_POST['name'] : '';
    $mobile_number = isset($_POST['mobile_number']) ? $_POST['mobile_number'] : '';
    $role = isset($_POST['role']) ? $_POST['role'] : '';

    // Check if the mandatory fields are filled
    if ($name && $mobile_number && $role) {
        // Check if the role exists in the `user_roles` table
        $role_query = "SELECT id, role FROM user_roles WHERE role = '$role'";
        $role_result = $con->query($role_query);

        if ($role_result && $role_result->num_rows > 0) {
            $role_data = $role_result->fetch_assoc();
            $role_id = $role_data['id'];

            // Check if the mobile number already exists in `user_details`
            $check_sql = "SELECT * FROM user_details WHERE mobile_number = '$mobile_number'";
            $check_result = $con->query($check_sql);

            if ($check_result->num_rows > 0) {
                // Mobile number already exists
                echo json_encode(["status" => "error", "message" => "Mobile number already exists"]);
            } else {
                // Insert the new user into `user_details`
                $sql = "
                    INSERT INTO user_details (name, role, role_id, mobile_number, last_login)
                    VALUES ('$name', '$role', '$role_id', '$mobile_number', '$date')
                ";

                if ($con->query($sql) === TRUE) {
                    echo json_encode([
                        "status" => "success",
                        "message" => "User added successfully",
                        "data" => [
                            "NAME" => $name,
                            "ROLE" => $role,
                            "ROLE_ID" => $role_id,
                            "MOBILE_NUMBER" => $mobile_number
                        ]
                    ]);
                } else {
                    echo json_encode(["status" => "error", "message" => "Error: " . $sql . "<br>" . $con->error]);
                }
            }
        } else {
            echo json_encode(["status" => "error", "message" => "Invalid role provided"]);
        }
    } else {
        echo json_encode(["status" =>   "error", "message" => "Mandatory fields are missing"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method"]);
}

// Close connection
$con->close();

?>
