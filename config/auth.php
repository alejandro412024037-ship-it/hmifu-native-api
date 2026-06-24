<?php

/**
 * Validates the Bearer token from the Authorization header.
 * Returns the authenticated user_id on success, or sends a 401 response and exits.
 */
function validateToken($conn) {
    $headers = getallheaders();
    $authHeader = $headers['Authorization'] ?? $headers['authorization'] ?? '';

    if (empty($authHeader) || !str_starts_with($authHeader, 'Bearer ')) {
        http_response_code(401);
        echo json_encode(["status" => "error", "message" => "Token tidak ditemukan. Harap login terlebih dahulu."]);
        exit();
    }

    $token = substr($authHeader, 7);

    $query = "SELECT user_id FROM personal_access_tokens WHERE token = ? LIMIT 1";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(1, $token);
    $stmt->execute();

    if ($stmt->rowCount() === 0) {
        http_response_code(401);
        echo json_encode(["status" => "error", "message" => "Token tidak valid atau sudah kedaluwarsa."]);
        exit();
    }

    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row['user_id'];
}
?>