<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");

require_once '../config/database.php';
require_once '../config/auth.php';
require_once '../controllers/EventController.php';

$eventCtrl = new EventController();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    requireAuth($koneksi_alejandrojulian);
    $eventCtrl->history();

} else {
    http_response_code(405);
    echo json_encode(["status" => "error", "message" => "Metode tidak diizinkan. Gunakan GET."]);
}
?>
