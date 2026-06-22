<?php

class AuthController {
    
    // 1. Fungsi untuk Registrasi Mahasiswa Baru
    public function register() {
        // Memanggil koneksi database dan Model User
        require '../config/database.php';
        require_once '../models/User.php';

        $user = new User($koneksi_alejandrojulian);

        // Menangkap data JSON yang dikirim dari Thunder Client/Aplikasi
        $data = json_decode(file_get_contents("php://input"));

        // Memastikan data yang dikirim tidak kosong
        if (!empty($data->name) && !empty($data->nim) && !empty($data->email) && !empty($data->password)) {
            
            // Masukkan data JSON ke dalam properti objek Model User
            $user->name = $data->name;
            $user->nim = $data->nim;
            $user->email = $data->email;
            $user->password = $data->password;

            // Cek apakah email sudah ada di database
            if ($user->emailExists()) {
                http_response_code(400); // 400 = Bad Request
                echo json_encode(["success" => false, "message" => "Gagal: Email sudah terdaftar!"]);
                return;
            }

            // Eksekusi fungsi create() di User.php untuk menyimpan ke database
            if ($user->create()) {
                http_response_code(201); // 201 = Created
                echo json_encode(["success" => true, "message" => "Berhasil: Akun mahasiswa HMIF-U berhasil dibuat!"]);
            } else {
                http_response_code(503); // 503 = Service Unavailable
                echo json_encode(["success" => false, "message" => "Server Error: Gagal membuat akun."]);
            }
        } else {
            // Jika ada kolom yang kosong
            http_response_code(400);
            echo json_encode(["success" => false, "message" => "Gagal: Data tidak lengkap (name, nim, email, password wajib diisi)."]);
        }
    }

    // 2. Fungsi untuk Login Mahasiswa
    public function login() {
        require '../config/database.php';
        require_once '../models/User.php';

        $user = new User($koneksi_alejandrojulian);
        $data = json_decode(file_get_contents("php://input"));

        if (!empty($data->email) && !empty($data->password)) {
            
            $user->email = $data->email;
            // Cek ke database apakah email ini ada?
            $email_ada = $user->emailExists();

            // Jika email ada DAN password yang diketik cocok dengan hash di database
            if ($email_ada && password_verify($data->password, $user->password)) {
                
                http_response_code(200); // 200 = OK
                echo json_encode([
                    "success" => true,
                    "message" => "Login Berhasil!",
                    "data" => [
                        "id"    => $user->id,
                        "name"  => $user->name,
                        "nim"   => $user->nim,
                        "email" => $user->email,
                        "role"  => $user->role  // <--- Data role sekarang sudah masuk ke dalam fungsi login dengan aman
                    ]
                ]);
            } else {
                http_response_code(401); // 401 = Unauthorized
                echo json_encode(["success" => false, "message" => "Login Gagal: Email atau Password salah!"]);
            }
        } else {
            http_response_code(400);
            echo json_encode(["success" => false, "message" => "Gagal: Email dan Password wajib diisi."]);
        }
    }
}
?>