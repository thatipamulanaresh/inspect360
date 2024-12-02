<?php
// Include the database connection and response function
include 'connection.php';
include 'response.php';

// SQL query to fetch data
$select_sql = "SELECT * FROM inspection_questions";

try {
    $result = $con->query($select_sql);

    if ($result === false) {
        // Handle query execution error
        throw new Exception("Query execution failed: " . $con->error);
    }

    // Check if rows are returned
    if ($result->num_rows > 0) {
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        response("Data Found", $data, "true");
    } else {
        response("No Data Found", null, "false");
    }
} catch (Exception $e) {
    response($e->getMessage(), null, "Error");
} finally {
    // Close the database connection
    $con->close();
}
?>
