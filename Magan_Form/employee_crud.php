<?php
$host = 'localhost';
$db = 'kd_sportswear';
$user = 'root';
$pass = '';

$pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
$action = $_GET['action'] ?? $_POST['action'] ?? '';
header('Content-Type: application/json');

// ------------------ READ INQUIRIES ------------------
if($action==='read_inquiry'){
    $stmt = $pdo->query("SELECT * FROM inquiry ORDER BY inquiry_id DESC");
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    exit;
}

// ------------------ READ ORDERS ------------------
if($action==='read_orders'){
    $stmt = $pdo->query("SELECT * FROM orders ORDER BY order_id DESC");
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    exit;
}

// ------------------ UPDATE INQUIRY ------------------
if($action==='update_inquiry'){
    $id = $_POST['inquiry_id'];
    $status = $_POST['status'];
    $price = $_POST['initial_price'];
    $stmt = $pdo->prepare("UPDATE inquiry SET status=?, initial_price=? WHERE inquiry_id=?");
    $stmt->execute([$status, $price, $id]);
    echo json_encode(['success'=>true,'message'=>'Inquiry updated successfully']);
    exit;
}

// ------------------ DELETE INQUIRY ------------------
if($action==='delete_inquiry'){
    $id = $_GET['id'];
    $stmt = $pdo->prepare("DELETE FROM inquiry WHERE inquiry_id=?");
    $stmt->execute([$id]);
    echo json_encode(['success'=>true,'message'=>'Inquiry deleted']);
    exit;
}

// ------------------ DELETE ORDER ------------------
if($action==='delete_order'){
    $id = $_GET['id'];
    $stmt = $pdo->prepare("DELETE FROM orders WHERE order_id=?");
    $stmt->execute([$id]);
    echo json_encode(['success'=>true,'message'=>'Order deleted']);
    exit;
}
?>
