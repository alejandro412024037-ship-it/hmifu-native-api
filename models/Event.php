<?php
class Event {
    private $conn;
    private $table_name = "events"; // Tabel events sudah ada dari XAMPP Anda sebelumnya

    public $id;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Fungsi untuk menghapus acara
    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        
        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(1, $this->id);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }
    // Fungsi untuk mengambil histori acara (acara yang sudah lewat)
    public function getHistory() {
        // Logika: Ambil event dimana event_date lebih kecil (<) dari waktu sekarang (NOW)
        $query = "SELECT id, title, description, event_date, location FROM " . $this->table_name . " WHERE event_date < NOW() ORDER BY event_date DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt;
    }
}
?>