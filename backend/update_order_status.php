<?php
session_start();
require_once __DIR__ . '/config.php';

header('Content-Type: application/json; charset=utf-8');
error_reporting(0); // JSON'u bozan uyarıları kapat

// Local debug: if the request originates from localhost, include a `reason` field
// in failure responses to aid debugging (safe for development only).
$debugLocal = (
    isset($_SERVER['REMOTE_ADDR']) && (
        $_SERVER['REMOTE_ADDR'] === '127.0.0.1' || $_SERVER['REMOTE_ADDR'] === '::1'
    )
);

// Yetki kontrolü kaldırıldı: Herkes güncelleme yapabilir

// Sadece POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $resp = ['success' => false];
    if ($debugLocal) $resp['reason'] = 'invalid_method';
    echo json_encode($resp);
    exit;
}

$orderId   = $_POST['order_id'] ?? null;
$newStatus = $_POST['status'] ?? null;

$validStatuses = [
    'onaylandi',
    'hazirlaniyor',
    'yola_cikti',
    'teslim_edildi',
    'teslim_edilemedi'
];

if (!$orderId || !$newStatus || !in_array($newStatus, $validStatuses, true)) {
    $resp = ['success' => false];
    if ($debugLocal) {
        $resp['reason'] = 'invalid_parameters';
        $resp['details'] = [ 'order_id' => $orderId, 'status' => $newStatus ];
    }
    echo json_encode($resp);
    exit;
}

$stmt = $pdo->prepare("UPDATE Orders SET Status = ? WHERE OrderID = ?");

$ok = $stmt->execute([$newStatus, $orderId]);
if ($ok) {
    echo json_encode(['success' => true]);
} else {
    $resp = ['success' => false];
    if ($debugLocal) {
        $err = $stmt->errorInfo();
        $resp['reason'] = 'db_update_failed';
        $resp['db_error'] = $err;
    }
    echo json_encode($resp);
}

exit;
