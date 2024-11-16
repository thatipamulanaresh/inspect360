<?php

$servername = "165.22.209.57";
$username = "developer";
//$password = "TeamUp!306";
$password = "Dev_TeamUp1";
$db="inspect360"; 
$con = new mysqli($servername, $username, $password, $db);
if($con->connect_error)
{
    die("connection failed");
}