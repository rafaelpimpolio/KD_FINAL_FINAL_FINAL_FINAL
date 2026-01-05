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


function DisplayRecord()
{
    global $pdo;

    $customer_id = $_SESSION['customer_id'];

    $sql = "SELECT * FROM inquiry
            WHERE customer_id = :customer_id
            ORDER BY id DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([':customer_id' => $customer_id]);

    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
}



function AddRecord()
{
    global $pdo;

    $dropdownFields = [
        'jerseySando',
        'jerseyNeck',
        'tshirt',
        'poloSize',
        'others',
        'jerseyShort',
        'sublimationDTF',
        'otherService'
    ];

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
        echo json_encode([
            "status" => "error",
            "message" => "At least one dropdown must be selected."
        ]);
        return;
    }

    $customerFile = '';
    if (!empty($_FILES['customerFile']['name']) && $_FILES['customerFile']['error'] === 0) {
        $targetDir = "uploads/";
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0777, true);
        }
        $customerFile = $targetDir . time() . '_' . basename($_FILES['customerFile']['name']);
        move_uploaded_file($_FILES['customerFile']['tmp_name'], $customerFile);
    }

    $colorSelection = isset($_POST['colorSelection'])
        ? implode(',', $_POST['colorSelection'])
        : '';

    $materialType = $_POST['materialType'] ?? '';

$customer_id = $_SESSION['customer_id'];

$sql = "INSERT INTO inquiry (
    customer_id,
    customer_comment,
    customer_file,
    jersey_sando,
    jersey_neck,
    jersey_sando_size,
    longsleeves,
    tshirt,
    tshirt_size,
    polo_size,
    others,
    jersey_short,
    short_size,
    jogging_pants,
    warmer,
    sublimation_dtf,
    other_service,
    material_type,
    colors
) VALUES (
    :customer_id,
    :customer_comment,
    :customer_file,
    :jersey_sando,
    :jersey_neck,
    :jersey_sando_size,
    :longsleeves,
    :tshirt,
    :tshirt_size,
    :polo_size,
    :others,
    :jersey_short,
    :short_size,
    :jogging_pants,
    :warmer,
    :sublimation_dtf,
    :other_service,
    :material_type,
    :colors
)";


Database::ManageRecord($pdo, $sql, [
    ':customer_id'        => $customer_id,
    ':customer_comment'   => $_POST['customer_comment'] ?? '',
    ':customer_file'      => $customerFile,

    ':jersey_sando'       => $_POST['jerseySando'] ?? '',
    ':jersey_neck'        => $_POST['jerseyNeck'] ?? '',
    ':jersey_sando_size'  => $_POST['jerseySandoSize'] ?? '',
    ':longsleeves'        => $_POST['longsleeves'] ?? '',
    ':tshirt'             => $_POST['tshirt'] ?? '',
    ':tshirt_size'        => $_POST['tshirtSize'] ?? '',
    ':polo_size'          => $_POST['poloSize'] ?? '',
    ':others'             => $_POST['others'] ?? '',

    ':jersey_short'       => $_POST['jerseyShort'] ?? '',
    ':short_size'         => $_POST['shortSize'] ?? '',
    ':jogging_pants'      => $_POST['joggingPants'] ?? '',

    ':warmer'             => $_POST['warmer'] ?? '',
    ':sublimation_dtf'    => $_POST['sublimationDTF'] ?? '',
    ':other_service'      => $_POST['otherService'] ?? '',

    ':material_type'      => $_POST['materialType'] ?? '',
    ':colors'             => isset($_POST['colorSelection'])
        ? implode(',', $_POST['colorSelection'])
        : ''
]);



    echo json_encode(["status" => "success", "message" => "Record added successfully."]);
}


function UpdateRecord()
{
    global $pdo;

    $customer_id = $_SESSION['customer_id'];

    $customerFile = '';
    if (!empty($_FILES['customerFile']['name']) && $_FILES['customerFile']['error'] === 0) {
        $targetDir = "uploads/";
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0777, true);
        }
        $customerFile = $targetDir . time() . '_' . basename($_FILES['customerFile']['name']);
        move_uploaded_file($_FILES['customerFile']['tmp_name'], $customerFile);
    }

    $sql = "UPDATE inquiry SET
        customer_comment = :customer_comment,
        customer_file = IF(:customer_file = '', customer_file, :customer_file),
        jersey_sando = :jersey_sando,
        jersey_neck = :jersey_neck,
        jersey_sando_size = :jersey_sando_size,
        longsleeves = :longsleeves,
        tshirt = :tshirt,
        tshirt_size = :tshirt_size,
        polo_size = :polo_size,
        others = :others,
        jersey_short = :jersey_short,
        short_size = :short_size,
        jogging_pants = :jogging_pants,
        warmer = :warmer,
        sublimation_dtf = :sublimation_dtf,
        other_service = :other_service,
        material_type = :material_type,
        colors = :colors
    WHERE id = :id AND customer_id = :customer_id";

    Database::ManageRecord($pdo, $sql, [
        ':id' => $_POST['id'],
        ':customer_id' => $customer_id,
        ':customer_comment' => $_POST['customer_comment'] ?? '',
        ':customer_file' => $customerFile,

        ':jersey_sando' => $_POST['jerseySando'] ?? '',
        ':jersey_neck' => $_POST['jerseyNeck'] ?? '',
        ':jersey_sando_size' => $_POST['jerseySandoSize'] ?? '',
        ':longsleeves' => $_POST['longsleeves'] ?? '',
        ':tshirt' => $_POST['tshirt'] ?? '',
        ':tshirt_size' => $_POST['tshirtSize'] ?? '',
        ':polo_size' => $_POST['poloSize'] ?? '',
        ':others' => $_POST['others'] ?? '',
        ':jersey_short' => $_POST['jerseyShort'] ?? '',
        ':short_size' => $_POST['shortSize'] ?? '',
        ':jogging_pants' => $_POST['joggingPants'] ?? '',
        ':warmer' => $_POST['warmer'] ?? '',
        ':sublimation_dtf' => $_POST['sublimationDTF'] ?? '',
        ':other_service' => $_POST['otherService'] ?? '',
        ':material_type' => $_POST['materialType'] ?? '',
        ':colors' => isset($_POST['colorSelection'])
            ? implode(',', $_POST['colorSelection'])
            : ''
    ]);

    echo json_encode(["status" => "success", "message" => "Record updated"]);
}



function DeleteRecord()
{
    global $pdo;

    $customer_id = $_SESSION['customer_id'];

    Database::ManageRecord(
        $pdo,
        "DELETE FROM inquiry WHERE id = :id AND customer_id = :customer_id",
        [
            ':id' => $_POST['id'],
            ':customer_id' => $customer_id
        ]
    );

    echo json_encode(["status" => "success", "message" => "Record deleted"]);
}



function GetRecord()
{
    global $pdo;

    $stmt = $pdo->prepare("SELECT * FROM inquiry WHERE id = :id");
    $stmt->execute([':id' => $_POST['id']]);
    echo json_encode($stmt->fetch(PDO::FETCH_ASSOC));
}
