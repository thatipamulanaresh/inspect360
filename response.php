<?php


function response($message, $data, $status) {
    echo json_encode(["status" => $status, "msg" => $message, "data" => $data]);
}


?>