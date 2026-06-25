<?php

$host_alejandrojulian = "127.0.0.1:3307";
$user_alejandrojulian = "root";
$pass_alejandrojulian = "";
$db_alejandrojulian   = "hmif_ukrida_db"; // Sesuaikan dengan nama database Anda di phpMyAdmin

try {
    $koneksi_alejandrojulian = new PDO("mysql:host=$host_alejandrojulian;dbname=$db_alejandrojulian", $user_alejandrojulian, $pass_alejandrojulian);
    // Mengatur error mode PDO ke exception
    $koneksi_alejandrojulian->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "Koneksi ke database sukses!"; // Buka komentar ini jika ingin tes koneksi
} catch(PDOException $e) {
    echo "Koneksi gagal: " . $e->getMessage();
}

?>