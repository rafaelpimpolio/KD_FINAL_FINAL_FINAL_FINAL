<?php
date_default_timezone_set('Asia/Manila');

$host = 'localhost';
$username = 'root';
$password = '';
$database = 'kd_sportswear';

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
