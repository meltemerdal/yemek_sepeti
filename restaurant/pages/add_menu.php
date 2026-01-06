<?php
session_name('restaurant_session');
session_start();

require_once '../../backend/config.php';
require_once '../../backend/auth_helper.php';

requireAuth('RestaurantOwner');

error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die('POST degil');
}

// === VERILERI AL ===
$categoryId = $_POST['category_id'] ?? null;
$menuName = trim($_POST['menu_name'] ?? '');
$menuDescription = trim($_POST['menu_description'] ?? '');
$menuPrice = (float) ($_POST['menu_price'] ?? 0);

// === RESTORAN ID ===
$stmt = $pdo->prepare("SELECT RestaurantID FROM Restaurants WHERE OwnerUserID = ?");
$stmt->execute([$_SESSION['restaurant_id']]);
$restaurantId = $stmt->fetchColumn();

if (!$restaurantId) {
    die('Restaurant bulunamadi');
}

// === GORSEL ===
$menuImagePath = null;
if (!empty($_FILES['menu_image']['name'])) {
    $dir = '../../images/';
    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }

    $fileName = uniqid().'_'.basename($_FILES['menu_image']['name']);
    $fullPath = $dir.$fileName;
    
    if (move_uploaded_file($_FILES['menu_image']['tmp_name'], $fullPath)) {
        $menuImagePath = 'images/'.$fileName;
    }
}

// === INSERT ===
$stmt = $pdo->prepare("
    INSERT INTO MenuItems 
    (RestaurantID, Name, Description, Price, CategoryID, ImageURL, IsAvailable)
    VALUES (?, ?, ?, ?, ?, ?, 1)
");

$stmt->execute([
    $restaurantId,
    $menuName,
    $menuDescription,
    $menuPrice,
    $categoryId,
    $menuImagePath
]);

header('Location: menu.php?success=1');
exit;
