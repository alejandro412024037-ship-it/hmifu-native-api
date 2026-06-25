<?php
class Event {
    private $conn;
    private $table_name = "events";

    public $id;
    public $title;
    public $description;
    public $location;
    public $event_date;
    public $created_by;
    public $created_at;
    public $updated_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Ambil semua event
    public function read() {
        $query = "SELECT id, title, description, location, event_date, created_by, created_at, updated_at
                  FROM " . $this->table_name . "
                  ORDER BY event_date ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Ambil satu event berdasarkan ID
    public function readOne() {
        $query = "SELECT id, title, description, location, event_date, created_by, created_at, updated_at
                  FROM " . $this->table_name . "
                  WHERE id = ?
                  LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->title       = $row['title'];
            $this->description = $row['description'];
            $this->location    = $row['location'];
            $this->event_date  = $row['event_date'];
            $this->created_by  = $row['created_by'];
            $this->created_at  = $row['created_at'];
            $this->updated_at  = $row['updated_at'];
            return true;
        }
        return false;
    }

    // Buat event baru (admin only)
    public function create() {
        $query = "INSERT INTO " . $this->table_name . "
                  (title, description, location, event_date, created_by)
                  VALUES (:title, :description, :location, :event_date, :created_by)";

        $stmt = $this->conn->prepare($query);

        $this->title       = htmlspecialchars(strip_tags($this->title));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->location    = htmlspecialchars(strip_tags($this->location));
        $this->event_date  = htmlspecialchars(strip_tags($this->event_date));

        $stmt->bindParam(':title',       $this->title);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':location',    $this->location);
        $stmt->bindParam(':event_date',  $this->event_date);
        $stmt->bindParam(':created_by',  $this->created_by);

        return $stmt->execute();
    }

    // Hapus event berdasarkan ID
    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(1, $this->id);
        return $stmt->execute();
    }
}
?>