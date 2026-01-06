<?php
session_start();
$host = 'localhost';
$user = 'root';
$pass = '';
$db   = 'kd_sportswear';
$conn = new mysqli($host, $user, $pass, $db);
if($conn->connect_error) die("Connection failed: ".$conn->connect_error);

$action = $_POST['action'] ?? '';

if($action === 'create_from_inquiry'){
    $inquiry_id = intval($_POST['inquiry_id']);
    $customer_id = $_SESSION['customer_id'] ?? 0;

    // Make sure the inquiry belongs to the customer and is APPROVED
    $check = $conn->query("SELECT * FROM inquiry WHERE inquiry_id=$inquiry_id AND customer_id=$customer_id AND status='APPROVED'");
    if($check->num_rows == 0){
        echo "Inquiry is not approved or does not belong to you.";
        exit;
    }

    $row = $check->fetch_assoc();
    $total_amount = $row['initial_price'];

    // Insert into orders
    $sql = "INSERT INTO orders (inquiry_id, employee_id, total_amount, status, created_at)
            VALUES ($inquiry_id, NULL, '$total_amount', 'PENDING', NOW())";

    if($conn->query($sql)){
        // Optionally, mark inquiry as ORDERED
        $conn->query("UPDATE inquiry SET status='ORDERED' WHERE inquiry_id=$inquiry_id");
        echo "Inquiry converted to order successfully!";
    } else {
        echo "Error: ".$conn->error;
    }
}
?>
