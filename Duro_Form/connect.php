<?php
// Set default timezone
date_default_timezone_set('Asia/Manila');

// Database connection configuration
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'kd_sportswear';

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Optional: set charset for proper UTF-8 support
$conn->set_charset("utf8mb4");

// Log file path (can be used by payment_crud.php or other scripts)
$logFile = __DIR__ . '/payment_log.txt';

// Function to write to log file
function writeLog($action, $payment_id, $transaction_id, $payment_reference, $details = '') {
    global $logFile;
    $timestamp = date('Y-m-d H:i:s');
    $logEntry = "[$timestamp] Action: $action | Payment ID: $payment_id | Transaction ID: $transaction_id | Reference: $payment_reference | $details\n";
    file_put_contents($logFile, $logEntry, FILE_APPEND);
}
?>
