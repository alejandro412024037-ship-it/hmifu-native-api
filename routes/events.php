<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET"); 

require_once '../controllers/EventController.php';

$eventCtrl = new EventController();

// Cek metodenya, jika GET maka tarik semua event
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $eventCtrl->getAll();
} else {
    http_response_code(405);
    echo json_encode(["success" => false, "message" => "Metode tidak diizinkan. Gunakan GET."]);
}
?>