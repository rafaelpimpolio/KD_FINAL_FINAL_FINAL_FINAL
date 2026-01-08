<?php
require_once 'connect.php';

$q = isset($_GET['q']) ? trim($_GET['q']) : '';

if ($q !== '') {
    $sql = "SELECT customer_id, CONCAT(first_name, ' ', last_name) AS full_name
            FROM customer
            WHERE CONCAT(first_name, ' ', last_name) LIKE ?
            LIMIT 10";

    $stmt = $conn->prepare($sql);
    $like = "%$q%";
    $stmt->bind_param("s", $like);
    $stmt->execute();
    $result = $stmt->get_result();

    $customers = [];
    while ($row = $result->fetch_assoc()) {
        $customers[] = $row;
    }

    echo json_encode($customers);

    $stmt->close();
}

$conn->close();
?>
