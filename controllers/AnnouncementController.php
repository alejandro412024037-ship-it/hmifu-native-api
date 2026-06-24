<?php
class AnnouncementController {

    // GET — Ambil semua pengumuman
    public function getAllAnnouncements() {
        require '../config/database.php';
        require_once '../models/Announcement.php';

        $announcement = new Announcement($koneksi_alejandrojulian);
        $stmt         = $announcement->read();

        $announcements_arr = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $announcements_arr[] = [
                "id"         => $row['id'],
                "title"      => $row['title'],
                "content"    => $row['content'],
                "created_by" => $row['created_by'],
                "created_at" => $row['created_at'],
            ];
        }

        http_response_code(200);
        echo json_encode(["status" => "success", "data" => $announcements_arr]);
    }

    // POST — Buat pengumuman baru (admin only)
    public function createAnnouncement($admin_user_id) {
        require '../config/database.php';
        require_once '../models/Announcement.php';

        $data = json_decode(file_get_contents("php://input"));

        if (empty($data->title) || empty($data->content)) {
            http_response_code(400);
            echo json_encode(["status" => "error", "message" => "Field title dan content wajib diisi."]);
            return;
        }

        $announcement             = new Announcement($koneksi_alejandrojulian);
        $announcement->title      = $data->title;
        $announcement->content    = $data->content;
        $announcement->created_by = $admin_user_id;

        if ($announcement->create()) {
            http_response_code(201);
            echo json_encode(["status" => "success", "message" => "Pengumuman berhasil dibuat."]);
        } else {
            http_response_code(503);
            echo json_encode(["status" => "error", "message" => "Gagal menyimpan pengumuman."]);
        }
    }
}
?>