<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
// Izinkan metode POST (Register) dan GET (Read Semua User)
header("Access-Control-Allow-Methods: POST, GET"); 

require_once '../controllers/AuthController.php';
require_once '../controllers/UserController.php';

$auth = new AuthController();
$userCtrl = new UserController();

// Cek metode apa yang digunakan oleh Thunder Client
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Jika POST, jalankan fitur Register
    $auth->register();
} else if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Jika GET, tampilkan daftar semua mahasiswa
    $userCtrl->getAllUsers();
} else {
    http_response_code(405);
    echo json_encode(["success" => false, "message" => "Metode tidak diizinkan. Gunakan POST atau GET."]);
}
?>