<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");

require_once '../config/database.php';
require_once '../config/auth.php';
require_once '../controllers/EventRegistrationController.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    requireAdmin($koneksi_alejandrojulian);
    $ctrl = new EventRegistrationController();
    $ctrl->getRegistrants();

} else {
    http_response_code(405);
    echo json_encode(["status" => "error", "message" => "Metode tidak diizinkan. Gunakan GET."]);
}
?>
