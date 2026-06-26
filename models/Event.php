<?php
class Event {
    private $conn;
    private $table_name = "events"; // Tabel events sudah ada dari XAMPP Anda sebelumnya

    public $id;

    public function __construct($db) {
        $this->conn = $db;
    }
    // Fungsi untuk mengambil histori acara (acara yang sudah lewat)
    public function getHistory() {
        // Logika: Ambil event dimana event_date lebih kecil (<) dari waktu sekarang (NOW)
        $query = "SELECT id, title, description, event_date, location FROM " . $this->table_name . " WHERE event_date < NOW() ORDER BY event_date DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt;
    }
    // Fungsi untuk mengambil SEMUA acara (baik yang lalu maupun masa depan)
    public function getAllEvents() {
        // Logika: Ambil semua data dari tabel events dan urutkan dari yang paling baru
        $query = "SELECT id, title, description, event_date, location FROM " . $this->table_name . " ORDER BY event_date DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt;
    }
}
?>