<?php
/**
 * Musqa Burger MNG Çocuk Menüleri Ekleme Script
 */

require_once 'config.php';

// Musqa Burger MNG RestaurantID'sini bul
$sql = "SELECT RestaurantID FROM dbo.Restaurants WHERE Name LIKE N'%Musqa Burger MNG%'";
$stmt = sqlsrv_query($conn, $sql);

if ($stmt === false) {
    die(json_encode(['success' => false, 'error' => 'Restaurant bulunamadı', 'details' => sqlsrv_errors()]));
}

$row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
if (!$row) {
    die(json_encode(['success' => false, 'error' => 'Musqa Burger MNG restoranı bulunamadı']));
}

$restaurantId = $row['RestaurantID'];
sqlsrv_free_stmt($stmt);

// Çocuk menülerini ekle
$insertSql = "INSERT INTO dbo.MenuItems (RestaurantID, Name, Description, Price, Category, IsAvailable, Stock) VALUES 
(?, N'Musqa Mini Tavuk', N'Ev yapımı mini hamburger ekmeği, ev yapımı tavuk burger köftesi, mayonez. Patates kızartması ile', 286.90, N'Çocuk Menüleri', 1, 100),
(?, N'Musqa Mini', N'Ev yapımı mini hamburger ekmeği, ev yapımı burger köftesi, ketçap. Patates kızartması ile', 346.90, N'Çocuk Menüleri', 1, 100),
(?, N'Musqa Mini Cheese', N'Ev yapımı mini hamburger ekmeği, ev yapımı burger köftesi, cheddar peyniri, ketçap. Patates kızartması ile', 346.90, N'Çocuk Menüleri', 1, 100)";

$params = array($restaurantId, $restaurantId, $restaurantId);
$stmt = sqlsrv_query($conn, $insertSql, $params);

if ($stmt === false) {
    echo json_encode([
        'success' => false,
        'message' => 'Menü eklenirken hata oluştu',
        'errors' => sqlsrv_errors()
    ]);
} else {
    sqlsrv_free_stmt($stmt);
    echo json_encode([
        'success' => true,
        'message' => 'Musqa Burger MNG çocuk menüleri başarıyla eklendi!',
        'restaurantId' => $restaurantId
    ]);
}

sqlsrv_close($conn);
?>
