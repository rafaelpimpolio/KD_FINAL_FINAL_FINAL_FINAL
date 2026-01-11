<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KD Sportswear & Apparel - Customer Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="customer_style.css">
    <link rel="stylesheet" href="../css/styles.css">

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
            </ul>
            <button class="navbar-toggle" id="navbarToggle" aria-label="Toggle navigation">
                <span id="menuIcon">☰</span>
            </button>
        </div>
    </nav>

    <!-- HEADER -->
    <div class="header">
        <h1>KD SPORTSWEAR & APPAREL</h1>
        <p class="sub">Sublimation • Custom Jerseys • Apparel Printing</p>
    </div>

    <!-- FORM CONTAINER -->
    <div class="form-container">
        <h2>Customer Details</h2>

    <form id="customerForm" class="needs-validation" novalidate action="javascript:void(0)">

        <!-- First & Last Name -->
        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">First Name</label>
                <input type="text" class="form-control" name="first_name" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Last Name</label>
                <input type="text" class="form-control" name="last_name" required>
            </div>
        </div>

        <!-- Phone & Email -->
        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Phone Number</label>
                <input type="tel" class="form-control" name="phone_number" pattern="^[0-9]{11}$" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Email</label>
                <input type="email" class="form-control" name="email" required>
            </div>
        </div>

        <!-- Address -->
        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Barangay</label>
                <input type="text" class="form-control" name="barangay" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">City / Municipality</label>
                <input type="text" class="form-control" name="city_municipality" required>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Province</label>
                <input type="text" class="form-control" name="province" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Postal Code</label>
                <input type="text" class="form-control" name="postal_code" pattern="^[0-9]{4}$" required>
            </div>
        </div>

        <!-- Login -->
        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Username</label>
                <input type="text" class="form-control" name="username" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Password</label>
                <input type="password" class="form-control" name="password" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Confirm Password</label>
                <input type="password" class="form-control" name="confirm_password" id="confirm_password" required>
              </div>
        </div>

        <button class="btn btn-primary w-100" type="submit">
            Create Account
        </button>
    </form>

    </div>

    <hr class="my-4">
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

    <!-- SCRIPTS -->
    <script src="js/jquery-min.js"></script>
    <script src="js/crud.js"></script>
    <script src="script.js"></script>
</body>
</html>
