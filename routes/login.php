<?php
// Mengatur header agar API bisa diakses dari aplikasi luar dan membalas dalam format JSON
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST"); // Hanya izinkan metode POST

// Panggil Controller-nya
require_once '../controllers/AuthController.php';

$auth = new AuthController();

// Pastikan yang nembak API menggunakan metode POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $auth->login();
} else {
    // Jika ditembak pakai GET, tolak!
    http_response_code(405);
    echo json_encode(["success" => false, "message" => "Metode tidak diizinkan. Gunakan POST."]);
}
?>