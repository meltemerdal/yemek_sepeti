<?php
require_once 'config.php';

header('Content-Type: application/json; charset=utf-8');

if (!isset($_GET['restaurant_id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Restaurant ID gerekli']);
    exit;
}

$restaurantId = (int) $_GET['restaurant_id'];

try {
    $stmt = $pdo->prepare("
        SELECT CategoryID, CategoryName
        FROM MenuCategories
        WHERE RestaurantID = ?
        ORDER BY CategoryName
    ");
    $stmt->execute([$restaurantId]);
    
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($categories, JSON_UNESCAPED_UNICODE);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
}
