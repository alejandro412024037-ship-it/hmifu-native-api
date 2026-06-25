<?php
class EventRegistrationController {

    // POST — Daftarkan user yang sedang login ke sebuah event
    public function register($user_id) {
        require '../config/database.php';
        require_once '../models/EventRegistration.php';

        $data = json_decode(file_get_contents("php://input"));

        if (empty($data->event_id)) {
            http_response_code(400);
            echo json_encode(["status" => "error", "message" => "Field event_id wajib diisi."]);
            return;
        }

        $reg           = new EventRegistration($koneksi_alejandrojulian);
        $reg->event_id = $data->event_id;
        $reg->user_id  = $user_id;

        $result = $reg->create();

        if ($result === 'created') {
            http_response_code(201);
            echo json_encode(["status" => "success", "message" => "Berhasil mendaftar ke event."]);
        } else if ($result === 'duplicate') {
            http_response_code(409);
            echo json_encode(["status" => "error", "message" => "Anda sudah terdaftar di event ini."]);
        } else {
            http_response_code(503);
            echo json_encode(["status" => "error", "message" => "Gagal mendaftar ke event."]);
        }
    }

    // GET — Ambil daftar peserta suatu event (admin only)
    public function getRegistrants() {
        require '../config/database.php';
        require_once '../models/EventRegistration.php';

        $event_id = $_GET['event_id'] ?? null;

        if (empty($event_id)) {
            http_response_code(400);
            echo json_encode(["status" => "error", "message" => "Parameter event_id wajib diisi."]);
            return;
        }

        $reg           = new EventRegistration($koneksi_alejandrojulian);
        $reg->event_id = $event_id;
        $stmt          = $reg->readByEvent();

        $registrants = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $registrants[] = [
                "id"            => $row['id'],
                "name"          => $row['name'],
                "nim"           => $row['nim'],
                "email"         => $row['email'],
                "registered_at" => $row['registered_at'],
            ];
        }

        http_response_code(200);
        echo json_encode(["status" => "success", "data" => $registrants]);
    }
}
?>