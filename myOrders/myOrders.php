<?php
session_start();

// Check if customer is logged in
if(!isset($_SESSION['customer_id'])){
    echo "Please log in to view your orders.";
    exit;
}

$customer_id = intval($_SESSION['customer_id']);

// Database connection
$host = 'localhost';
$user = 'root';
$pass = '';
$db   = 'kd_sportswear';
$conn = new mysqli($host,$user,$pass,$db);
if($conn->connect_error) die("Connection failed: ".$conn->connect_error);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>My Orders - KD Sportswear & Apparel</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="../account/nav.css">
<link rel="stylesheet" href="../Magan_Form/inquiry_style.css">
<link rel="stylesheet" href="my_inquiries.css">
</head>
<body>

<!-- Navigation -->
<nav class="navbar" id="navbar">
    <div class="container">
        <a href="#home" class="navbar-brand">KD Sportswear & Apparel</a>
        <ul class="navbar-menu" id="navbarMenu">
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

<div class="container mt-5">
    <h2 class="mb-4 text-center">My Orders</h2>
    <div class="table-responsive table-container">
        <table class="table table-striped table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>Order ID</th>
                    <th>Inquiry ID</th>
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
            <tbody>
                <?php
                $res = $conn->query("
                    SELECT o.*, i.jersey_sando, i.jersey_sando_size, i.tshirt, i.tshirt_size, i.jersey_short, i.short_size,
                           i.material_type, i.colors, i.customer_comment, i.customer_file
                    FROM orders o
                    JOIN inquiry i ON o.inquiry_id = i.inquiry_id
                    WHERE i.customer_id = $customer_id
                    ORDER BY o.created_at DESC
                ");

                if($res->num_rows > 0){
                    while($row = $res->fetch_assoc()){
                        $details = "Jersey Sando: ".$row['jersey_sando']." (".$row['jersey_sando_size'].") | ".
                                   "T-Shirt: ".$row['tshirt']." (".$row['tshirt_size'].") | ".
                                   "Shorts: ".$row['jersey_short']." (".$row['short_size'].")";
                        
                        echo "<tr>
                            <td>{$row['order_id']}</td>
                            <td>{$row['inquiry_id']}</td>
                            <td>$details</td>
                            <td>{$row['material_type']}</td>
                            <td>{$row['colors']}</td>
                            <td>{$row['customer_comment']}</td>
                            <td>{$row['total_amount']}</td>
                            <td>{$row['status']}</td>
                            <td>{$row['created_at']}</td>
                            <td>".($row['customer_file'] ? "<a href='uploads/{$row['customer_file']}' target='_blank'>View</a>" : 'No file')."</td>
                            <td>
                                <form method='post' action='delete_order.php' onsubmit='return confirm(\"Are you sure you want to delete this order?\");'>
                                    <input type='hidden' name='order_id' value='{$row['order_id']}'>
                                    <button type='submit' class='btn btn-danger btn-sm'>Delete</button>
                                </form>
                            </td>
                        </tr>";
                    }
                } else {
                    echo "<tr><td colspan='11' class='text-center'>You have no orders yet.</td></tr>";
                }
                ?>
            </tbody>
        </table>
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
                    <li>0998 976 5742</li>
                    <li>kdsportswear2023@gmail.com</li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <p style="color: rgba(255, 255, 255, 0.8); font-size: 0.875rem; margin: 0;">© 2025 KD Sportswear & Apparel. All rights reserved.</p>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
