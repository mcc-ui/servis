<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

$raw = file_get_contents('php://input');
$decoded = json_decode($raw);

if ($decoded === null) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid JSON: ' . json_last_error_msg()]);
    exit;
}

if (!is_array($decoded)) {
    http_response_code(400);
    echo json_encode(['error' => 'Expected JSON array']);
    exit;
}

$file = __DIR__ . '/routes.json';

// Backup current file
if (file_exists($file)) {
    copy($file, $file . '.bak');
}

$written = file_put_contents($file, json_encode($decoded, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));

if ($written === false) {
    http_response_code(500);
    echo json_encode(['error' => 'Could not write routes.json — check file permissions']);
    exit;
}

echo json_encode(['ok' => true, 'count' => count((array)$decoded), 'bytes' => $written]);
