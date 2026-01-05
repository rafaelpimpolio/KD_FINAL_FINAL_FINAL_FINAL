<?php
session_start();
require_once "login_signup_connect.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);
    $role     = trim($_POST["role"]);

    if ($username === "" || $password === "" || $role === "") {
        die("All fields are required.");
    }

    $stmt = $conn->prepare("
        SELECT 
            u.user_id,
            u.username,
            u.password_hash,
            u.role,
            c.customer_id
        FROM users u
        LEFT JOIN customer c ON c.user_id = u.user_id
        WHERE u.username = :username
          AND u.role = :role
        LIMIT 1
    ");

    /* ✅ THIS WAS MISSING */
    $stmt->execute([
        ":username" => $username,
        ":role" => $role
    ]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        die("Invalid username or role.");
    }

    if (!password_verify($password, $user["password_hash"])) {
        die("Invalid password.");
    }

    $_SESSION["username"] = $user["username"];
    $_SESSION["role"]     = $user["role"];
    $_SESSION["user_id"]  = $user["user_id"];

    if ($role === "customer") {
        if (empty($user["customer_id"])) {
            die("Customer profile not found. Please contact admin.");
        }
        $_SESSION["customer_id"] = $user["customer_id"];
        header("Location: Balatan_Form/inquiry.html");
        exit;
    }

    if ($role === "admin") {
        header("Location: Magan_Form/employee_to_inquiry_order.php");
        exit;
    }
}
?>
