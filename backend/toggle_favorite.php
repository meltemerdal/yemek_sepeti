<?php
session_name('customer_session');
session_start();
require_once __DIR__ . '/config.php';

header('Content-Type: application/json; charset=utf-8');

// Kullanıcı giriş kontrolü
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'Oturum açılmamış']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Geçersiz istek metodu']);
    exit;
}

$restaurantId = $_POST['restaurant_id'] ?? null;

if (!$restaurantId) {
    echo json_encode(['success' => false, 'error' => 'Restoran ID gerekli']);
    exit;
}

try {
    // Favoride var mı kontrol et
    $stmt = $pdo->prepare("
        SELECT COUNT(*) as count 
        FROM Favorites 
        WHERE UserID = ? AND RestaurantID = ?
    ");
    $stmt->execute([$_SESSION['user_id'], $restaurantId]);
    $exists = $stmt->fetch(PDO::FETCH_ASSOC)['count'] > 0;
    
    if ($exists) {
        // Favorilerden çıkar
        $stmt = $pdo->prepare("
            DELETE FROM Favorites 
            WHERE UserID = ? AND RestaurantID = ?
        ");
        $stmt->execute([$_SESSION['user_id'], $restaurantId]);
        
        echo json_encode([
            'success' => true,
            'action' => 'removed',
            'message' => 'Favorilerden çıkarıldı'
        ]);
    } else {
        // Favorilere ekle
        $stmt = $pdo->prepare("
            INSERT INTO Favorites (UserID, RestaurantID, CreatedAt)
            VALUES (?, ?, GETDATE())
        ");
        $stmt->execute([$_SESSION['user_id'], $restaurantId]);
        
        echo json_encode([
            'success' => true,
            'action' => 'added',
            'message' => 'Favorilere eklendi'
        ]);
    }
    
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
exit;
