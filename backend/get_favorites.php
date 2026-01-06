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

try {
    $stmt = $pdo->prepare("
        SELECT RestaurantID, CreatedAt
        FROM Favorites
        WHERE UserID = ?
        ORDER BY CreatedAt DESC
    ");
    $stmt->execute([$_SESSION['user_id']]);
    $favorites = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode([
        'success' => true,
        'favorites' => $favorites
    ]);
    
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
exit;
