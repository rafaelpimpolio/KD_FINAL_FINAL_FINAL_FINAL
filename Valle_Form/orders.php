<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>KD Sportswear & Apparel - Orders</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="../account/nav.css">
<link rel="stylesheet" href="../Magan_Form/inquiry_style.css">
</head>
<body>

<!-- Navigation -->
<nav class="navbar" id="navbar">
    <div class="container">
        <a href="#home" class="navbar-brand">KD Sportswear & Apparel</a>
        <ul class="navbar-menu" id="navbarMenu">
            <li><a href="../Magan_Form/employee_to_inquiry_order.php" class="nav-link">Inquiry List</a></li>
            <li><a href="orders.php" class="nav-link">Order List</a></li>
            <li><a href="../Duro_Form/payment-form.html" class="nav-link">Payment</a></li>
            <li><a href="../account/account.php" class="nav-link">Account</a></li>
        </ul>
        <button class="navbar-toggle" id="navbarToggle" aria-label="Toggle navigation">
            <span id="menuIcon">☰</span>
        </button>
    </div>
</nav>

<div class="container mt-5">
    <h2 class="mb-4 text-center">Orders List</h2>
    <div class="table-responsive table-container">
        <table class="table table-striped table-bordered" id="ordersTable">
            <thead class="table-dark">
                <tr>
                    <th>Order ID</th>
                    <th>Inquiry ID</th>
                    <th>Customer ID</th>
                    <th>Order Details</th>
                    <th>Material</th>
                    <th>Colors</th>
                    <th>Comment</th>
                    <th>Total Amount</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>File</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="ordersTableBody">
                <tr><td colspan="12" class="text-center">Loading orders...</td></tr>
            </tbody>
        </table>
    </div>
</div>

<!-- Edit Order Modal -->
<div class="modal fade" id="editOrderModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form id="editOrderForm">
        <div class="modal-header">
          <h5 class="modal-title">Edit Order</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
            <input type="hidden" name="order_id" id="edit_order_id">

            <div class="mb-3">
                <label for="edit_total_amount" class="form-label">Total Amount</label>
                <input type="number" step="0.01" name="total_amount" id="edit_total_amount" class="form-control">
            </div>

            <div class="mb-3">
                <label for="edit_order_status" class="form-label">Status</label>
                <select name="status" id="edit_order_status" class="form-control">
                    <option value="PENDING">PENDING</option>
                    <option value="PROCESSING">PROCESSING</option>
                    <option value="READY FOR PICKUP">READY FOR PICKUP</option>
                    <option value="COMPLETED">COMPLETED</option>
                </select>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Save Changes</button>
        </div>
      </form>
    </div>
  </div>
</div>

    <!-- Footer -->
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
function loadOrders(){
    $.get('order_crud.php',{action:'read'},function(res){
        $('#ordersTableBody').html(res);
    });
}

function openEditOrderModal(row){
    $('#edit_order_id').val(row.order_id);
    $('#edit_total_amount').val(row.total_amount);
    $('#edit_order_status').val(row.status);
    new bootstrap.Modal(document.getElementById('editOrderModal')).show();
}

function deleteOrder(id){
    if(!confirm('Are you sure you want to delete this order?')) return;
    $.post('order_crud.php',{action:'delete',order_id:id},function(res){
        alert(res);
        loadOrders();
    });
}

$(document).ready(function(){
    loadOrders();

    $('#editOrderForm').submit(function(e){
        e.preventDefault();
        $.post('order_crud.php',$(this).serialize(),function(res){
            alert(res);
            loadOrders();
            bootstrap.Modal.getInstance(document.getElementById('editOrderModal')).hide();
        });
    });
});
</script>
</body>
</html>
