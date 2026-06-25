<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");

require_once '../config/database.php';
require_once '../config/auth.php';
require_once '../controllers/EventRegistrationController.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $user_id = validateToken($koneksi_alejandrojulian);

    // Hanya admin yang bisa melihat daftar peserta
    $query = "SELECT role FROM users WHERE id = ? LIMIT 1";
    $stmt  = $koneksi_alejandrojulian->prepare($query);
    $stmt->bindParam(1, $user_id);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row || $row['role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(["status" => "error", "message" => "Akses ditolak: Anda bukan Admin."]);
        exit();
    }

    $ctrl = new EventRegistrationController();
    $ctrl->getRegistrants();
} else {
    http_response_code(405);
    echo json_encode(["status" => "error", "message" => "Metode tidak diizinkan. Gunakan GET."]);
}
?>