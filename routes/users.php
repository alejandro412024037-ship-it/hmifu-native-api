<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET"); 

require_once '../controllers/AuthController.php';
require_once '../controllers/UserController.php';

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'POST') {
    // Arahkan ke fitur Registrasi di AuthController
    $auth = new AuthController();
    $auth->register();
} elseif ($method === 'GET') {
    // Arahkan ke fitur Lihat Semua Mahasiswa di UserController
    $userCtrl = new UserController();
    $userCtrl->getAllUsers();
} else {
    http_response_code(405);
    echo json_encode(["success" => false, "message" => "Metode tidak diizinkan. Gunakan POST atau GET."]);
}
?>