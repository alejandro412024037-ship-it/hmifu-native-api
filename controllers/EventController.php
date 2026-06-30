<?php
class EventController {

    // GET — Ambil semua event
    public function getAllEvents() {
        require '../config/database.php';
        require_once '../models/Event.php';

        $event = new Event($koneksi_alejandrojulian);
        $stmt  = $event->read();

        $events_arr = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $events_arr[] = [
                "id"          => $row['id'],
                "title"       => $row['title'],
                "description" => $row['description'],
                "location"    => $row['location'],
                "event_date"  => $row['event_date'],
                "created_by"  => $row['created_by'],
                "created_at"  => $row['created_at'],
            ];
        }

        http_response_code(200);
        echo json_encode(["status" => "success", "data" => $events_arr]);
    }

    // GET — Ambil satu event berdasarkan ID
    public function getEvent() {
        require '../config/database.php';
        require_once '../models/Event.php';

        $id = $_GET['id'] ?? null;

        if (empty($id)) {
            http_response_code(400);
            echo json_encode(["status" => "error", "message" => "Parameter id wajib diisi."]);
            return;
        }

        $event     = new Event($koneksi_alejandrojulian);
        $event->id = $id;

        if ($event->readOne()) {
            http_response_code(200);
            echo json_encode([
                "status" => "success",
                "data"   => [
                    "id"          => $event->id,
                    "title"       => $event->title,
                    "description" => $event->description,
                    "location"    => $event->location,
                    "event_date"  => $event->event_date,
                    "created_by"  => $event->created_by,
                    "created_at"  => $event->created_at,
                ]
            ]);
        } else {
            http_response_code(404);
            echo json_encode(["status" => "error", "message" => "Event tidak ditemukan."]);
        }
    }

    // POST — Buat event baru (admin only)
    public function createEvent($admin_user_id) {
        require '../config/database.php';
        require_once '../models/Event.php';

        $data = json_decode(file_get_contents("php://input"));

        if (empty($data->title) || empty($data->event_date)) {
            http_response_code(400);
            echo json_encode(["status" => "error", "message" => "Field title dan event_date wajib diisi."]);
            return;
        }

        $event              = new Event($koneksi_alejandrojulian);
        $event->title       = $data->title;
        $event->description = $data->description ?? '';
        $event->location    = $data->location ?? '';
        $event->event_date  = $data->event_date;
        $event->created_by  = $admin_user_id;

        if ($event->create()) {
            http_response_code(201);
            echo json_encode(["status" => "success", "message" => "Event berhasil dibuat."]);
        } else {
            http_response_code(503);
            echo json_encode(["status" => "error", "message" => "Gagal menyimpan event ke database."]);
        }
    }

    // POST — Hapus event (admin only)
    public function deleteEvent($admin_user_id) {
        require '../config/database.php';
        require_once '../models/Event.php';

        $data = json_decode(file_get_contents("php://input"));

        if (empty($data->event_id)) {
            http_response_code(400);
            echo json_encode(["status" => "error", "message" => "Field event_id wajib diisi."]);
            return;
        }

        $event     = new Event($koneksi_alejandrojulian);
        $event->id = $data->event_id;

        if ($event->delete()) {
            http_response_code(200);
            echo json_encode(["status" => "success", "message" => "Event berhasil dihapus."]);
        } else {
            http_response_code(503);
            echo json_encode(["status" => "error", "message" => "Gagal menghapus event di database."]);
        }
    }

    // GET — Ambil histori event (acara yang sudah lewat)
    public function history() {
        require '../config/database.php';
        require_once '../models/Event.php';

        $event = new Event($koneksi_alejandrojulian);
        $stmt = $event->getHistory();
        $jumlah_data = $stmt->rowCount();

        if ($jumlah_data > 0) {
            $events_arr = array();

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $event_item = array(
                    "id"          => $row['id'],
                    "title"       => $row['title'],
                    "description" => $row['description'],
                    "event_date"  => $row['event_date'],
                    "location"    => $row['location']
                );
                array_push($events_arr, $event_item);
            }

            http_response_code(200);
            echo json_encode([
                "success" => true,
                "message" => "Berhasil mengambil data histori acara.",
                "data"    => $events_arr
            ]);
        } else {
            http_response_code(404);
            echo json_encode(["success" => false, "message" => "Belum ada histori acara yang selesai."]);
        }
    }
}
?>
