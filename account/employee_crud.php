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

if ($action === 'create') {
    $username = trim($_POST['username']);

    // Check for duplicate username
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username=?");
    $stmt->execute([$username]);
    if ($stmt->fetchColumn() > 0) {
        die("<div class='alert alert-danger'>Username already exists!</div>");
    }

    // Insert user
    $stmt = $pdo->prepare("INSERT INTO users (username, password_hash, role) VALUES (?, ?, 'Employee')");
    $stmt->execute([
        $username,
        password_hash($_POST['password'], PASSWORD_DEFAULT)
    ]);
    $user_id = $pdo->lastInsertId();

    // Insert employee (role/position is fixed)
    $stmt = $pdo->prepare("INSERT INTO employee 
        (user_id, first_name, middle_initial, last_name, position)
        VALUES (?, ?, ?, ?, 'Employee')");
    $stmt->execute([
        $user_id,
        $_POST['first_name'],
        $_POST['middle_initial'],
        $_POST['last_name']
    ]);

} elseif($action === 'fetch') {
    $id = $_POST['id'];
    $stmt = $pdo->prepare("
        SELECT employee.*, users.username
        FROM employee
        JOIN users ON employee.user_id = users.user_id
        WHERE employee_id=?
    ");
    $stmt->execute([$id]);
    echo json_encode($stmt->fetch(PDO::FETCH_ASSOC));
    exit;
}elseif ($action === 'update') {
    $id = $_POST['id'];

    // Update employee
    $stmt = $pdo->prepare("
        UPDATE employee SET
        first_name=?, middle_initial=?, last_name=?
        WHERE employee_id=?
    ");
    $stmt->execute([
        $_POST['first_name'],
        $_POST['middle_initial'],
        $_POST['last_name'],
        $id
    ]);

    // Update username
    $stmt = $pdo->prepare("
        UPDATE users
        JOIN employee ON users.user_id = employee.user_id
        SET users.username = ?
        WHERE employee.employee_id = ?
    ");
    $stmt->execute([ $_POST['username'], $id ]);

    // Update password only if filled
    if (!empty($_POST['password'])) {
        $stmt = $pdo->prepare("
            UPDATE users
            JOIN employee ON users.user_id = employee.user_id
            SET users.password_hash = ?
            WHERE employee.employee_id = ?
        ");
        $stmt->execute([ password_hash($_POST['password'], PASSWORD_DEFAULT), $id ]);
    }
} elseif ($action === 'delete') {
    $id = $_POST['id'];
    $stmt = $pdo->prepare("DELETE FROM employee WHERE employee_id=?");
    $stmt->execute([$id]);
}

// Return updated table
$employees = $pdo->query("
    SELECT employee.*, users.username 
    FROM employee 
    JOIN users ON employee.user_id = users.user_id
")->fetchAll(PDO::FETCH_ASSOC);
?>

<table class="table table-bordered table-striped">
<thead class="table-dark">
<tr>
<th>ID</th><th>Username</th><th>First Name</th><th>Middle Initial</th><th>Last Name</th><th>Position</th><th>Actions</th>
</tr>
</thead>
<tbody>
<?php foreach($employees as $e): ?>
<tr>
<td><?= $e['employee_id'] ?></td>
<td><?= htmlspecialchars($e['username']) ?></td>
<td><?= htmlspecialchars($e['first_name']) ?></td>
<td><?= htmlspecialchars($e['middle_initial']) ?></td>
<td><?= htmlspecialchars($e['last_name']) ?></td>
<td><?= htmlspecialchars($e['position']) ?></td>
<td>
<button class="btn btn-sm btn-primary editEmployeeBtn" data-id="<?= $e['employee_id'] ?>">Edit</button>
<button class="btn btn-sm btn-danger" onclick="deleteEmployee(<?= $e['employee_id'] ?>)">Delete</button>
</td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
