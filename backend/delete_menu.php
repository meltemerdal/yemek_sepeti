<?php
require_once 'config.php';

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

$menuId = $_POST['menu_id'] ?? null;

if (!$menuId) {
    http_response_code(400);
    echo json_encode(['error' => 'Menu ID yok']);
    exit;
}

// ❗ MENÜDEN KALDIR (Soft Delete)
// Tükendi → IsAvailable = 0
// Menüden kaldır → IsVisible = 0
$stmt = $pdo->prepare("
    UPDATE MenuItems 
    SET IsVisible = 0 
    WHERE MenuItemID = ?
");
$stmt->execute([$menuId]);

echo json_encode(['success' => true]);
