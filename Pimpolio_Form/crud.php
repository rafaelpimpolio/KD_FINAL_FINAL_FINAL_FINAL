<?php
function CreateCustomerAccount($pdo)
{
    try {
        $pdo->beginTransaction();

        $username = trim($_POST['username']);
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];

        // Server-side password confirmation check
        if ($password !== $confirm_password) {
            return ["success" => false, "message" => "Passwords do not match"];
        }

        // Hash password
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        // Check if username already exists
        $check = $pdo->prepare("SELECT user_id FROM users WHERE username = ?");
        $check->execute([$username]);
        if ($check->rowCount() > 0) {
            return ["success" => false, "message" => "Username already exists"];
        }

        // Insert user into users table
        $stmtUser = $pdo->prepare(
            "INSERT INTO users (username, password_hash) VALUES (?, ?)"
        );
        $stmtUser->execute([$username, $password_hash]);

        $user_id = $pdo->lastInsertId();

        // Insert customer details
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
?>
