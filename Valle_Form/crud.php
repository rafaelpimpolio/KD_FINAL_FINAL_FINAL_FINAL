<?php
require 'connect.php'; // your DB connection file

$action = $_REQUEST['action'] ?? '';

if($action == 'read') {
    $sql = "SELECT o.order_id, o.inquiry_id, o.status, i.customer_id, i.customer_comment, i.customer_file,
                   CONCAT_WS(', ', i.jersey_sando, i.jersey_neck, i.longsleeves, i.tshirt, i.polo_size, i.others,
                                 i.jersey_short, i.short_size, i.jogging_pants,
                                 i.warmer, i.sublimation_dtf, i.other_service) AS order_details,
                   i.material_type, i.colors, i.initial_price, i.created_at
            FROM orders o
            JOIN inquiry i ON o.inquiry_id = i.inquiry_id
            ORDER BY o.order_id DESC";
    $res = $conn->query($sql);
    $rows = '';
    if($res->num_rows > 0){
        while($r = $res->fetch_assoc()){
            $rows .= '<tr>
                <td>'.$r['order_id'].'</td>
                <td>'.$r['inquiry_id'].'</td>
                <td>'.$r['customer_id'].'</td>
                <td>'.$r['order_details'].'</td>
                <td>'.$r['material_type'].'</td>
                <td>'.$r['colors'].'</td>
                <td>'.$r['customer_comment'].'</td>
                <td>'.$r['initial_price'].'</td>
                <td>'.$r['status'].'</td>
                <td>'.$r['created_at'].'</td>
                <td>'.($r['customer_file'] ? '<a href="../uploads/'.$r['customer_file'].'" target="_blank">View</a>' : 'No file').'</td>
                <td class="action-buttons">
                    <button class="btn btn-info" onclick=\'openEditOrderModal('.json_encode($r).')\'>Edit</button>
                    <button class="btn btn-danger" onclick="deleteOrder('.$r['order_id'].')">Delete</button>
                </td>
            </tr>';
        }
    } else {
        $rows = '<tr><td colspan="12" class="text-center">No orders yet.</td></tr>';
    }
    echo $rows;
}

elseif($action == 'update'){
    $order_id = $_POST['order_id'];
    $status = $_POST['status'];
    $stmt = $conn->prepare("UPDATE orders SET status=? WHERE order_id=?");
    $stmt->bind_param('si', $status, $order_id);
    if($stmt->execute()){
        echo "Order status updated successfully.";
    } else {
        echo "Failed to update order status.";
    }
}

elseif($action == 'delete'){
    $order_id = $_POST['order_id'];
    $stmt = $conn->prepare("DELETE FROM orders WHERE order_id=?");
    $stmt->bind_param('i', $order_id);
    if($stmt->execute()){
        echo "Order deleted successfully.";
    } else {
        echo "Failed to delete order.";
    }
}
?>
