<?php

class AuthController {

    // 1. Fungsi untuk Registrasi Mahasiswa Baru
    public function register() {
        require '../config/database.php';
        require_once '../models/User.php';

        $user = new User($koneksi_alejandrojulian);
        $data = json_decode(file_get_contents("php://input"));

        if (!empty($data->name) && !empty($data->nim) && !empty($data->email) && !empty($data->password)) {

            $user->name = $data->name;
            $user->nim = $data->nim;
            $user->email = $data->email;
            $user->password = $data->password;

            if ($user->emailExists()) {
                http_response_code(400);
                echo json_encode(["status" => "error", "message" => "Email sudah terdaftar."]);
                return;
            }

            if ($user->create()) {
                http_response_code(201);
                echo json_encode(["status" => "success", "message" => "Akun mahasiswa HMIF-U berhasil dibuat."]);
            } else {
                http_response_code(503);
                echo json_encode(["status" => "error", "message" => "Server Error: Gagal membuat akun."]);
            }
        } else {
            http_response_code(400);
            echo json_encode(["status" => "error", "message" => "Data tidak lengkap (name, nim, email, password wajib diisi)."]);
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
            $email_ada = $user->emailExists();

            if ($email_ada && password_verify($data->password, $user->password)) {

                $token  = bin2hex(random_bytes(32));
                $hashed = hash('sha256', $token);
                $stmt = $koneksi_alejandrojulian->prepare(
                    "INSERT INTO personal_access_tokens (user_id, token) VALUES (?, ?)"
                );
                $stmt->bindParam(1, $user->id);
                $stmt->bindParam(2, $hashed);
                $stmt->execute();

                http_response_code(200);
                echo json_encode([
                    "status" => "success",
                    "token"  => $token,
                    "data" => [
                        "id"    => $user->id,
                        "name"  => $user->name,
                        "nim"   => $user->nim,
                        "email" => $user->email,
                        "role"  => $user->role
                    ]
                ]);
            } else {
                http_response_code(401);
                echo json_encode(["status" => "error", "message" => "Email atau Password salah."]);
            }
        } else {
            http_response_code(400);
            echo json_encode(["status" => "error", "message" => "Email dan Password wajib diisi."]);
        }
    }

    // 3. Fungsi untuk Logout (hapus token dari database)
    public function logout() {
        require '../config/database.php';

        $headers = getallheaders();
        $authHeader = $headers['Authorization'] ?? $headers['authorization'] ?? '';

        if (empty($authHeader) || !str_starts_with($authHeader, 'Bearer ')) {
            http_response_code(401);
            echo json_encode(["status" => "error", "message" => "Token tidak ditemukan."]);
            return;
        }

        $token = hash('sha256', substr($authHeader, 7));

        $stmt = $koneksi_alejandrojulian->prepare(
            "DELETE FROM personal_access_tokens WHERE token = ?"
        );
        $stmt->bindParam(1, $token);
        $stmt->execute();

        http_response_code(200);
        echo json_encode(["status" => "success", "message" => "Logout berhasil."]);
    }
}
?>