<?php
/**
 * Yemeksepeti - Sipariş İşlemleri API
 * Tarih: 24 Kasım 2025
 */

require_once 'config.php';

header('Content-Type: application/json');

// İşlem türünü al
$action = isset($_GET['action']) ? $_GET['action'] : '';

switch ($action) {
    case 'create':
        createOrder($pdo);
        break;
    case 'add_item':
        addOrderItem($pdo);
        break;
    case 'history':
        getOrderHistory($pdo);
        break;
    case 'details':
        getOrderDetails($pdo);
        break;
    case 'list_all':
        getAllOrders($pdo);
        break;
    case 'list_restaurant':
        getRestaurantOrders($pdo);
        break;
    default:
        echo json_encode(array("error" => "Geçersiz işlem"));
        break;
}

/**
 * Yeni sipariş oluştur
 */
function createOrder($pdo) {
    try {
        $userId = isset($_POST['user_id']) ? intval($_POST['user_id']) : 0;
        $restaurantId = isset($_POST['restaurant_id']) ? intval($_POST['restaurant_id']) : 0;
        $addressId = isset($_POST['address_id']) ? intval($_POST['address_id']) : 0;
        $paymentMethod = isset($_POST['payment_method']) ? $_POST['payment_method'] : 'Kredi Kartı';
        $note = isset($_POST['note']) ? $_POST['note'] : null;
        
        if ($userId <= 0 || $restaurantId <= 0) {
            echo json_encode(array("error" => "Geçersiz parametreler"));
            return;
        }
        
        $deliveryFee = 10.00;
        $sql = "INSERT INTO Orders (UserID, RestaurantID, AddressID, OrderDate, Status, PaymentMethod, DeliveryFee, TotalAmount, Note) 
                VALUES (?, ?, ?, GETDATE(), 'onaylandi', ?, ?, 0, ?)";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$userId, $restaurantId, $addressId, $paymentMethod, $deliveryFee, $note]);
        
        $orderId = $pdo->lastInsertId();
        
        echo json_encode(array("success" => true, "order_id" => $orderId));
    } catch (PDOException $e) {
        echo json_encode(array("error" => "Sipariş oluşturulamadı", "details" => $e->getMessage()));
    }
}

/**
 * Siparişe ürün ekle
 */
function addOrderItem($pdo) {
    try {
        $orderId = isset($_POST['order_id']) ? intval($_POST['order_id']) : 0;
        $menuItemId = isset($_POST['menu_item_id']) ? intval($_POST['menu_item_id']) : 0;
        $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;
        $unitPrice = isset($_POST['unit_price']) ? floatval($_POST['unit_price']) : 0;
        
        if ($orderId <= 0 || $menuItemId <= 0 || $quantity <= 0) {
            echo json_encode(array("error" => "Geçersiz parametreler"));
            return;
        }
        
        $subtotal = $quantity * $unitPrice;
        
        $sql = "INSERT INTO OrderDetails (OrderID, MenuItemID, Quantity, UnitPrice, Subtotal) 
                VALUES (?, ?, ?, ?, ?)";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$orderId, $menuItemId, $quantity, $unitPrice, $subtotal]);
        
        $updateSql = "UPDATE Orders SET TotalAmount = (
            SELECT SUM(Subtotal) FROM OrderDetails WHERE OrderID = ?
        ) WHERE OrderID = ?";
        $updateStmt = $pdo->prepare($updateSql);
        $updateStmt->execute([$orderId, $orderId]);
        
        echo json_encode(array("success" => true));
    } catch (PDOException $e) {
        echo json_encode(array("error" => "Ürün eklenemedi", "details" => $e->getMessage()));
    }
}

/**
 * Kullanıcının sipariş geçmişini getir
 */
function getOrderHistory($pdo) {
    try {
        $userId = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;
        $status = isset($_GET['status']) ? $_GET['status'] : null;
        
        if ($userId <= 0) {
            echo json_encode(array("error" => "Geçersiz kullanıcı ID"));
            return;
        }
        
        if ($status) {
            $sql = "SELECT o.OrderID, o.UserOrderNumber, o.OrderDate, o.TotalAmount, o.DeliveryFee, o.Status, o.PaymentMethod, o.Note,
                           r.Name as RestaurantName, a.AddressText
                    FROM Orders o
                    INNER JOIN Restaurants r ON o.RestaurantID = r.RestaurantID
                    LEFT JOIN Addresses a ON o.AddressID = a.AddressID
                    WHERE o.UserID = ? AND o.Status = ? 
                    ORDER BY o.OrderDate DESC";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$userId, $status]);
        } else {
            $sql = "SELECT o.OrderID, o.UserOrderNumber, o.OrderDate, o.TotalAmount, o.DeliveryFee, o.Status, o.PaymentMethod, o.Note,
                           r.Name as RestaurantName, a.AddressText
                    FROM Orders o
                    INNER JOIN Restaurants r ON o.RestaurantID = r.RestaurantID
                    LEFT JOIN Addresses a ON o.AddressID = a.AddressID
                    WHERE o.UserID = ? 
                    ORDER BY o.OrderDate DESC";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$userId]);
        }
        
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($orders);
    } catch (PDOException $e) {
        echo json_encode(array("error" => "Siparişler yüklenemedi", "details" => $e->getMessage()));
    }
}

/**
 * Sipariş detaylarını getir
 */
function getOrderDetails($pdo) {
    try {
        $orderId = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
        
        if ($orderId <= 0) {
            echo json_encode(array("error" => "Geçersiz sipariş ID"));
            return;
        }
        
        $sql = "SELECT od.*, mi.Name as MenuItemName 
                FROM OrderDetails od
                JOIN MenuItems mi ON od.MenuItemID = mi.MenuItemID
                WHERE od.OrderID = ?";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$orderId]);
        
        $details = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($details);
    } catch (PDOException $e) {
        echo json_encode(array("error" => "Sipariş detayları yüklenemedi", "details" => $e->getMessage()));
    }
}

/**
 * Tüm siparişleri getir (Admin için)
 */
function getAllOrders($pdo) {
    try {
        $sql = "SELECT o.OrderID, o.OrderDate, o.TotalAmount, o.DeliveryFee, o.Status, o.PaymentMethod,
                       r.Name as RestaurantName, u.FullName as CustomerName, u.Phone as CustomerPhone
                FROM Orders o
                JOIN Restaurants r ON o.RestaurantID = r.RestaurantID
                JOIN Users u ON o.UserID = u.UserID
                ORDER BY o.OrderDate DESC";
        
        $stmt = $pdo->query($sql);
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo json_encode(array("success" => true, "orders" => $orders));
    } catch (PDOException $e) {
        echo json_encode(array("success" => false, "error" => "Siparişler yüklenemedi", "details" => $e->getMessage()));
    }
}

/**
 * Restoran siparişlerini getir
 */
function getRestaurantOrders($pdo) {
    try {
        $restaurantId = isset($_GET['restaurant_id']) ? intval($_GET['restaurant_id']) : 0;
        
        if ($restaurantId <= 0) {
            echo json_encode(array("success" => false, "error" => "Geçersiz restoran ID"));
            return;
        }
        
        $sql = "SELECT o.OrderID, o.OrderDate, o.TotalAmount, o.DeliveryFee, o.Status, o.PaymentMethod, o.Note,
                       u.FullName, u.Phone, a.AddressText
                FROM Orders o
                JOIN Users u ON o.UserID = u.UserID
                LEFT JOIN Addresses a ON o.AddressID = a.AddressID
                WHERE o.RestaurantID = ?
                ORDER BY 
                    CASE 
                        WHEN o.Status = 'hazirlaniyor' THEN 1
                        WHEN o.Status = 'yola_cikti' THEN 2
                        ELSE 3
                    END,
                    o.OrderDate DESC";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$restaurantId]);
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo json_encode(array("success" => true, "orders" => $orders));
    } catch (PDOException $e) {
        echo json_encode(array("success" => false, "error" => "Siparişler yüklenemedi", "details" => $e->getMessage()));
    }
}
?>
