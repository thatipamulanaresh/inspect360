<?php
include 'connection.php';

// Get the role from POST request
$role = isset($_POST['role']) ? $con->real_escape_string($_POST['role']) : null;

// Build the SQL query with optional role filtering
$sql = "
    SELECT 
        ud.name, 
        ud.role, 
        ud.mobile_number, 
        d.dist_name, 
        COUNT(ia.dist_id) AS assign, 
        SUM(
            IF(
                (ud.role = 'RDDM' AND ia.rddm_status = 'completed') OR 
                (ud.role = 'RJDM' AND ia.rjdm_status = 'completed') OR 
                (ud.role = 'DMO' AND assign_status = 'completed'), 
                1, 
                0
            )
        ) AS completed
    FROM 
        user_details ud
    LEFT JOIN 
        district_user_map dum ON ud.id = dum.user_id
    LEFT JOIN 
        districts d ON dum.dist_id = d.dist_code
    LEFT JOIN 
        inspection_assign ia ON ia.dist_id = d.dist_code WHERE 
        ud.status != 'deleted'";

// Append WHERE clause if a role is specified
if ($role) {
    $sql .= " AND WHERE ud.role = '$role'";
}

$sql .= " GROUP BY ud.name, ud.role, ud.mobile_number, d.dist_name";

$result = $con->query($sql);

if ($result) {
    if ($result->num_rows > 0) {
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        // Success response
        echo json_encode([
            'success' => true,
            'message' => 'Data retrieved successfully',
            'data' => $data
        ]);
    } else {
        // No results found
        echo json_encode([
            'success' => false,
            'message' => 'No results found',
            'data' => []
        ]);
    }
} else {
    // Query error
    echo json_encode([
        'success' => false,
        'message' => 'Error executing query: ' . $con->error
    ]);
}

$con->close();
?>
