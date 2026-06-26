<?php
class EventController {
    
    // 1. Fungsi untuk menghapus event (Dengan Keamanan RBAC)
    public function deleteEvent() {
        require '../config/database.php';
        require_once '../models/Event.php';

        $data = json_decode(file_get_contents("php://input"));

        // Kita butuh ID event yang mau dihapus, dan ID user yang sedang memerintah
        if (!empty($data->event_id) && !empty($data->user_id)) {
            
            // Cek ke database apakah user_id ini memiliki role 'admin'
            $query = "SELECT role FROM users WHERE id = ? LIMIT 0,1";
            $stmt = $koneksi_alejandrojulian->prepare($query);
            $stmt->bindParam(1, $data->user_id);
            $stmt->execute();
            
            if($stmt->rowCount() > 0) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                
                // Pintu Keamanan: Jika dia admin, izinkan hapus
                if ($row['role'] === 'admin') {
                    $event = new Event($koneksi_alejandrojulian);
                    $event->id = $data->event_id;
                    
                    if ($event->delete()) {
                        http_response_code(200);
                        echo json_encode(["success" => true, "message" => "Berhasil: Acara telah dihapus oleh Admin."]);
                    } else {
                        http_response_code(503);
                        echo json_encode(["success" => false, "message" => "Gagal menghapus acara di database."]);
                    }
                } else {
                    // Jika mahasiswa biasa yang mencoba menghapus
                    http_response_code(403); // 403 = Forbidden
                    echo json_encode(["success" => false, "message" => "Akses Ditolak: Anda bukan Admin!"]);
                }
            } else {
                http_response_code(404);
                echo json_encode(["success" => false, "message" => "User tidak ditemukan."]);
            }
        } else {
            http_response_code(400);
            echo json_encode(["success" => false, "message" => "Data tidak lengkap (butuh event_id dan user_id)."]);
        }
    }

    // 2. Fungsi untuk menampilkan histori event
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
                    "id" => $row['id'],
                    "title" => $row['title'],
                    "description" => $row['description'],
                    "event_date" => $row['event_date'],
                    "location" => $row['location']
                );
                array_push($events_arr, $event_item);
            }

            http_response_code(200);
            echo json_encode([
                "success" => true, 
                "message" => "Berhasil mengambil data histori acara.",
                "data" => $events_arr
            ]);
        } else {
            http_response_code(404);
            echo json_encode(["success" => false, "message" => "Belum ada histori acara yang selesai."]);
        }
    }
} // <--- Tanda penutup class-nya yang benar diletakkan di paling bawah ini
?>