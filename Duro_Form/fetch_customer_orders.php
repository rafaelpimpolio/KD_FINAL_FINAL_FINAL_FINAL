<?php
require_once 'connect.php';

$customer_id = intval($_GET['customer_id'] ?? 0);

if ($customer_id === 0) {
    echo json_encode([]);
    exit;
}

$sql = "
    SELECT 
        o.order_id,
        o.inquiry_id,
        o.total_amount,
        o.status,
        o.created_at
    FROM orders o
    INNER JOIN inquiry i ON o.inquiry_id = i.inquiry_id
    WHERE i.customer_id = ?
      AND o.status = 'FOR PAYMENT'
    ORDER BY o.created_at DESC
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $customer_id);
$stmt->execute();

$result = $stmt->get_result();
$orders = [];

while ($row = $result->fetch_assoc()) {
    $orders[] = $row;
}

echo json_encode($orders);
