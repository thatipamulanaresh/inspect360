<?php
include 'connection.php';

$select_sql = "SELECT distinct id, name, role, mobile_number, last_login, status
FROM user_details 
WHERE status != 'deleted'";

$result = $con->query($select_sql);

if ($result->num_rows > 0) {
    $questions = array(); // Initialize an array to hold the data

    while ($row = $result->fetch_assoc()) {
        $questions[] = $row; // Add each row to the array
    }

    // Output the JSON response
    echo json_encode(array(
        "status" => "True",
        "message" => "Data Found",
        "data" => $questions
    ));
} else {
    // Output the JSON response for no data
    echo json_encode(array(
        "status" => "False",
        "message" => "No Data Found"
    ));
}

$con->close();
?>
