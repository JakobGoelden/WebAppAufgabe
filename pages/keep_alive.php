<?php
require_once __DIR__ . '/../includes/init.php';

header('Content-Type: application/json');

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    $_SESSION['last_activity'] = time();
    echo json_encode(['status' => 'ok']);
} else {
    http_response_code(401);
    echo json_encode(['status' => 'not_logged_in']);
}
