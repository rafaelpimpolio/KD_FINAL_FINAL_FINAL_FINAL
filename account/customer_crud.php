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
    $stmt = $pdo->prepare("INSERT INTO users (username, password_hash) VALUES (?, ?)");
    $stmt->execute([
        $username,
        password_hash($_POST['password'], PASSWORD_DEFAULT)
    ]);
    $user_id = $pdo->lastInsertId();

    // Insert customer
    $stmt = $pdo->prepare("INSERT INTO customer 
        (user_id, first_name, last_name, phone_number, email, barangay, city_municipality, province, postal_code)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([
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

} elseif($action === 'fetch') {
    $id = $_POST['id'];
    $stmt = $pdo->prepare("
        SELECT customer.*, users.username
        FROM customer
        JOIN users ON customer.user_id = users.user_id
        WHERE customer_id=?
    ");
    $stmt->execute([$id]);
    echo json_encode($stmt->fetch(PDO::FETCH_ASSOC));
    exit;
} elseif ($action === 'delete') {
    $id = $_POST['id'];

    // Deleting customer will automatically delete user due to foreign key cascade
    $stmt = $pdo->prepare("DELETE FROM customer WHERE customer_id=?");
    $stmt->execute([$id]);
}

// Return updated table
$customers = $pdo->query("
    SELECT customer.*, users.username 
    FROM customer 
    JOIN users ON customer.user_id = users.user_id
")->fetchAll(PDO::FETCH_ASSOC);
?>

<table class="table table-bordered table-striped">
<thead class="table-dark">
<tr>
<th>ID</th><th>Username</th><th>First Name</th><th>Last Name</th><th>Phone</th><th>Email</th><th>Address</th><th>Actions</th>
</tr>
</thead>
<tbody>
<?php foreach($customers as $c): ?>
<tr>
<td><?= $c['customer_id'] ?></td>
<td><?= htmlspecialchars($c['username']) ?></td>
<td><?= htmlspecialchars($c['first_name']) ?></td>
<td><?= htmlspecialchars($c['last_name']) ?></td>
<td><?= htmlspecialchars($c['phone_number']) ?></td>
<td><?= htmlspecialchars($c['email']) ?></td>
<td><?= htmlspecialchars($c['barangay'].', '.$c['city_municipality'].', '.$c['province'].' '.$c['postal_code']) ?></td>
<td>
<button class="btn btn-sm btn-primary editCustomerBtn" data-id="<?= $c['customer_id'] ?>">Edit</button>
<button class="btn btn-sm btn-danger" onclick="deleteCustomer(<?= $c['customer_id'] ?>)">Delete</button>
</td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
