<?php
class Announcement {
    private $conn;
    private $table_name = "announcements";

    public $id;
    public $title;
    public $content;
    public $created_by;
    public $created_at;
    public $updated_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Ambil semua pengumuman, terbaru di atas
    public function read() {
        $query = "SELECT id, title, content, created_by, created_at, updated_at
                  FROM " . $this->table_name . "
                  ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Buat pengumuman baru (admin only)
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " (title, content, created_by)
                  VALUES (:title, :content, :created_by)";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':title',      $this->title);
        $stmt->bindParam(':content',    $this->content);
        $stmt->bindParam(':created_by', $this->created_by);

        return $stmt->execute();
    }
}
?>