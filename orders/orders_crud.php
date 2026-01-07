<?php
session_start();

/* =========================
   DATABASE CONNECTION
========================= */
$conn = new mysqli("localhost", "root", "", "kd_sportswear");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$action = $_REQUEST['action'] ?? '';

/* =========================
   READ ORDERS
========================= */
if ($action === 'read') {

    $sql = "
    SELECT 
        o.order_id,
        o.inquiry_id,
        o.total_amount,
        o.status,
        o.created_at,

        i.customer_id,
        i.jersey_sando,
        i.jersey_sando_size,
        i.tshirt,
        i.tshirt_size,
        i.jersey_short,
        i.short_size,
        i.material_type,   
        i.colors,          
        i.customer_comment,
        i.customer_file

    FROM orders o
    INNER JOIN inquiry i ON o.inquiry_id = i.inquiry_id
    ORDER BY o.created_at DESC
";

    $res = $conn->query($sql);

    if (!$res || $res->num_rows === 0) {
        echo "<tr><td colspan='12' class='text-center'>No orders found</td></tr>";
        exit;
    }

    while ($row = $res->fetch_assoc()) {

        $details = "
            Jersey Sando: {$row['jersey_sando']} ({$row['jersey_sando_size']})<br>
            T-Shirt: {$row['tshirt']} ({$row['tshirt_size']})<br>
            Shorts: {$row['jersey_short']} ({$row['short_size']})
        ";

        $fileLink = $row['customer_file']
            ? "<a href='../Magan_Form/{$row['customer_file']}' target='_blank'>View</a>"
            : "No file";

        echo "
        <tr>
            <td>{$row['order_id']}</td>
            <td>{$row['inquiry_id']}</td>
            <td>{$row['customer_id']}</td>
            <td>{$details}</td>
            <td>{$row['material_type']}</td>
            <td>{$row['colors']}</td>
            <td>{$row['customer_comment']}</td>
            <td>{$row['total_amount']}</td>
            <td>{$row['status']}</td>
            <td>{$row['created_at']}</td>
            <td>{$fileLink}</td>
            <td>
                <button class='btn btn-info btn-sm'
                    onclick='openEditOrderModal(" . json_encode($row) . ")'>
                    Edit
                </button>
                <button class='btn btn-danger btn-sm'
                    onclick='deleteOrder({$row['order_id']})'>
                    Delete
                </button>
            </td>
        </tr>";
    }
}

/* =========================
   UPDATE ORDER
========================= */
elseif ($action === 'update') {

    $order_id = intval($_POST['order_id']);
    $status   = $conn->real_escape_string($_POST['status']);

    $sql = "UPDATE orders SET status='$status' WHERE order_id=$order_id";

    if ($conn->query($sql)) {
        echo "Order updated successfully";
    } else {
        echo "Update failed: " . $conn->error;
    }
}


/* =========================
   DELETE ORDER
========================= */
elseif ($action === 'delete') {

    $order_id = intval($_POST['order_id']);

    echo $conn->query("DELETE FROM orders WHERE order_id = $order_id")
        ? "Order deleted successfully."
        : "Error deleting order.";
}

/* =========================
   CREATE ORDER FROM INQUIRY
========================= */
elseif ($action === 'create' || $action === 'create_from_inquiry') {

    $inquiry_id = intval($_POST['inquiry_id']);

    if (!$inquiry_id) {
        echo "Invalid inquiry.";
        exit;
    }

    // Fetch inquiry from DB
    $check = $conn->query("SELECT * FROM inquiry WHERE inquiry_id=$inquiry_id AND status='APPROVED'");
    if ($check->num_rows == 0) {
        echo "Inquiry is not approved or does not exist.";
        exit;
    }

    $row = $check->fetch_assoc();
    $total_amount = $row['initial_price']; // always use DB value

    // Insert new order
    $sql = "INSERT INTO orders (inquiry_id, employee_id, total_amount, status, created_at)
            VALUES ($inquiry_id, NULL, '$total_amount', 'PENDING', NOW())";

    if($conn->query($sql)){
        // Mark inquiry as ORDERED
        $conn->query("UPDATE inquiry SET status='ORDERED' WHERE inquiry_id=$inquiry_id");
        echo "Inquiry converted to order successfully!";
    } else {
        echo "Error: ".$conn->error;
    }
}


?>
