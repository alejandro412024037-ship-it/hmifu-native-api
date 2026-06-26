<?php

class UserController {
    
    // Fungsi untuk mengambil semua data mahasiswa
    public function getAllUsers() {
        require '../config/database.php';
        require_once '../models/User.php';

        $user = new User($koneksi_alejandrojulian);
        $stmt = $user->read();
        $jumlah_data = $stmt->rowCount();

        if ($jumlah_data > 0) {
            $users_arr = array();
            
            // Looping untuk memasukkan semua baris data ke dalam array
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $user_item = array(
                    "id" => $row['id'],
                    "name" => $row['name'],
                    "nim" => $row['nim'],
                    "email" => $row['email'],
                    "role" => $row['role'], // <--- Koma sudah ditambahkan di sini
                    "status" => $row['status'] // <--- Diubah menjadi 'status' sesuai database
                );
                array_push($users_arr, $user_item);
            }

            http_response_code(200);
            echo json_encode([
                "success" => true,
                "message" => "Berhasil mengambil $jumlah_data data mahasiswa.",
                "data" => $users_arr
            ]);
        } else {
            http_response_code(404);
            echo json_encode(["success" => false, "message" => "Belum ada data mahasiswa ditemukan."]);
        }
    }
}
?>