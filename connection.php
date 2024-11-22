<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, DELETE, PUT, PATCH, OPTIONS');
header('Access-Control-Allow-Headers: token, Content-Type');
header('Access-Control-Max-Age: 1728000');
header('Content-Length: 0');
header('Content-Type: application/json');

$servername = "localhost";
$username = "root";
//$password = "TeamUp!306";
$password = "";
$db="inspect360"; 
$con = new mysqli($servername, $username, $password, $db);
if($con->connect_error)
{
    die("connection failed");
}