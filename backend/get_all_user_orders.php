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
    // Tüm siparişleri getir
    $stmt = $pdo->prepare("
        SELECT o.OrderID, o.OrderDate, o.TotalAmount, o.DeliveryFee, o.Status, o.PaymentMethod,
               r.Name as RestaurantName, a.AddressText
        FROM Orders o
        JOIN Restaurants r ON o.RestaurantID = r.RestaurantID
        LEFT JOIN Addresses a ON o.AddressID = a.AddressID
        WHERE o.UserID = ?
        ORDER BY o.OrderDate DESC
    ");
    $stmt->execute([$_SESSION['user_id']]);
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode([
        'success' => true,
        'orders' => $orders
    ]);
    
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
exit;
