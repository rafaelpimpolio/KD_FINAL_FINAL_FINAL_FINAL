<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>KD Sportswear & Apparel - Inquiries</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="inquiry_style.css">
<link rel="stylesheet" href="../account/nav.css">
<link rel="stylesheet" href="my_inquiries.css">

</head>
<body>
    <!-- Navigation -->
  <nav class="navbar" id="navbar">
    <div class="container">
      <a href="#home" class="navbar-brand">KD Sportswear & Apparel</a>
      <ul class="navbar-menu" id="navbarMenu">
        <li><a href="../home.html#home" class="nav-link">Home</a></li>
            <li><a href="../home.html#home" class="nav-link">Home</a></li>
        <li><a href="../home.html#latest-designs" class="nav-link">Latest Designs</a></li>
        <li><a href="../home.html#gallery" class="nav-link">Gallery</a></li>
        <li><a href="../home.html#services" class="nav-link">Services</a></li>
        <li><a href="../Balatan_Form/inquiry.html" class="nav-link">Make Inquiry</a></li>
        <li><a href="../Balatan_my_inquiries/my_inquiries.php" class="nav-link">My Inquiries</a></li>
        <li><a href="../myOrders/myOrders.php" class="nav-link">My Orders</a></li>
        <li><a href="../login_signup.html" class="nav-link">Log out</a></li>
      </ul>
      <button class="navbar-toggle" id="navbarToggle" aria-label="Toggle navigation">
        <span id="menuIcon">☰</span>
      </button>
    </div>
  </nav>
  <h2 class="headings">Customer's Inquiries</h2>
<div class="container mt-5">
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
<div class="modal fade" id="editModal" tabindex="-1">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <form id="editForm" enctype="multipart/form-data">
        <div class="modal-header">
          <h5 class="modal-title">Edit Inquiry</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <input type="hidden" name="inquiry_id" id="edit_inquiry_id">

          <!-- Comment -->
          <label>Customer Comment</label>
          <textarea name="customer_comment" id="edit_customer_comment" class="form-control mb-3"></textarea>

          <!-- File -->
          <label>File</label>
          <input type="file" name="customer_file" class="form-control mb-1">
          <small id="currentFile" class="text-muted"></small>

          <hr>

          <!-- TOPS -->
          <h5>Tops</h5>

          <label>Jersey Sando</label>
          <select name="jersey_sando" id="edit_jersey_sando" class="form-select">
            <option value="">-- None --</option>
            <option value="jerseySandoOrdinary">Ordinary CUT</option>
            <option value="jerseySandoNBA">NBA CUT</option>
            <option value="jerseySandoNoRib">REVERSIBLE W/O RIBBINGS</option>
            <option value="jerseySandoRib">REVERSIBLE W RIBBINGS</option>
          </select>

          <label class="mt-2">Neck</label>
          <select name="jersey_neck" id="edit_jersey_neck" class="form-select">
            <option value="">-- None --</option>
            <option value="jerseyRound">ROUND NECK</option>
            <option value="jerseyV">V-NECK</option>
            <option value="jerseyW">W-NECK</option>
          </select>

          <label class="mt-2">Sando Size</label><br>
          <label><input type="radio" name="jersey_sando_size" value="sandoMale"> Male</label>
          <label><input type="radio" name="jersey_sando_size" value="sandoFemale"> Female</label>
          <label><input type="radio" name="jersey_sando_size" value="sandoKids"> Kids</label>

          <label class="mt-2">T-Shirt</label>
          <select name="tshirt" id="edit_tshirt" class="form-select">
            <option value="">-- None --</option>
            <option value="tshirtRound">ROUND NECK</option>
            <option value="tshirtV">V-NECK</option>
            <option value="tshirtFull">FULL COLLAR</option>
            <option value="tshirtHalf">HALF COLLAR</option>
          </select>

          <label class="mt-2">T-Shirt Size</label><br>
          <label><input type="radio" name="tshirt_size" value="tshirtMale"> Male</label>
          <label><input type="radio" name="tshirt_size" value="tshirtFemale"> Female</label>
          <label><input type="radio" name="tshirt_size" value="tshirtKid"> Kids</label>

          <hr>

          <!-- BOTTOMS -->
          <h5>Bottoms</h5>

          <label>Jersey Short</label>
          <select name="jersey_short" id="edit_jersey_short" class="form-select">
            <option value="">-- None --</option>
            <option value="shortNormal">NORMAL CUT</option>
            <option value="shortKnee">ABOVE THE KNEE</option>
            <option value="shortUnisex">UNISEX</option>
            <option value="shortCycling">CYCLING</option>
          </select>

          <label class="mt-2">Short Size</label><br>
          <label><input type="radio" name="short_size" value="shortAdult"> Adult</label>
          <label><input type="radio" name="short_size" value="shortKids"> Kids</label>

          <hr>

          <!-- MATERIAL -->
          <h5>Material</h5>
          <label><input type="radio" name="material_type" value="polydex"> Polydex</label><br>
          <label><input type="radio" name="material_type" value="cottonBlend"> Cotton Blend</label><br>
          <label><input type="radio" name="material_type" value="triBlend"> Tri-Blend</label><br>
          <label><input type="radio" name="material_type" value="performance"> Performance</label>

          <h5 class="mt-3">Colors</h5>
          <label><input type="checkbox" name="colors[]" value="red"> Red</label>
          <label><input type="checkbox" name="colors[]" value="blue"> Blue</label>
          <label><input type="checkbox" name="colors[]" value="green"> Green</label>
          <label><input type="checkbox" name="colors[]" value="black"> Black</label>
          <label><input type="checkbox" name="colors[]" value="white"> White</label>
        </div>

        <div class="modal-footer">
          <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button class="btn btn-primary">Save</button>
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
                    <a href="https://www.facebook.com/kdsportswearandapparel" target="_blank" rel="noopener noreferrer" aria-label="Facebook"><img src="../images/facebook.png" alt=""></a>
                    <a href="https://instagram.com" target="_blank" rel="noopener noreferrer" aria-label="Instagram"><img src="../images/instagram.png" alt=""></a>
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
            url:'my_inquiries_crud.php',
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

function proceedToOrder(inquiryId){
    if(!confirm('Do you want to convert this inquiry into an order?')) return;

    $.post('../orders/orders_crud.php', {action:'create_from_inquiry', inquiry_id: inquiryId}, function(res){
        alert(res);
        loadInquiries(); // refresh table
    });
}

</script>
</body>
</html>
