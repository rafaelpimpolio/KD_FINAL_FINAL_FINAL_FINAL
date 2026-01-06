<?php
// account.php

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
$employees = $pdo->query("SELECT * FROM employee")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Account Management</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
</head>
<body>
<div class="container mt-5">
    <h2>Admin Account Management</h2>

    <ul class="nav nav-tabs" id="accountTabs" role="tablist">
        <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#customer">Customer Accounts</button></li>
        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#employee">Employee Accounts</button></li>
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
                            <th>ID</th><th>First Name</th><th>Middle Initial</th><th>Last Name</th><th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($employees as $e): ?>
                        <tr>
                            <td><?= $e['employee_id'] ?></td>
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
    </div>
</div>

<!-- Customer Modal -->
<div class="modal fade" id="createCustomerModal">
  <div class="modal-dialog">
    <form id="customerForm" class="modal-content" novalidate>
      <div class="modal-header"><h5 class="modal-title">Create Customer</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
      <div class="modal-body">
        <input type="hidden" name="action" value="create">
        <div class="mb-3"><input type="text" name="first_name" class="form-control" placeholder="First Name" required></div>
        <div class="mb-3"><input type="text" name="last_name" class="form-control" placeholder="Last Name" required></div>
        <div class="mb-3"><input type="text" name="phone_number" class="form-control" placeholder="Phone" pattern="\d{10,15}"></div>
        <div class="mb-3"><input type="email" name="email" class="form-control" placeholder="Email"></div>
        <div class="mb-3"><input type="text" name="barangay" class="form-control" placeholder="Barangay"></div>
        <div class="mb-3"><input type="text" name="city_municipality" class="form-control" placeholder="City/Municipality"></div>
        <div class="mb-3"><input type="text" name="province" class="form-control" placeholder="Province"></div>
        <div class="mb-3"><input type="text" name="postal_code" class="form-control" placeholder="Postal Code"></div>
        <div class="mb-3"><input type="text" name="username" class="form-control" placeholder="Username" required></div>
        <div class="mb-3"><input type="password" name="password" class="form-control" placeholder="Password" required></div>
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
    <form id="employeeForm" class="modal-content" novalidate>
      <div class="modal-header"><h5 class="modal-title">Create Employee</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
      <div class="modal-body">
        <input type="hidden" name="action" value="create">
        <div class="mb-3"><input type="text" name="first_name" class="form-control" placeholder="First Name" required></div>
        <div class="mb-3"><input type="text" name="middle_initial" class="form-control" placeholder="Middle Initial"></div>
        <div class="mb-3"><input type="text" name="last_name" class="form-control" placeholder="Last Name" required></div>
        <div class="mb-3"><input type="text" name="username" class="form-control" placeholder="Username" required></div>
        <div class="mb-3"><input type="password" name="password" class="form-control" placeholder="Password" required></div>

      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-success">Save</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
      </div>
    </form>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
$('#customerForm, #employeeForm').on('submit', function(e){
    if(!this.checkValidity()){
        e.preventDefault();
        e.stopPropagation();
        this.classList.add('was-validated');
        return false;
    }

    e.preventDefault();
    let form = $(this);
    let url = form.attr('id') === 'customerForm' ? 'customer_crud.php' : 'employee_crud.php';

    $.post(url, form.serialize(), function(res){
        if(form.attr('id') === 'customerForm'){
            $('#customerTable').html(res);
            $('#createCustomerModal').modal('hide');
        } else {
            $('#employeeTable').html(res);
            $('#createEmployeeModal').modal('hide');
        }
        form[0].reset();
        form.removeClass('was-validated');
    });
});

// Edit Customer
$(document).on('click', '.editCustomerBtn', function(){
    let id = $(this).data('id');

    $.post('customer_crud.php', {action:'fetch', id:id}, function(res){
        let data = JSON.parse(res);

        let modal = $('#createCustomerModal');
        modal.find('input[name="action"]').val('update');
        modal.find('input[name="id"]').remove();
        modal.append('<input type="hidden" name="id" value="'+id+'">');

        modal.find('input[name="username"]').val(data.username);
        modal.find('input[name="password"]').val(''); // optional
        modal.find('input[name="first_name"]').val(data.first_name);
        modal.find('input[name="last_name"]').val(data.last_name);
        modal.find('input[name="phone_number"]').val(data.phone_number);
        modal.find('input[name="email"]').val(data.email);
        modal.find('input[name="barangay"]').val(data.barangay);
        modal.find('input[name="city_municipality"]').val(data.city_municipality);
        modal.find('input[name="province"]').val(data.province);
        modal.find('input[name="postal_code"]').val(data.postal_code);

        modal.modal('show');
    });
});

// Edit Employee
$(document).on('click', '.editEmployeeBtn', function(){
    let id = $(this).data('id');

    $.post('employee_crud.php', {action:'fetch', id:id}, function(res){
        let data = JSON.parse(res);

        let modal = $('#createEmployeeModal');
        modal.find('input[name="action"]').val('update');
        modal.find('input[name="id"]').remove();
        modal.append('<input type="hidden" name="id" value="'+id+'">');

        modal.find('input[name="username"]').val(data.username);
        modal.find('input[name="password"]').val(''); // optional
        modal.find('input[name="first_name"]').val(data.first_name);
        modal.find('input[name="middle_initial"]').val(data.middle_initial);
        modal.find('input[name="last_name"]').val(data.last_name);

        modal.modal('show');
    });
});

function deleteCustomer(id){
    if(confirm('Are you sure?')) $.post('customer_crud.php',{action:'delete',id:id},res=>$('#customerTable').html(res));
}
function deleteEmployee(id){
    if(confirm('Are you sure?')) $.post('employee_crud.php',{action:'delete',id:id},res=>$('#employeeTable').html(res));
}
</script>
</body>
</html>
