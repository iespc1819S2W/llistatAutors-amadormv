<?php

$host = "localhost";
$user = "root";
$pass = "amador99";
$db = "biblioteca";

$mysqli = new mysqli();
$mysqli->connect($host, $user, $pass, $db);
$mysqli->set_charset("utf8mb4");
if (!$mysqli) { 
    echo "Connection error: ". mysqli_connect_error(); 
}

?>