<?php
header("Content-Type: application/json; charset=UTF-8");

echo json_encode([
    "success" => true,
    "message" => "Selamat datang di API Native HMIF-U!",
    "status" => "Server Berjalan Normal"
]);
?>