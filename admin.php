<?php
include 'connection.php';

$select_sql = "SELECT * FROM admin WHERE username = ('" . $_POST['username'] ." ') AND 
password=('" . $_POST['password'] . "')";
$result = $con->query($select_sql);

if ($result->num_rows > 0) {
    {
        while ($row[] =$result->fetch_assoc())
        {
            $questions = $row;
            $jsonData = json_encode($questions);
        }
        
        
        
        echo json_encode(array("status" => "True","message" => "user login Successfully","data" => $questions));
    }
} else {
    echo json_encode(array("status" => "False","message" => "user details don't match"));
}

$con->close();
?>