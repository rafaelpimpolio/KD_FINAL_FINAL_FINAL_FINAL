<?php
require_once __DIR__ . '/connect.php';

$action = $_GET['action'] ?? $_POST['action'] ?? 'read';

switch ($action) {
    case 'create':
        handleCreate(); // ✅ EMPLOYEE ID FIX inside function
        break;
    case 'read':
        handleRead();
        break;
    case 'edit':
        handleEdit();
        break;
    case 'update':
        handleUpdate(); // ✅ EMPLOYEE ID FIX inside function
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

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;

    $customer_id = intval($_POST['customer_id'] ?? 0);
    $order_id    = intval($_POST['order_id'] ?? 0);
    $method      = $_POST['method_of_payment'] ?? 'CASH';
    $downpayment = floatval($_POST['downpayment'] ?? 0.00);
    $status      = $_POST['status'] ?? 'PENDING';
    $date        = $_POST['date'] ?? date('Y-m-d');

    if ($customer_id <= 0 || $order_id <= 0) {
        showAlert("danger", "Please select a customer and an order.", true);
    }

    // Get selected order details
    $sql_order = "SELECT total_amount, employee_id FROM orders WHERE order_id=? AND status='FOR PAYMENT'";
    $stmt_order = $conn->prepare($sql_order);
    $stmt_order->bind_param("i", $order_id);
    $stmt_order->execute();
    $res_order = $stmt_order->get_result();

    if ($res_order->num_rows === 0) {
        showAlert("danger", "Selected order is not available for payment.", true);
    }

    $row = $res_order->fetch_assoc();
    $total_amount = floatval($row['total_amount']);

    // ✅ EMPLOYEE ID FIX: read from form if provided, else fallback to order
    $employee_id = intval($_POST['employee_id'] ?? 0);
    if ($employee_id <= 0) {
        $employee_id = $row['employee_id'] ?? null;
    }

    // 🔒 ERROR TRAP
    if ($employee_id === null) {
        showAlert("danger", "Employee ID is required.", true);
    }

    $balance = $total_amount - $downpayment;

    // Insert payment for only that selected order
    $conn->begin_transaction();
    try {
        $sql = "INSERT INTO payment
                (customer_id, order_id, employee_id, method_of_payment, total_amount, downpayment, balance, date, status)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            "iiisdddss",
            $customer_id,
            $order_id,
            $employee_id, // ✅ EMPLOYEE ID FIX
            $method,
            $total_amount,
            $downpayment,
            $balance,
            $date,
            $status
        );

        if (!$stmt->execute()) {
            throw new Exception($stmt->error);
        }

        $payment_id = $stmt->insert_id;

        // Update order status
        $orderStatus = ($balance <= 0) ? 'PAID' : 'PARTIAL';
        $stmt2 = $conn->prepare("UPDATE orders SET status=? WHERE order_id=?");
        $stmt2->bind_param("si", $orderStatus, $order_id);
        $stmt2->execute();

        $conn->commit();

        writeLog("CREATE", $payment_id, $order_id, null, "Payment created");
        showAlert("success", "Payment recorded successfully.", true);

    } catch (Exception $e) {
        $conn->rollback();
        showAlert("danger", "Payment failed: " . $e->getMessage(), true);
    }

    $stmt->close();
}

/* =========================
   READ PAYMENTS
========================= */
function handleRead() {
    global $conn;

    $sql = "
        SELECT 
            p.*,
            CONCAT(c.first_name, ' ', c.last_name) AS customer_name
        FROM payment p
        LEFT JOIN customer c ON p.customer_id = c.customer_id
        ORDER BY p.payment_id DESC
    ";

    $result = $conn->query($sql);

    if (!$result || $result->num_rows === 0) {
        echo '<tr><td colspan="10" class="text-center text-muted">No payment records found.</td></tr>';
        return;
    }

    while ($row = $result->fetch_assoc()) {
        $paymentJson = json_encode($row, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
        echo '<tr>
            <td>'.htmlspecialchars($row['payment_id']).'</td>
            <td>'.htmlspecialchars($row['order_id']).'</td>
            <td>'.htmlspecialchars($row['customer_name']).'</td>
            <td>'.htmlspecialchars($row['method_of_payment']).'</td>
            <td>'.htmlspecialchars($row['total_amount']).'</td>
            <td>'.htmlspecialchars($row['downpayment']).'</td>
            <td>'.htmlspecialchars($row['balance']).'</td>
            <td>'.htmlspecialchars($row['date']).'</td>
            <td>'.htmlspecialchars($row['status']).'</td>
            <td style="white-space: nowrap;">
                <a href="#"
                   class="btn btn-sm btn-warning btn-edit"
                   data-payment=\''. $paymentJson .'\'>
                   Edit
                </a>
                <a href="payment_crud.php?action=delete&id='.intval($row['payment_id']).'"
                   class="btn btn-sm btn-danger"
                   style="margin-left:5px;">
                   Delete
                </a>
            </td>
        </tr>';
    }
}

/* =========================
   EDIT PAYMENT
========================= */
function handleEdit() {
    global $conn;

    header('Content-Type: application/json');

    $id = intval($_GET['id'] ?? 0);
    if ($id <= 0) {
        echo json_encode([]);
        exit;
    }

    $stmt = $conn->prepare("SELECT * FROM payment WHERE payment_id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    echo json_encode($result->fetch_assoc() ?: []);
}

/* =========================
   UPDATE PAYMENT
========================= */
function handleUpdate() {
    global $conn;

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;

    $payment_id  = intval($_POST['payment_id'] ?? 0);
    $downpayment = floatval($_POST['downpayment'] ?? 0);
    $method      = $_POST['method_of_payment'] ?? '';
    $status      = $_POST['status'] ?? '';
    $date        = $_POST['date'] ?? date('Y-m-d');

    if ($payment_id <= 0) {
        showAlert("danger", "Invalid payment ID.", true);
    }

    // Fetch existing payment
    $stmt = $conn->prepare("SELECT order_id, total_amount, employee_id FROM payment WHERE payment_id=?");
    $stmt->bind_param("i", $payment_id);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows === 0) {
        showAlert("danger", "Payment not found.", true);
    }

    $row = $res->fetch_assoc();
    $order_id = $row['order_id'];
    $total_amount = $row['total_amount'];

    if ($downpayment > $total_amount) {
        showAlert("danger", "Downpayment exceeds total.", true);
    }

    $balance = $total_amount - $downpayment;

    // ✅ EMPLOYEE ID FIX for UPDATE
    $employee_id = intval($_POST['employee_id'] ?? 0);
    if ($employee_id <= 0) {
        $employee_id = $row['employee_id'] ?? null;
    }
    if ($employee_id === null) {
        showAlert("danger", "Employee ID is required.", true);
    }

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

        $stmt->bind_param(
            "isddssi",
            $employee_id, // ✅ EMPLOYEE ID FIX
            $method,
            $downpayment,
            $balance,
            $date,
            $status,
            $payment_id
        );

        $stmt->execute();

        // Update order status
        $orderStatus = ($balance <= 0) ? 'PAID' : 'PARTIAL';
        $stmt2 = $conn->prepare("UPDATE orders SET status=? WHERE order_id=?");
        $stmt2->bind_param("si", $orderStatus, $order_id);
        $stmt2->execute();

        $conn->commit();

        writeLog("UPDATE", $payment_id, $order_id, null, "Payment updated");
        showAlert("success", "Payment updated successfully.", true);

    } catch (Exception $e) {
        $conn->rollback();
        showAlert("danger", "Update failed.", true);
    }
}

/* =========================
   DELETE PAYMENT
========================= */
function handleDelete() {
    global $conn;

    $id = intval($_GET['id'] ?? 0);
    if ($id <= 0) return;

    $stmt = $conn->prepare("DELETE FROM payment WHERE payment_id=?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        writeLog("DELETE", $id, null, null, "Payment deleted");
        showAlert("success", "Payment deleted.", true);
    } else {
        showAlert("danger", "Error deleting payment.", true);
    }

    $stmt->close();
}

/* =========================
   ALERT
========================= */
function showAlert($type, $message, $ajax = false) {
    if ($ajax || (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest')) {
        echo $message;
        exit();
    } else {
        $redirect = $_SERVER['HTTP_REFERER'] ?? 'payment.html';
        ?>
        <script>
        alert("<?= strip_tags($message) ?>");
        window.location.href = "<?= $redirect ?>";
        </script>
        <?php
        exit();
    }
}

$conn->close();
?>
