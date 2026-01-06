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
        <li><a href="../home.html#home" class="nav-link">Home</a></li>
        <li><a href="../home.html#about" class="nav-link">About</a></li>
        <li><a href="../home.html#latest-designs" class="nav-link">Latest Designs</a></li>
        <li><a href="../home.html#gallery" class="nav-link">Gallery</a></li>
        <li><a href="../home.html#services" class="nav-link">Services</a></li>
        <li><a href="../home.html#testimonials" class="nav-link">Testimonials</a></li>
        <li><a href="../home.html#contact" class="nav-link">Contact</a></li>
        <li><a href="../Balatan_Form/inquiry.html" class="nav-link">Make Inquiry</a></li>
        <li><a href="../login_signup.html" class="nav-link">Log out</a></li>
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
                    <th>ID</th>
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
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <form id="editForm" enctype="multipart/form-data">
        <div class="modal-header">
          <h5 class="modal-title">Edit Inquiry</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="inquiry_id" id="edit_inquiry_id">

          <!-- Customer Comment -->
          <div class="mb-3">
            <label for="edit_customer_comment" class="form-label">Customer Comment</label>
            <textarea name="customer_comment" id="edit_customer_comment" class="form-control"></textarea>
          </div>

          <!-- File Upload -->
          <div class="mb-3">
            <label for="edit_customer_file" class="form-label">File Upload</label>
            <input type="file" name="customer_file" id="edit_customer_file" class="form-control">
            <small id="currentFile" class="form-text text-muted"></small>
          </div>

          <!-- Tops -->
          <div class="row">
            <div class="col-md-4 mb-3">
              <label for="edit_jersey_sando" class="form-label">Jersey Sando</label>
              <input type="text" name="jersey_sando" id="edit_jersey_sando" class="form-control">
            </div>
            <div class="col-md-4 mb-3">
              <label for="edit_jersey_neck" class="form-label">Jersey Neck</label>
              <input type="text" name="jersey_neck" id="edit_jersey_neck" class="form-control">
            </div>
            <div class="col-md-4 mb-3">
              <label for="edit_jersey_sando_size" class="form-label">Jersey Sando Size</label>
              <input type="text" name="jersey_sando_size" id="edit_jersey_sando_size" class="form-control">
            </div>
            <div class="col-md-4 mb-3">
              <label for="edit_longsleeves" class="form-label">Longsleeves</label>
              <input type="text" name="longsleeves" id="edit_longsleeves" class="form-control">
            </div>
            <div class="col-md-4 mb-3">
              <label for="edit_tshirt" class="form-label">T-Shirt</label>
              <input type="text" name="tshirt" id="edit_tshirt" class="form-control">
            </div>
            <div class="col-md-4 mb-3">
              <label for="edit_tshirt_size" class="form-label">T-Shirt Size</label>
              <input type="text" name="tshirt_size" id="edit_tshirt_size" class="form-control">
            </div>
            <div class="col-md-4 mb-3">
              <label for="edit_polo_size" class="form-label">Polo Size</label>
              <input type="text" name="polo_size" id="edit_polo_size" class="form-control">
            </div>
            <div class="col-md-4 mb-3">
              <label for="edit_others" class="form-label">Other Tops</label>
              <input type="text" name="others" id="edit_others" class="form-control">
            </div>
          </div>

          <!-- Bottoms -->
          <div class="row">
            <div class="col-md-4 mb-3">
              <label for="edit_jersey_short" class="form-label">Jersey Short</label>
              <input type="text" name="jersey_short" id="edit_jersey_short" class="form-control">
            </div>
            <div class="col-md-4 mb-3">
              <label for="edit_short_size" class="form-label">Short Size</label>
              <input type="text" name="short_size" id="edit_short_size" class="form-control">
            </div>
            <div class="col-md-4 mb-3">
              <label for="edit_jogging_pants" class="form-label">Jogging Pants</label>
              <input type="text" name="jogging_pants" id="edit_jogging_pants" class="form-control">
            </div>
          </div>

          <!-- Accessories -->
          <div class="row">
            <div class="col-md-4 mb-3">
              <label for="edit_warmer" class="form-label">Warmer</label>
              <input type="text" name="warmer" id="edit_warmer" class="form-control">
            </div>
            <div class="col-md-4 mb-3">
              <label for="edit_sublimation_dtf" class="form-label">Sublimation DTF</label>
              <input type="text" name="sublimation_dtf" id="edit_sublimation_dtf" class="form-control">
            </div>
            <div class="col-md-4 mb-3">
              <label for="edit_other_service" class="form-label">Other Service</label>
              <input type="text" name="other_service" id="edit_other_service" class="form-control">
            </div>
          </div>

          <!-- Material and Colors -->
          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="edit_material_type" class="form-label">Material Type</label>
              <input type="text" name="material_type" id="edit_material_type" class="form-control">
            </div>
            <div class="col-md-6 mb-3">
              <label for="edit_colors" class="form-label">Colors (comma-separated)</label>
              <input type="text" name="colors" id="edit_colors" class="form-control">
            </div>
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
    $.get('my_inquiries_crud.php', {action:'read'}, function(res){
        $('#inquiryTableBody').html(res);
    });
}

// Populate edit modal
function openEditModal(row) {
    $('#edit_inquiry_id').val(row.inquiry_id);

    $('#edit_customer_comment').val(row.customer_comment);
    $('#currentFile').text(row.customer_file || 'No file uploaded');

    $('#edit_jersey_sando').val(row.jersey_sando);
    $('#edit_jersey_neck').val(row.jersey_neck);
    $('#edit_jersey_sando_size').val(row.jersey_sando_size);
    $('#edit_longsleeves').val(row.longsleeves);
    $('#edit_tshirt').val(row.tshirt);
    $('#edit_tshirt_size').val(row.tshirt_size);
    $('#edit_polo_size').val(row.polo_size);
    $('#edit_others').val(row.others);

    $('#edit_jersey_short').val(row.jersey_short);
    $('#edit_short_size').val(row.short_size);
    $('#edit_jogging_pants').val(row.jogging_pants);

    $('#edit_warmer').val(row.warmer);
    $('#edit_sublimation_dtf').val(row.sublimation_dtf);
    $('#edit_other_service').val(row.other_service);

    $('#edit_material_type').val(row.material_type);
    $('#edit_colors').val(row.colors);

    var modal = new bootstrap.Modal(document.getElementById('editModal'));
    modal.show();
}

// Delete inquiry
function deleteInquiry(id) {
    if(!confirm('Are you sure you want to delete this inquiry?')) return;
    $.post('my_inquiries_crud.php', {action:'delete', inquiry_id:id}, function(res){
        alert(res);
        loadInquiries();
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
