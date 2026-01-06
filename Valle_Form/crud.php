<?php
session_start();

// -------------------
// Database connection
// -------------------
$host = 'localhost';
$user = 'root';
$pass = '';
$db   = 'kd_sportswear';
$conn = new mysqli($host,$user,$pass,$db);
if($conn->connect_error) die("Connection failed: ".$conn->connect_error);

// -------------------
// Determine action
// -------------------
$action = $_REQUEST['action'] ?? '';

if($action === 'read'){
    $res = $conn->query("SELECT o.*, i.customer_id, i.jersey_sando, i.jersey_sando_size, i.tshirt, i.tshirt_size, i.jersey_short, i.short_size, i.material_type, i.colors, i.customer_comment, i.customer_file
                         FROM orders o
                         JOIN inquiry i ON o.inquiry_id = i.inquiry_id
                         ORDER BY o.created_at DESC");

    if($res->num_rows > 0){
        while($row = $res->fetch_assoc()){
            $details = "Jersey Sando: ".$row['jersey_sando'].", ".$row['jersey_sando_size'].
                       " | T-shirt: ".$row['tshirt'].", ".$row['tshirt_size'].
                       " | Shorts: ".$row['jersey_short'].", ".$row['short_size'];

            echo "<tr>
                <td>{$row['order_id']}</td>
                <td>{$row['inquiry_id']}</td>
                <td>{$row['customer_id']}</td>
                <td>$details</td>
                <td>{$row['material_type']}</td>
                <td>{$row['colors']}</td>
                <td>{$row['customer_comment']}</td>
                <td>{$row['total_amount']}</td>
                <td>{$row['status']}</td>
                <td>{$row['created_at']}</td>
                <td>".($row['customer_file'] ? "<a href='uploads/{$row['customer_file']}' target='_blank'>View</a>" : 'No file')."</td>
                <td>
                    <button class='btn btn-info btn-sm' onclick='openEditOrderModal(".json_encode($row).")'>Edit</button>
                    <button class='btn btn-danger btn-sm' onclick='deleteOrder({$row['order_id']})'>Delete</button>
                </td>
            </tr>";
        }
    } else {
        echo "<tr><td colspan='12' class='text-center'>No orders found</td></tr>";
    }
}

elseif($action === 'update'){
    $order_id = intval($_POST['order_id']);
    $total_amount = floatval($_POST['total_amount']);
    $status = $conn->real_escape_string($_POST['status']);

    $sql = "UPDATE orders SET total_amount=$total_amount, status='$status' WHERE order_id=$order_id";

    if($conn->query($sql)) echo "Order updated successfully";
    else echo "Error: ".$conn->error;
}

elseif($action === 'delete'){
    $order_id = intval($_POST['order_id']);
    if($conn->query("DELETE FROM orders WHERE order_id=$order_id")) echo "Order deleted successfully";
    else echo "Error: ".$conn->error;
}
?>
