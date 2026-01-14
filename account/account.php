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

$customers = $pdo->query("SELECT * FROM customer")->fetchAll(PDO::FETCH_ASSOC);
$employees = $pdo->query("
    SELECT employee.*, users.username, users.user_id
    FROM employee
    JOIN users ON employee.user_id = users.user_id
")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Account Management</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="nav.css">
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar" id="navbar">
        <div class="container">
            <a href="#home" class="navbar-brand">KD Sportswear & Apparel</a>
            <ul class="navbar-menu" id="navbarMenu">
                     <li><a href="../Magan_Form/employee_to_inquiry_order.php" class="nav-link">Inquiry List</a></li>
                     <li><a href="../Valle_Form/orders.php" class="nav-link">Order List</a></li>
                     <li><a href="../Duro_Form/payment-form.html" class="nav-link">Payment</a></li>
                     <li><a href="../account/account.php" class="nav-link">Account</a></li>
                     <li><a href="#" class="nav-link logout-link" onclick="confirmLogout(event)">Logout</a></li>

            </ul>
            <button class="navbar-toggle" id="navbarToggle" aria-label="Toggle navigation">
                <span id="menuIcon">☰</span>
            </button>
        </div>
    </nav>

<div class="container mt-5">
    <h2>Admin Account Management</h2>

    <ul class="nav nav-tabs" id="accountTabs" role="tablist">
        <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#customer">Customer Accounts</button></li>
        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#employee">Employee Accounts</button></li>
        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#user">User Accounts</button></li>
    </ul>

    <div class="tab-content mt-3">
        <!-- Customer Tab -->
        <div class="tab-pane fade show active" id="customer">
            <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#createCustomerModal">+ Create Customer</button>
            <div id="customerTable">
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th><th>First Name</th><th>Last Name</th><th>Phone</th><th>Email</th><th>Address</th><th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($customers as $c): ?>
                        <tr>
                            <td><?= $c['customer_id'] ?></td>
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
            </div>
        </div>

        <!-- Employee Tab -->
        <div class="tab-pane fade" id="employee">
            <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#createEmployeeModal">+ Create Employee</button>
            <div id="employeeTable">
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th><th>Username</th><th>First Name</th><th>Middle Initial</th><th>Last Name</th><th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($employees as $e): ?>
                        <tr>
                            <td><?= $e['user_id'] ?></td>
                            <td><?= htmlspecialchars($e['username']) ?></td>
                            <td><?= htmlspecialchars($e['first_name']) ?></td>
                            <td><?= htmlspecialchars($e['middle_initial']) ?></td>
                            <td><?= htmlspecialchars($e['last_name']) ?></td>
                            <td>
                                <button class="btn btn-sm btn-primary editEmployeeBtn" data-id="<?= $e['employee_id'] ?>">Edit</button>
                                <button class="btn btn-sm btn-danger" onclick="deleteEmployee(<?= $e['employee_id'] ?>)">Delete</button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- User Tab -->
        <div class="tab-pane fade" id="user">
            <div id="userTable">
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
                        <?php
                        $users = $pdo->query("SELECT * FROM users")->fetchAll(PDO::FETCH_ASSOC);
                        foreach($users as $u):
                        ?>
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
            </div>
        </div>
    </div>
</div>

<!-- Customer Modal -->
<div class="modal fade" id="createCustomerModal">
  <div class="modal-dialog">
    <form id="customerForm" class="modal-content needs-validation" novalidate>
      <div class="modal-header">
        <h5 class="modal-title">Customer Account</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <input type="hidden" name="action" value="create">

        <div class="mb-3">
          <input type="text" name="first_name" class="form-control" placeholder="First Name" required>
          <div class="invalid-feedback">First name is required.</div>
        </div>

        <div class="mb-3">
          <input type="text" name="last_name" class="form-control" placeholder="Last Name" required>
          <div class="invalid-feedback">Last name is required.</div>
        </div>

        <div class="mb-3">
          <input type="text" name="phone_number" class="form-control" placeholder="Phone" pattern="\d{10,15}">
          <div class="invalid-feedback">Enter a valid phone number (10-15 digits).</div>
        </div>

        <div class="mb-3">
          <input type="email" name="email" class="form-control" placeholder="Email">
          <div class="invalid-feedback">Enter a valid email address.</div>
        </div>

        <div class="mb-3">
          <input type="text" name="barangay" class="form-control" placeholder="Barangay" required>
          <div class="invalid-feedback">Barangay is required.</div>
        </div>

        <div class="mb-3">
          <input type="text" name="city_municipality" class="form-control" placeholder="City/Municipality" required>
          <div class="invalid-feedback">City/Municipality is required.</div>
        </div>

        <div class="mb-3">
          <input type="text" name="province" class="form-control" placeholder="Province" required>
          <div class="invalid-feedback">Province is required.</div>
        </div>

        <div class="mb-3">
          <input type="text" name="postal_code" class="form-control" placeholder="Postal Code" required>
          <div class="invalid-feedback">Postal code is required.</div>
        </div>

        <div class="mb-3">
          <input type="text" name="username" class="form-control" placeholder="Username" required>
          <div class="invalid-feedback">Username is required.</div>
        </div>

        <div class="mb-3">
          <input type="password" name="password" class="form-control" placeholder="Password">
          <div class="invalid-feedback">Password is required for new accounts.</div>
        </div>

        <div class="mb-3">
            <input type="password" name="confirm_password" class="form-control" placeholder="Confirm Password">
        </div>

      </div>

      <div class="modal-footer">
        <button type="submit" class="btn btn-success">Save</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
      </div>
    </form>
  </div>
</div>

<!-- Employee Modal -->
<div class="modal fade" id="createEmployeeModal">
  <div class="modal-dialog">
    <form id="employeeForm" class="modal-content needs-validation" novalidate>
      <div class="modal-header">
        <h5 class="modal-title">Employee Account</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <input type="hidden" name="action" value="create">

        <div class="mb-3">
          <input type="text" name="first_name" class="form-control" placeholder="First Name" required>
          <div class="invalid-feedback">First name is required.</div>
        </div>

        <div class="mb-3">
          <input type="text" name="middle_initial" class="form-control" placeholder="Middle Initial" maxlength="1">
          <div class="invalid-feedback">Middle initial should be a single letter.</div>
        </div>

        <div class="mb-3">
          <input type="text" name="last_name" class="form-control" placeholder="Last Name" required>
          <div class="invalid-feedback">Last name is required.</div>
        </div>

        <div class="mb-3">
          <input type="text" name="username" class="form-control" placeholder="Username" required>
          <div class="invalid-feedback">Username is required.</div>
        </div>

        <div class="mb-3">
          <input type="password" name="password" class="form-control" placeholder="Password">
          <div class="invalid-feedback">Password is required for new accounts.</div>
        </div>
        
        <div class="mb-3">
            <input type="password" name="confirm_password" class="form-control" placeholder="Confirm Password">
        </div>

      </div>

      <div class="modal-footer">
        <button type="submit" class="btn btn-success">Save</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
      </div>
    </form>
  </div>
</div>

<!-- User Edit Modal -->
<div class="modal fade" id="editUserModal">
  <div class="modal-dialog">
    <form id="userForm" class="modal-content needs-validation" novalidate>
      <div class="modal-header">
        <h5 class="modal-title">Edit User</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" name="action" value="update">
        <input type="hidden" name="id">

        <div class="mb-3">
          <input type="text" name="username" class="form-control" placeholder="Username" required>
        </div>

        <div class="mb-3">
          <input type="password" name="password" class="form-control" placeholder="New Password (leave blank to keep)">
        </div>

        <div class="mb-3">
          <label>Role</label>
          <select name="role" class="form-select" required>
              <option value="customer">Customer</option>
              <option value="employee">Employee</option>
              <option value="admin">Admin</option>
          </select>
        </div>

        <div class="mb-3">
          <label>Status</label>
          <select name="status" class="form-select" required>
              <option value="active">Active</option>
              <option value="inactive">Inactive</option>
          </select>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-success">Save</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
      </div>
    </form>
  </div>
</div>

    <footer>
        <div class="container">
            <div class="footer-grid">
                <div class="footer-section">
                    <h3>KD Sportswear & Apparel</h3>
                    <p>Custom apparel design shop creating unique t-shirts and jerseys since 2023.</p>
                </div>

                <div class="footer-section">
                    <h4>Quick Links</h4>
                    <ul class="footer-links">
                        <li><a href="#home">Home</a></li>
                        <li><a href="#about">About Us</a></li>
                        <li><a href="#latest-designs">Latest Designs</a></li>
                        <li><a href="#gallery">Gallery</a></li>
                    </ul>
                </div>

                <div class="footer-section">
                    <h4>Services</h4>
                    <ul class="footer-links">
                        <li>Custom T-Shirts</li>
                        <li>Team Jerseys</li>
                        <li>Community Shirts</li>
                        <li>Special Orders</li>
                    </ul>
                </div>

                <div class="footer-section">
                    <h4>Contact</h4>
                    <ul class="footer-links">
                        <li>J. Hernandez Avenue,</li>
                        <li>Sta. Cruz, Naga City</li>
                        <li style="margin-top: 0.5rem;">0998 976 5742</li>
                        <li style="display: flex; align-items: center; gap: 0.5rem;">
                        kdsportswear2023@gmail.com
                        </li>
                    </ul>
                </div>
            </div>

            <div class="footer-bottom">
                <p style="color: rgba(255, 255, 255, 0.8); font-size: 0.875rem; margin: 0;">© 2025 KD Sportswear & Apparel. All rights reserved.</p>
                <div class="footer-social">
                    <a href="https://www.facebook.com/kdsportswearandapparel" target="_blank" rel="noopener noreferrer" aria-label="Facebook"><img src="" alt=""></a>
                    <a href="https://instagram.com" target="_blank" rel="noopener noreferrer" aria-label="Instagram"><img src="" alt=""></a>
                </div>
            </div>
        </div>
    </footer>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src=js/script.js></script>
<script>
    // Logout confirmation
    function confirmLogout(event) {
        event.preventDefault();
        if(confirm('Are you sure you want to logout?')) {
            window.location.href = '../login_signup.html';
        }
    }
    </script>

</body>
</html>
