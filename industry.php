<?php
include 'connection.php';

$select_sql = "SELECT * FROM industry_information WHERE type_id = ('" . $_POST['type_id'] ." ') AND 
district=('" . $_POST['district'] . "')";
$result = $con->query($select_sql);

if ($result->num_rows > 0) {
    {
        while ($row[] =$result->fetch_assoc())
        {
            $questions = $row;
            $jsonData = json_encode($questions);
        }
        
        
        
        echo json_encode(array("status" => "True","message" => "Data Found","data" => $questions));
    }
} else {
    echo json_encode(array("status" => "False","message" => "No Data Found"));
}

$con->close();
?>