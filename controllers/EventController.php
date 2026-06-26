<?php
class EventController {
    // Fungsi untuk menampilkan semua event
    public function getAll() {
        require '../config/database.php';
        require_once '../models/Event.php';

        $event = new Event($koneksi_alejandrojulian);
        $stmt = $event->getAllEvents();
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
                "message" => "Berhasil mengambil seluruh data acara.",
                "data" => $events_arr
            ]);
        } else {
            http_response_code(404);
            echo json_encode(["success" => false, "message" => "Belum ada acara yang terdaftar."]);
        }
    }
    // Fungsi untuk menampilkan histori event
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