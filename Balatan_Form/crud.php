<?php
session_start();
require "connect.php";
header('Content-Type: application/json');

$pdo = Database::Connection();
$func_name = $_POST['func_name'] ?? 'AddRecord';

if (function_exists($func_name)) {
    $func_name();
} else {
    echo json_encode(["status" => "error", "message" => "Invalid function"]);
}

/**
 * =========================
 * DISPLAY RECORDS
 * Admin sees all, customer sees only theirs
 * =========================
 */
function DisplayRecord() {
    global $pdo;

    if ($_SESSION['role'] === 'customer') {
        $sql = "SELECT * FROM inquiry WHERE customer_id = :customer_id ORDER BY id DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':customer_id' => $_SESSION['customer_id']]);
    } else {
        $sql = "SELECT * FROM inquiry ORDER BY id DESC";
        $stmt = $pdo->query($sql);
    }

    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
}

/**
 * =========================
 * ADD RECORD
 * Customer_id is set from session
 * =========================
 */
function AddRecord() {
    global $pdo;

    $hasSelection =
        !empty($_POST['jerseySando']) ||
        !empty($_POST['jerseyNeck']) ||
        !empty($_POST['tshirt']) ||
        !empty($_POST['poloSize']) ||
        !empty($_POST['others']) ||
        !empty($_POST['jerseyShort']) ||
        !empty($_POST['sublimationDTF']) ||
        !empty($_POST['otherService']) ||
        !empty($_POST['materialType']) ||
        !empty($_POST['colorSelection']) ||
        !empty($_POST['jerseySandoSize']) ||
        !empty($_POST['tshirtSize']) ||
        !empty($_POST['shortSize']);

    if (!$hasSelection) {
        echo json_encode(["status" => "error", "message" => "At least one dropdown must be selected."]);
        return;
    }

    $customerFile = '';
    if (!empty($_FILES['customerFile']['name']) && $_FILES['customerFile']['error'] === 0) {
        $targetDir = "uploads/";
        if (!file_exists($targetDir)) mkdir($targetDir, 0777, true);
        $customerFile = $targetDir . time() . '_' . basename($_FILES['customerFile']['name']);
        move_uploaded_file($_FILES['customerFile']['tmp_name'], $customerFile);
    }

    $colorSelection = isset($_POST['colorSelection']) ? implode(',', $_POST['colorSelection']) : '';

    $sql = "INSERT INTO inquiry (
        customer_id, customer_comment, customer_file,
        jersey_sando, jersey_neck, jersey_sando_size, longsleeves,
        tshirt, tshirt_size, polo_size, others,
        jersey_short, short_size, jogging_pants, warmer,
        sublimation_dtf, other_service, material_type, colors
    ) VALUES (
        :customer_id, :customer_comment, :customer_file,
        :jersey_sando, :jersey_neck, :jersey_sando_size, :longsleeves,
        :tshirt, :tshirt_size, :polo_size, :others,
        :jersey_short, :short_size, :jogging_pants, :warmer,
        :sublimation_dtf, :other_service, :material_type, :colors
    )";

    Database::ManageRecord($pdo, $sql, [
        ':customer_id'      => $_SESSION['customer_id'],
        ':customer_comment' => $_POST['customer_comment'] ?? '',
        ':customer_file'    => $customerFile,
        ':jersey_sando'     => $_POST['jerseySando'] ?? '',
        ':jersey_neck'      => $_POST['jerseyNeck'] ?? '',
        ':jersey_sando_size'=> $_POST['jerseySandoSize'] ?? '',
        ':longsleeves'      => $_POST['longsleeves'] ?? '',
        ':tshirt'           => $_POST['tshirt'] ?? '',
        ':tshirt_size'      => $_POST['tshirtSize'] ?? '',
        ':polo_size'        => $_POST['poloSize'] ?? '',
        ':others'           => $_POST['others'] ?? '',
        ':jersey_short'     => $_POST['jerseyShort'] ?? '',
        ':short_size'       => $_POST['shortSize'] ?? '',
        ':jogging_pants'    => $_POST['joggingPants'] ?? '',
        ':warmer'           => $_POST['warmer'] ?? '',
        ':sublimation_dtf'  => $_POST['sublimationDTF'] ?? '',
        ':other_service'    => $_POST['otherService'] ?? '',
        ':material_type'    => $_POST['materialType'] ?? '',
        ':colors'           => $colorSelection
    ]);

    echo json_encode(["status" => "success", "message" => "Record added successfully."]);
}

function GetRecord() {
    global $pdo;

    $sql = "SELECT * FROM inquiry WHERE id = :id";
    $params = [':id' => $_POST['id']];

    if ($_SESSION['role'] === 'customer') {
        $sql .= " AND customer_id = :customer_id";
        $params[':customer_id'] = $_SESSION['customer_id'];
    }

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    echo json_encode($stmt->fetch(PDO::FETCH_ASSOC));
}
