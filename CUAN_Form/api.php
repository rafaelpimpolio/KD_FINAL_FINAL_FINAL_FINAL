<?php
include 'config.php';
header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];

// 1. READ: Fetch all users
if ($method === 'GET') {
    $result = $conn->query("SELECT * FROM user ORDER BY user_id DESC");
    echo json_encode($result->fetch_all(MYSQLI_ASSOC));
} 

// 2. CREATE or UPDATE
if ($method === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $username = $conn->real_escape_string($data['username']);
    $phone = $conn->real_escape_string($data['phone']);
    $role = $conn->real_escape_string($data['role']);
    $id = isset($data['id']) ? $data['id'] : null;

    if ($id) {
        // UPDATE existing user in phpMyAdmin
        $sql = "UPDATE user SET username='$username', phone_number='$phone', role='$role' WHERE user_id=$id";
    } else {
        // CREATE new user
        $pass = password_hash($data['password'], PASSWORD_BCRYPT);
        $sql = "INSERT INTO user (username, password_hash, phone_number, role, status) VALUES ('$username', '$pass', '$phone', '$role', 'active')";
    }

    if ($conn->query($sql)) echo json_encode(["success" => true]);
    else echo json_encode(["success" => false, "error" => $conn->error]);
}

// 3. DELETE: Remove from phpMyAdmin
if ($method === 'DELETE') {
    $id = $_GET['id'];
    $conn->query("DELETE FROM user WHERE user_id=$id");
    echo json_encode(["success" => true]);
}
?>