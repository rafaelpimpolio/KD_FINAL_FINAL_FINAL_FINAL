<?php
require_once __DIR__ . '/connect.php';

// 🔒 ERROR TRAP: force JSON for AJAX errors
header('X-Content-Type-Options: nosniff');

$action = $_GET['action'] ?? $_POST['action'] ?? 'read';

switch ($action) {
    case 'create':
        handleCreate();
        break;
    case 'read':
        handleRead();
        break;
    case 'edit':
        handleEdit();
        break;
    case 'update':
        handleUpdate();
        break;
    case 'delete':
        handleDelete();
        break;
    default:
        handleRead();
}

/* =========================
   CREATE PAYMENT
========================= */
function handleCreate() {
    global $conn;

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        showAlert("danger", "Invalid request method.", true);
    }

    $customer_id = intval($_POST['customer_id'] ?? 0);
    $order_id    = intval($_POST['order_id'] ?? 0);
    $employee_id = intval($_POST['employee_id'] ?? 0);
    $method      = trim($_POST['method_of_payment'] ?? '');
    $downpayment = floatval($_POST['downpayment'] ?? -1);
    $status      = trim($_POST['status'] ?? 'PENDING');
    $date        = $_POST['date'] ?? date('Y-m-d');

    // 🔒 ERROR TRAPS
    if ($customer_id <= 0) showAlert("danger", "Customer is required.", true);
    if ($order_id <= 0) showAlert("danger", "Order selection is required.", true);
    if ($employee_id <= 0) showAlert("danger", "Employee ID is required.", true);
    if ($method === '') showAlert("danger", "Method of payment is required.", true);
    if ($downpayment < 0) showAlert("danger", "Downpayment must be zero or greater.", true);
    if (!strtotime($date)) showAlert("danger", "Invalid payment date.", true);

    // FETCH ORDER
    $stmt_order = $conn->prepare("SELECT total_amount FROM orders WHERE order_id=? AND status='FOR PAYMENT'");
    if (!$stmt_order) showAlert("danger", "Order validation failed.", true);
    $stmt_order->bind_param("i", $order_id);
    $stmt_order->execute();
    $res_order = $stmt_order->get_result();
    if ($res_order->num_rows === 0) showAlert("danger", "Selected order is not available for payment.", true);

    $row = $res_order->fetch_assoc();
    $total_amount = floatval($row['total_amount']);
    if ($downpayment > $total_amount) showAlert("danger", "Downpayment cannot exceed total amount.", true);

    $balance = $total_amount - $downpayment;

    // 🔒 TRANSACTION
    if (!$conn->begin_transaction()) showAlert("danger", "Unable to start transaction.", true);

    try {
        // INSERT PAYMENT
        $stmt = $conn->prepare("
            INSERT INTO payment
            (customer_id, order_id, employee_id, method_of_payment,
             total_amount, downpayment, balance, date, status)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        if (!$stmt) throw new Exception("Insert prepare failed");

        $stmt->bind_param(
            "iiisdddss",
            $customer_id,
            $order_id,
            $employee_id,
            $method,
            $total_amount,
            $downpayment,
            $balance,
            $date,
            $status
        );

        if (!$stmt->execute()) throw new Exception("Insert execution failed");
        $payment_id = $stmt->insert_id;

        // UPDATE ORDER STATUS
        $orderStatus = ($balance <= 0) ? 'PAID' : 'PARTIAL';
        $stmt2 = $conn->prepare("UPDATE orders SET status=? WHERE order_id=?");
        if (!$stmt2) throw new Exception("Order update failed");
        $stmt2->bind_param("si", $orderStatus, $order_id);
        $stmt2->execute();

        $conn->commit();
        writeLog("CREATE", $payment_id, $order_id, null, "Payment created");
        showAlert("success", "Payment recorded successfully.", true);

    } catch (Exception $e) {
        $conn->rollback();
        writeLog("CREATE_FAILED", null, $order_id, null, $e->getMessage());
        showAlert("danger", "Payment failed. Please try again.", true);
    }
}

/* =========================
   READ PAYMENTS
========================= */
function handleRead() {
    global $conn;

    $result = $conn->query("
        SELECT p.*, CONCAT(c.first_name,' ',c.last_name) AS customer_name
        FROM payment p
        LEFT JOIN customer c ON p.customer_id=c.customer_id
        ORDER BY p.payment_id DESC
    ");

    if (!$result) {
        echo '<tr><td colspan="11" class="text-danger">Error loading payments.</td></tr>';
        return;
    }

    if ($result->num_rows === 0) {
        echo '<tr><td colspan="11" class="text-muted text-center">No payment records found.</td></tr>';
        return;
    }

    while ($row = $result->fetch_assoc()) {
        $json = json_encode($row);
        echo "<tr>
            <td>{$row['payment_id']}</td>
            <td>{$row['order_id']}</td>
            <td>{$row['customer_name']}</td>
            <td>{$row['employee_id']}</td>
            <td>{$row['method_of_payment']}</td>
            <td>{$row['total_amount']}</td>
            <td>{$row['downpayment']}</td>
            <td>{$row['balance']}</td>
            <td>{$row['date']}</td>
            <td>{$row['status']}</td>
            <td>
                <button class='btn btn-warning btn-sm btn-edit' data-payment='{$json}'>Edit</button>
                <a class='btn btn-danger btn-sm' onclick='return confirm(\"Delete this payment?\")' href='payment_crud.php?action=delete&id={$row['payment_id']}'>Delete</a>
            </td>
        </tr>";
    }
}

/* =========================
   EDIT PAYMENT
========================= */
function handleEdit() {
    global $conn;

    header('Content-Type: application/json');

    $id = intval($_GET['id'] ?? 0);
    if ($id <= 0) { echo json_encode([]); exit; }

    $stmt = $conn->prepare("SELECT * FROM payment WHERE payment_id=?");
    if (!$stmt) { echo json_encode([]); exit; }

    $stmt->bind_param("i", $id);
    $stmt->execute();
    echo json_encode($stmt->get_result()->fetch_assoc() ?: []);
}

/* =========================
   UPDATE PAYMENT
========================= */
function handleUpdate() {
    global $conn;

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') showAlert("danger", "Invalid update request.", true);

    $payment_id  = intval($_POST['payment_id'] ?? 0);
    $employee_id = intval($_POST['employee_id'] ?? 0);
    $downpayment = floatval($_POST['downpayment'] ?? -1);
    $method      = trim($_POST['method_of_payment'] ?? '');
    $status      = trim($_POST['status'] ?? '');
    $date        = $_POST['date'] ?? date('Y-m-d');

    // 🔒 ERROR TRAPS
    if ($payment_id <= 0) showAlert("danger", "Invalid payment ID.", true);
    if ($employee_id <= 0) showAlert("danger", "Employee ID is required.", true);
    if ($downpayment < 0) showAlert("danger", "Downpayment must be zero or greater.", true);
    if ($method === '') showAlert("danger", "Method of payment is required.", true);

    $stmt = $conn->prepare("SELECT order_id, total_amount FROM payment WHERE payment_id=?");
    if (!$stmt) showAlert("danger", "Payment lookup failed.", true);
    $stmt->bind_param("i", $payment_id);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res->num_rows === 0) showAlert("danger", "Payment not found.", true);

    $row = $res->fetch_assoc();
    $order_id = $row['order_id'];
    $total_amount = $row['total_amount'];
    if ($downpayment > $total_amount) showAlert("danger", "Downpayment exceeds total.", true);

    $balance = $total_amount - $downpayment;

    $conn->begin_transaction();
    try {
        $stmt = $conn->prepare("
            UPDATE payment SET
                employee_id=?,
                method_of_payment=?,
                downpayment=?,
                balance=?,
                date=?,
                status=?
            WHERE payment_id=?
        ");
        $stmt->bind_param("isddssi", $employee_id, $method, $downpayment, $balance, $date, $status, $payment_id);
        $stmt->execute();

        $orderStatus = ($balance <= 0) ? 'PAID' : 'PARTIAL';
        $stmt2 = $conn->prepare("UPDATE orders SET status=? WHERE order_id=?");
        $stmt2->bind_param("si", $orderStatus, $order_id);
        $stmt2->execute();

        $conn->commit();
        writeLog("UPDATE", $payment_id, $order_id, null, "Payment updated");
        showAlert("success", "Payment updated successfully.", true);
    } catch (Exception $e) {
        $conn->rollback();
        writeLog("UPDATE_FAILED", $payment_id, $order_id, null, $e->getMessage());
        showAlert("danger", "Update failed.", true);
    }
}

/* =========================
   DELETE PAYMENT
========================= */
function handleDelete() {
    global $conn;

    $id = intval($_GET['id'] ?? 0);
    if ($id <= 0) showAlert("danger", "Invalid payment ID.", true);

    $stmt = $conn->prepare("DELETE FROM payment WHERE payment_id=?");
    if (!$stmt) showAlert("danger", "Delete failed.", true);

    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        writeLog("DELETE", $id, null, null, "Payment deleted");
        showAlert("success", "Payment deleted.", true);
    } else {
        showAlert("danger", "Unable to delete payment.", true);
    }
}

/* =========================
   ALERT HANDLER
========================= */
function showAlert($type, $message, $ajax = false) {
    if ($ajax) { echo $message; exit; }

    $redirect = $_SERVER['HTTP_REFERER'] ?? 'payment.html';
    ?>
    <script>
        alert("<?= strip_tags($message) ?>");
        window.location.href = "<?= $redirect ?>";
    </script>
    <?php
    exit();
}

$conn->close();
?>
