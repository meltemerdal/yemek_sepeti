<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'config.php';

header('Content-Type: application/json; charset=utf-8');

if (!isset($_GET['id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Restaurant ID yok']);
    exit;
}

$restaurantId = (int) $_GET['id'];

try {
    // JOIN ile Categories tablosundan kategori ismini al
    $stmt = $pdo->prepare("
        SELECT
            m.MenuItemID,
            m.Name,
            m.Description,
            m.Price,
            m.ImageURL,
            m.CategoryID,
            m.IsVisible,
            m.IsAvailable,
            c.Name AS Category
        FROM MenuItems m
        LEFT JOIN Categories c ON m.CategoryID = c.CategoryID
        WHERE m.RestaurantID = ?
        ORDER BY c.Name, m.Name
    ");
    
    $stmt->execute([$restaurantId]);
    $allMenu = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Filtreleme
    $filteredItems = array_values(array_filter($allMenu, function($item) {
        return $item['IsVisible'] == 1 && $item['IsAvailable'] == 1;
    }));
    
    echo json_encode($filteredItems, JSON_UNESCAPED_UNICODE);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
}
