<?php
require_once __DIR__ . '/connect.php';

$action = $_GET['action'] ?? $_POST['action'] ?? 'read';

switch ($action) {
    case 'create':
        handleCreate(); // <-- uses the new version
        break;
    case 'read':
        handleRead();
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
    $order_id    = intval($_POST['order_id'] ?? 0); // <-- selected order
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
    $balance = $total_amount - $downpayment;
    $employee_id = $row['employee_id'] ?? null;

    // Insert payment for only that selected order
    $sql = "INSERT INTO payment
            (customer_id, order_id, employee_id, method_of_payment, total_amount, downpayment, balance, date, status)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
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

    if ($stmt->execute()) {
        writeLog("CREATE", $stmt->insert_id, $order_id, "N/A", "Payment created for order $order_id");
        showAlert("success", "Payment recorded successfully.", true);
    } else {
        showAlert("danger", "Error creating payment: " . $stmt->error, true);
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
        // Use htmlspecialchars only on text, not on numbers or JSON
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
   DELETE PAYMENT
========================= */
function handleDelete() {
    global $conn;

    $id = intval($_GET['id'] ?? 0);
    if ($id <= 0) return;

    $stmt = $conn->prepare("DELETE FROM payment WHERE payment_id=?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        writeLog("DELETE", $id, "", "", "Payment deleted");
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
        // For AJAX: return plain message only
        echo $message;
        exit();
    } else {
        // Non-AJAX fallback (keep your original behavior)
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
