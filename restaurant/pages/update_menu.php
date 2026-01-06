<?php
require_once '../../backend/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Geçersiz istek']);
    exit;
}

$menuId = $_POST['menu_id'] ?? null;
$name = $_POST['menu_name'] ?? '';
$desc = $_POST['menu_description'] ?? '';
$price = $_POST['menu_price'] ?? '';
$categoryId = $_POST['category_id'] ?? null;

if (!$menuId || !$name || !$desc || !$price || !$categoryId) {
    echo json_encode(['success' => false, 'message' => 'Eksik veri']);
    exit;
}

// Görsel güncelleme opsiyonel
$imageUrl = null;
if (isset($_FILES['menu_image']) && $_FILES['menu_image']['error'] === UPLOAD_ERR_OK) {
    $targetDir = '../../images/';
    $fileName = uniqid('menu_') . '_' . basename($_FILES['menu_image']['name']);
    $targetFile = $targetDir . $fileName;
    if (move_uploaded_file($_FILES['menu_image']['tmp_name'], $targetFile)) {
        $imageUrl = 'images/' . $fileName;
    }
}

$sql = "UPDATE MenuItems SET Name=?, Description=?, Price=?, CategoryID=?";
$params = [$name, $desc, $price, $categoryId];
if ($imageUrl) {
    $sql .= ", ImageURL=?";
    $params[] = $imageUrl;
}
$sql .= " WHERE MenuItemID=?";
$params[] = $menuId;

$stmt = $pdo->prepare($sql);
$success = $stmt->execute($params);

echo json_encode(['success' => $success]);
