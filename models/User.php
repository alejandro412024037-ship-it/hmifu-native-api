<?php

class User {
    // Properti untuk koneksi database dan nama tabel
    private $conn;
    private $table_name = "users";

    // Properti objek (sesuai dengan kolom di tabel database Anda)
    public $id;
    public $name;
    public $nim;
    public $email;
    public $password;
    public $role; 
    public $status; // <--- Properti status sudah ditambahkan

    // Constructor: Otomatis menerima koneksi database saat dipanggil
    public function __construct($db) {
        $this->conn = $db;
    }

    // 1. Fungsi untuk mengecek apakah Email sudah terdaftar (Dipakai saat Login & Validasi Register)
    public function emailExists() {
        $query = "SELECT id, name, nim, password, role, status FROM " . $this->table_name . " WHERE email = ? LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->email);
        $stmt->execute();

        if($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->id = $row['id'];
            $this->name = $row['name'];
            $this->nim = $row['nim'];
            $this->password = $row['password'];
            $this->role = $row['role']; 
            $this->status = $row['status'];
            return true;
        }
        return false;
    }

    // 2. Fungsi untuk Mendaftar Mahasiswa Baru (Register)
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " SET name=:name, nim=:nim, email=:email, password=:password";

        $stmt = $this->conn->prepare($query);

        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->nim = htmlspecialchars(strip_tags($this->nim));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->password = htmlspecialchars(strip_tags($this->password));

        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":nim", $this->nim);
        $stmt->bindParam(":email", $this->email);
        
        $password_hash = password_hash($this->password, PASSWORD_BCRYPT);
        $stmt->bindParam(":password", $password_hash);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // 3. Fungsi untuk Menampilkan Semua Data Mahasiswa
    public function read() {
        // Hanya ada SATU fungsi read() di sini, yang sudah dilengkapi kolom status
        $query = "SELECT id, name, nim, email, role, status FROM " . $this->table_name . " ORDER BY id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
}
?>