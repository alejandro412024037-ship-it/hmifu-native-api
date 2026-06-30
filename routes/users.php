<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET");

require_once '../config/database.php';
require_once '../config/auth.php';
require_once '../controllers/AuthController.php';
require_once '../controllers/UserController.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $auth = new AuthController();
    $auth->register();

} else if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    requireAdmin($koneksi_alejandrojulian);
    $userCtrl = new UserController();
    $userCtrl->getAllUsers();

} else {
    http_response_code(405);
    echo json_encode(["status" => "error", "message" => "Metode tidak diizinkan. Gunakan POST atau GET."]);
}
?>
