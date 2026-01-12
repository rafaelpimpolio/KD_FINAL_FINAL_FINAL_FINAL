<?php
$host = "localhost";
$dbname = "kd_sportswear";
$user = "root";
$pass = "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

$action = $_POST['action'] ?? '';

if ($action === 'fetch') {
    // Fetch single user for editing
    $id = $_POST['id'];
    $stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = ?");
    $stmt->execute([$id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    echo json_encode($user);
    exit;
}

if ($action === 'update') {
    $id = $_POST['id'];
    $username = $_POST['username'];
    $role = $_POST['role'];
    $status = $_POST['status'];
    $password = $_POST['password'];

    if(!empty($password)){
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE users SET username = ?, password = ?, role = ?, status = ? WHERE user_id = ?");
        $stmt->execute([$username, $hashed, $role, $status, $id]);
    } else {
        $stmt = $pdo->prepare("UPDATE users SET username = ?, role = ?, status = ? WHERE user_id = ?");
        $stmt->execute([$username, $role, $status, $id]);
    }
    
    // After update, return the updated table
    $action = 'reload_table';
}

if ($action === 'delete') {
    $id = $_POST['id'];
    $stmt = $pdo->prepare("DELETE FROM users WHERE user_id = ?");
    $stmt->execute([$id]);

    // After delete, return the updated table
    $action = 'reload_table';
}

if ($action === 'reload_table') {
    $users = $pdo->query("SELECT * FROM users")->fetchAll(PDO::FETCH_ASSOC);
    ?>
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Role</th>
                <th>Status</th>
                <th>Date Created</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($users as $u): ?>
            <tr>
                <td><?= $u['user_id'] ?></td>
                <td><?= htmlspecialchars($u['username']) ?></td>
                <td><?= htmlspecialchars($u['role']) ?></td>
                <td><?= htmlspecialchars($u['status']) ?></td>
                <td><?= $u['date_created'] ?></td>
                <td>
                    <button class="btn btn-sm btn-primary editUserBtn" data-id="<?= $u['user_id'] ?>">Edit</button>
                    <button class="btn btn-sm btn-danger" onclick="deleteUser(<?= $u['user_id'] ?>)">Delete</button>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php
    exit;
}
