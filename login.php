<?php
session_start();
require_once "login_signup_connect.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);
    $role     = trim($_POST["role"]);

    if ($username === "" || $password === "" || $role === "") {
        $error = "All fields are required.";
    } else {
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

        $stmt->execute([
            ":username" => $username,
            ":role" => $role
        ]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            $error = "Invalid username or role.";
        } elseif (!password_verify($password, $user["password_hash"])) {
            $error = "Invalid password.";
        } else {
            $_SESSION["username"] = $user["username"];
            $_SESSION["role"]     = $user["role"];
            $_SESSION["user_id"]  = $user["user_id"];

            if ($role === "customer") {
                if (empty($user["customer_id"])) {
                    $error = "Customer profile not found. Please contact admin.";
                } else {
                    $_SESSION["customer_id"] = $user["customer_id"];
                    echo json_encode(["success" => true, "redirect" => "Balatan_Form/inquiry.html"]);
                    exit;
                }
            } elseif ($role === "admin") {
                echo json_encode(["success" => true, "redirect" => "Magan_Form/employee_to_inquiry_order.php"]);
                exit;
            }
        }
    }

    // Return error as JSON
    echo json_encode(["success" => false, "error" => $error]);
}
