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
}
?>