<?php
session_start();

// -------------------
// Database connection
// -------------------
$host = 'localhost';
$user = 'root';
$pass = '';
$db   = 'kd_sportswear';
$conn = new mysqli($host, $user, $pass, $db);
if($conn->connect_error) die("Connection failed: ".$conn->connect_error);

// -------------------
// Determine action
// -------------------
$action = $_REQUEST['action'] ?? '';

if($action == 'read'){
    $customer_id = $_SESSION['customer_id'] ?? 0; // logged-in customer
    $res = $conn->query("SELECT * FROM inquiry WHERE customer_id = $customer_id ORDER BY created_at DESC");

    if($res->num_rows > 0){
        while($row = $res->fetch_assoc()){
            $details = "Jersey Sando: ".$row['jersey_sando'].", ".$row['jersey_sando_size'].
                       " | T-shirt: ".$row['tshirt'].", ".$row['tshirt_size'].
                       " | Shorts: ".$row['jersey_short'].", ".$row['short_size'];

            echo "<tr>
                <td>{$row['inquiry_id']}</td>
                <td>{$row['customer_id']}</td>
                <td>$details</td>
                <td>{$row['material_type']}</td>
                <td>{$row['colors']}</td>
                <td>{$row['customer_comment']}</td>
                <td>{$row['initial_price']}</td>
                <td>{$row['status']}</td>
                <td>{$row['created_at']}</td>
                <td>".($row['customer_file'] ? "<a href='uploads/{$row['customer_file']}' target='_blank'>View</a>" : 'No file')."</td>
                <td class='action-buttons'>
                    <button class='btn btn-info btn-sm' onclick='openEditModal(".json_encode($row).")'>Edit</button>
                    <button class='btn btn-danger btn-sm' onclick='deleteInquiry({$row['inquiry_id']})'>Delete</button>
                </td>
            </tr>";
        }
    } else {
        echo "<tr><td colspan='11' class='text-center'>No inquiries found</td></tr>";
    }
}
elseif($action == 'update'){
    $id = intval($_POST['inquiry_id']);
    $customer_id = $_SESSION['customer_id'] ?? 0;

    // Make sure the inquiry belongs to the customer
    $check = $conn->query("SELECT * FROM inquiry WHERE inquiry_id = $id AND customer_id = $customer_id");
    if($check->num_rows == 0){
        echo "You cannot edit this inquiry.";
        exit;
    }

    $comment = $conn->real_escape_string($_POST['customer_comment']);

    $file_name = '';
    if(isset($_FILES['customer_file']) && $_FILES['customer_file']['name'] != ''){
        $file_name = time().'_'.basename($_FILES['customer_file']['name']);
        move_uploaded_file($_FILES['customer_file']['tmp_name'],'uploads/'.$file_name);
    }

    $fields = [
        'jersey_sando','jersey_neck','jersey_sando_size','longsleeves',
        'tshirt','tshirt_size','polo_size','others',
        'jersey_short','short_size','jogging_pants',
        'warmer','sublimation_dtf','other_service',
        'material_type','colors'
    ];

    $sql = "UPDATE inquiry SET customer_comment='$comment'";
    foreach($fields as $f){
        $val = $conn->real_escape_string($_POST[$f] ?? '');
        $sql .= ", $f='$val'";
    }

    if($file_name) $sql .= ", customer_file='$file_name'";
    $sql .= " WHERE inquiry_id=$id";

    if($conn->query($sql)) echo "Inquiry updated successfully";
    else echo "Error: ".$conn->error;
}
elseif($action == 'delete'){
    $id = intval($_POST['inquiry_id']);
    $customer_id = $_SESSION['customer_id'] ?? 0;

    // Make sure the inquiry belongs to the customer
    $check = $conn->query("SELECT * FROM inquiry WHERE inquiry_id = $id AND customer_id = $customer_id");
    if($check->num_rows == 0){
        echo "You cannot delete this inquiry.";
        exit;
    }

    if($conn->query("DELETE FROM inquiry WHERE inquiry_id=$id")) echo "Inquiry deleted successfully";
    else echo "Error: ".$conn->error;
}
?>
