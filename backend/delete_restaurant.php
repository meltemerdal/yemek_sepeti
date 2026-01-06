<?php
session_name('admin_session');
session_start();
require_once 'config.php';

header('Content-Type: application/json');

// Sadece admin silebilir
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    http_response_code(403);
    echo json_encode(['error' => 'Yetkisiz']);
    exit;
}

$restaurantId = $_POST['restaurant_id'] ?? null;

if (!$restaurantId) {
    http_response_code(400);
    echo json_encode(['error' => 'Restaurant ID yok']);
    exit;
}

try {
    $pdo->beginTransaction();


    // 1️⃣ Restoranı bul (OwnerUserID al)
    $stmt = $pdo->prepare("SELECT OwnerUserID FROM Restaurants WHERE RestaurantID = ?");
    $stmt->execute([$restaurantId]);
    $restaurant = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$restaurant) {
        throw new Exception("Restoran bulunamadı");
    }
    $userId = $restaurant['OwnerUserID'];

    // 2️⃣ O restorana ait tüm MenuItemID'leri bul
    $stmt = $pdo->prepare("SELECT MenuItemID FROM MenuItems WHERE RestaurantID = ?");
    $stmt->execute([$restaurantId]);
    $menuItemIds = $stmt->fetchAll(PDO::FETCH_COLUMN);

    // 3️⃣ Her bir MenuItemID için OrderDetails kayıtlarını sil
    if (!empty($menuItemIds)) {
        $in = str_repeat('?,', count($menuItemIds) - 1) . '?';
        $pdo->prepare("DELETE FROM OrderDetails WHERE MenuItemID IN ($in)")->execute($menuItemIds);
    }

    // 4️⃣ Menü ürünlerini sil
    $pdo->prepare("DELETE FROM MenuItems WHERE RestaurantID = ?")->execute([$restaurantId]);

    // 5️⃣ Menü kategorilerini sil
    $pdo->prepare("DELETE FROM MenuCategories WHERE RestaurantID = ?")->execute([$restaurantId]);

    // 6️⃣ Restoranı sil
    $pdo->prepare("DELETE FROM Restaurants WHERE RestaurantID = ?")->execute([$restaurantId]);

    // 7️⃣ Kullanıcıyı sil (giriş yapamasın)
    if ($userId) {
        $pdo->prepare("DELETE FROM Users WHERE UserID = ?")->execute([$userId]);
    }

    $pdo->commit();

    echo json_encode(['success' => true]);

} catch (Exception $e) {
    $pdo->rollBack();
    echo json_encode(['error' => $e->getMessage()]);
}
