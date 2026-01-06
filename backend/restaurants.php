<?php
session_name('customer_session');
session_start();
/**
 * Yemeksepeti - Restoran İşlemleri API
 */

require_once 'config.php';
header('Content-Type: application/json; charset=utf-8');

// 🔹 MENÜ İSTEĞİ (action=menu)
if (isset($_GET['action']) && $_GET['action'] === 'menu') {
    getMenu();
    exit;
}

// 🔹 TEK RESTORAN (detay)
if (isset($_GET['id'])) {
    $id = (int) $_GET['id'];
    
    $stmt = $pdo->prepare("
        SELECT RestaurantID, Name, Category, Address, ImageURL, Rating, DeliveryTime, MinOrderAmount
        FROM Restaurants
        WHERE RestaurantID = ? AND Status = 1
    ");
    $stmt->execute([$id]);
    
    $restaurant = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$restaurant) {
        http_response_code(404);
        echo json_encode(['error' => 'Restoran bulunamadı']);
        exit;
    }
    
    // ImageURL boşsa default değer
    if (empty($restaurant['ImageURL'])) {
        $restaurant['ImageURL'] = 'https://images.unsplash.com/photo-1504674900247-0877df9cc836?w=400&h=300&fit=crop';
    }
    
    echo json_encode($restaurant);
    exit;
}

// 🔹 TÜM ONAYLI RESTORANLAR (liste)
$category = isset($_GET['category']) ? $_GET['category'] : null;
$minRating = isset($_GET['rating']) ? floatval($_GET['rating']) : 0;

$sql = "SELECT RestaurantID, Name, Category, Address, Rating, DeliveryTime, MinOrderAmount, ImageURL
        FROM Restaurants
        WHERE IsActive = 1";

$params = [];

if ($category) {
    $sql .= " AND Category = ?";
    $params[] = $category;
}

if ($minRating > 0) {
    $sql .= " AND Rating >= ?";
    $params[] = $minRating;
}

$sql .= " ORDER BY Rating DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);

echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
exit;

/**
 * Restoran menüsünü getir
 */
function getMenu() {
    global $pdo;
    
    $restaurantId = isset($_GET['id']) ? intval($_GET['id']) : 0;
    $category = isset($_GET['cat']) ? $_GET['cat'] : null;
    
    if ($restaurantId <= 0) {
        echo json_encode(array("error" => "Geçersiz restoran ID"));
        return;
    }
    
    try {
        $sql = "SELECT 
            mi.MenuItemID,
            mi.Name,
            mi.Description,
            mi.Price,
            mi.ImageURL,
            mi.IsAvailable,
            mc.CategoryName
        FROM MenuItems mi
        JOIN MenuCategories mc ON mc.CategoryID = mi.CategoryID
        WHERE mi.RestaurantID = ? 
          AND mi.IsAvailable = 1";
        
        $params = [$restaurantId];
        
        if ($category) {
            $sql .= " AND mc.CategoryName = ?";
            $params[] = $category;
        }
        
        $sql .= " ORDER BY mi.Name";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $menuItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo json_encode($menuItems);
    } catch (PDOException $e) {
        echo json_encode(array("error" => "Menü yüklenemedi", "details" => $e->getMessage()));
    }
}

?>
