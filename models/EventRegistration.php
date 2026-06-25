<?php
class EventRegistration {
    private $conn;
    private $table_name = "event_registrations";

    public $id;
    public $event_id;
    public $user_id;
    public $created_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Daftarkan user ke event (cek duplikat terlebih dahulu)
    public function create() {
        // Cek apakah user sudah terdaftar di event ini
        $check = $this->conn->prepare(
            "SELECT id FROM " . $this->table_name . " WHERE event_id = ? AND user_id = ? LIMIT 1"
        );
        $check->bindParam(1, $this->event_id);
        $check->bindParam(2, $this->user_id);
        $check->execute();

        if ($check->rowCount() > 0) {
            return 'duplicate';
        }

        $query = "INSERT INTO " . $this->table_name . " (event_id, user_id) VALUES (?, ?)";
        $stmt  = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->event_id);
        $stmt->bindParam(2, $this->user_id);

        return $stmt->execute() ? 'created' : 'error';
    }

    // Ambil semua peserta yang terdaftar di suatu event (join ke tabel users)
    public function readByEvent() {
        $query = "SELECT u.id, u.name, u.nim, u.email, er.created_at AS registered_at
                  FROM " . $this->table_name . " er
                  JOIN users u ON er.user_id = u.id
                  WHERE er.event_id = ?
                  ORDER BY er.created_at ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->event_id);
        $stmt->execute();
        return $stmt;
    }
}
?>