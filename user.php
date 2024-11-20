<?php
include 'connection.php';

$select_sql = "SELECT dm.user_id, ud.name, ud.role, ud.mobile_number, ud.last_login, ud.status
FROM district_user_map dm
LEFT JOIN user_details ud ON dm.user_id = ud.id
WHERE ud.status = 'active'";

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
