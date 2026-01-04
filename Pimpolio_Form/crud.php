<?php
require "connect.php";

$pdo = Database::Connection();

if ($_POST['func_name'] === "CreateCustomerAccount") {
    echo json_encode(CreateCustomerAccount($pdo));
    exit;
}

function CreateCustomerAccount($pdo)
{
    try {
        $pdo->beginTransaction();

        $username = trim($_POST['username']);
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        // Check username
        $check = $pdo->prepare("SELECT user_id FROM users WHERE username = ?");
        $check->execute([$username]);
        if ($check->rowCount() > 0) {
            return ["success" => false, "message" => "Username already exists"];
        }

        // Insert user
        $stmtUser = $pdo->prepare(
            "INSERT INTO users (username, password_hash) VALUES (?, ?)"
        );
        $stmtUser->execute([$username, $password]);

        $user_id = $pdo->lastInsertId();

        // Insert customer
        $stmtCustomer = $pdo->prepare("
            INSERT INTO customer
            (user_id, first_name, last_name, phone_number, email, barangay, city_municipality, province, postal_code)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

        $stmtCustomer->execute([
            $user_id,
            $_POST['first_name'],
            $_POST['last_name'],
            $_POST['phone_number'],
            $_POST['email'],
            $_POST['barangay'],
            $_POST['city_municipality'],
            $_POST['province'],
            $_POST['postal_code']
        ]);

        $pdo->commit();
        return ["success" => true];

    } catch (Throwable $e) {
        $pdo->rollBack();
        return ["success" => false, "message" => $e->getMessage()];
    }
}
