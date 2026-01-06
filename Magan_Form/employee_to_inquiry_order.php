<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>KD Sportswear & Apparel - Inquiries</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="inquiry_style.css">
<link rel="stylesheet" href="../account/nav.css">

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
            </ul>
            <button class="navbar-toggle" id="navbarToggle" aria-label="Toggle navigation">
                <span id="menuIcon">☰</span>
            </button>
        </div>
    </nav>

<div class="container mt-5">
    <h2 class="mb-4 text-center">Customer Inquiries</h2>
    <div class="table-responsive table-container">
        <table class="table table-striped table-bordered" id="inquiryTable">
            <thead class="table-dark">
                <tr>
                    <th>Inquiry ID</th>
                    <th>Customer ID</th>
                    <th>Order Details</th>
                    <th>Material</th>
                    <th>Colors</th>
                    <th>Comment</th>
                    <th>Initial Price</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>File</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="inquiryTableBody">
                <tr><td colspan="11" class="text-center">Loading inquiries...</td></tr>
            </tbody>
        </table>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form id="editForm" enctype="multipart/form-data">
        <div class="modal-header">
          <h5 class="modal-title">Edit Inquiry</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="inquiry_id" id="edit_inquiry_id">

          <div class="mb-3">
            <label for="edit_initial_price" class="form-label">Initial Price</label>
            <input type="number" step="0.01" name="initial_price" id="edit_initial_price" class="form-control">
          </div>

          <div class="mb-3">
            <label for="edit_status" class="form-label">Status</label>
            <select name="status" id="edit_status" class="form-control">
              <option value="PENDING">PENDING</option>
              <option value="APPROVED">APPROVED</option>
              <option value="DISAPPROVED">DISAPPROVED</option>
            </select>
          </div>

          <div class="mb-3">
            <label for="edit_customer_comment" class="form-label">Customer Comment</label>
            <textarea name="customer_comment" id="edit_customer_comment" class="form-control"></textarea>
          </div>

          <div class="mb-3">
            <label for="edit_customer_file" class="form-label">File Upload</label>
            <input type="file" name="customer_file" id="edit_customer_file" class="form-control">
            <small id="currentFile" class="form-text text-muted"></small>
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
// Load inquiries
function loadInquiries() {
    $.get('inquiry_crud.php', {action:'read'}, function(res){
        $('#inquiryTableBody').html(res);
    });
}

// Populate edit modal
function openEditModal(row) {
    $('#edit_inquiry_id').val(row.inquiry_id);
    $('#edit_initial_price').val(row.initial_price);
    $('#edit_status').val(row.status);
    $('#edit_customer_comment').val(row.customer_comment);
    $('#currentFile').text(row.customer_file || 'No file uploaded');
    var modal = new bootstrap.Modal(document.getElementById('editModal'));
    modal.show();
}

// Delete inquiry
function deleteInquiry(id) {
    if(!confirm('Are you sure you want to delete this inquiry?')) return;
    $.post('inquiry_crud.php', {action:'delete', inquiry_id:id}, function(res){
        loadInquiries();
        alert(res);
    });
}

$(document).ready(function(){
    loadInquiries();

    $('#editForm').submit(function(e){
        e.preventDefault();
        var formData = new FormData(this);
        formData.append('action','update');
        $.ajax({
            url:'inquiry_crud.php',
            type:'POST',
            data: formData,
            processData:false,
            contentType:false,
            success:function(res){
                alert(res);
                loadInquiries();
                bootstrap.Modal.getInstance(document.getElementById('editModal')).hide();
            }
        });
    });
});
</script>
</body>
</html>
