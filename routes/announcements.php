<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST");

require_once '../config/database.php';
require_once '../config/auth.php';
require_once '../controllers/AnnouncementController.php';

$ctrl = new AnnouncementController();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    requireAuth($koneksi_alejandrojulian);
    $ctrl->getAllAnnouncements();

} else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = requireAdmin($koneksi_alejandrojulian);
    $ctrl->createAnnouncement($user['id']);

} else {
    http_response_code(405);
    echo json_encode(["status" => "error", "message" => "Metode tidak diizinkan. Gunakan GET atau POST."]);
}
?>
