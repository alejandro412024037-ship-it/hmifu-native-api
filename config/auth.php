<?php

function requireAuth($conn) {
    $headers = getallheaders();
    $authHeader = $headers['Authorization'] ?? $headers['authorization'] ?? '';

    if (empty($authHeader) || !str_starts_with($authHeader, 'Bearer ')) {
        http_response_code(401);
        echo json_encode(["status" => "error", "message" => "Token tidak ditemukan. Harap login terlebih dahulu."]);
        exit();
    }

    $token = hash('sha256', substr($authHeader, 7));

    $query = "SELECT u.id, u.name, u.role
              FROM personal_access_tokens t
              JOIN users u ON t.user_id = u.id
              WHERE t.token = ? LIMIT 1";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(1, $token);
    $stmt->execute();

    if ($stmt->rowCount() === 0) {
        http_response_code(401);
        echo json_encode(["status" => "error", "message" => "Token tidak valid atau sudah kedaluwarsa."]);
        exit();
    }

    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function requireAdmin($conn) {
    $user = requireAuth($conn);
    if ($user['role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(["status" => "error", "message" => "Akses ditolak: Anda bukan Admin."]);
        exit();
    }
    return $user;
}
?>
