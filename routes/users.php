<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST"); 

require_once '../controllers/AuthController.php';

$auth = new AuthController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Menggunakan fungsi register dari AuthController
    $auth->register();
} else {
    http_response_code(405);
    echo json_encode(["success" => false, "message" => "Metode tidak diizinkan. Gunakan POST."]);
}
?>