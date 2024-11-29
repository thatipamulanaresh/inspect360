<?php
include 'connection.php';



$type = $_POST['data_type'];


if($type == 'districts'){

    
$select_sql = "SELECT dist_code, dist_name FROM districts WHERE status != 'deleted'";

} else if($type == 'inspection_types'){
    
$select_sql = "SELECT id, inspection_type FROM inspection_types WHERE status != 'deleted' ";

}else if($type == 'user_roles'){
    
    $select_sql = "SELECT id, role, status FROM user_roles WHERE status != 'deleted' ";
 } else {
    echo json_encode(array(
        "status" => "False",
        "message" => "missing parameters"
    ));
}


$result = $con->query($select_sql);

if ($result->num_rows > 0) {
    $questions = array(); // Initialize an array to hold the data

    while ($row = $result->fetch_assoc()) {
        $questions[] = $row; 
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