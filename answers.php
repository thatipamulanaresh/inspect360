<?php
include 'connection.php';

// Get and sanitize user_id
$user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : null;

// Build SQL query
$sql = "
    SELECT 
        DISTINCT ii.name AS Mill_name,
        iq.question,
        ia.answer,
        ia.user_id,
        SUM(
            IF(
                (ud.role = 'RDDM' AND ias.rddm_status = 'completed') OR 
                (ud.role = 'RJDM' AND ias.rjdm_status = 'completed') OR 
                (ud.role = 'DMO' AND ias.assign_status = 'completed'), 
                1, 
                0
            )
        ) AS completed
    FROM industry_information ii 
    LEFT JOIN inspection_questions iq 
        ON iq.id = ii.id
    LEFT JOIN inspection_answers ia 
        ON ia.q_id = iq.id
    LEFT JOIN inspection_assign ias 
        ON ias.user_id = ia.assign_id
    LEFT JOIN user_details ud 
        ON ud.id = ia.user_id
    WHERE 1=1";

if ($user_id) {
    $sql .= " AND ia.user_id = ?";
}

$sql .= " GROUP BY ii.name, iq.question, ia.answer, ia.user_id";

// Prepare and execute
$stmt = $con->prepare($sql);
if (!$stmt) {
    echo json_encode([
        'success' => false,
        'message' => 'Query preparation failed: ' . $con->error
    ]);
    exit;
}

if ($user_id) {
    $stmt->bind_param("i", $user_id);
}

$stmt->execute();
$result = $stmt->get_result();

// Fetch results
$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

// Return response
echo json_encode([
    'success' => count($data) > 0,
    'message' => count($data) > 0 ? 'Data retrieved successfully' : 'No results found',
    'data' => $data
]);

// Cleanup
$stmt->close();
$con->close();
?>