<?php
/**
 * Tatlı Ekleme Script
 * Veritabanına yeni tatlıları ekler
 */

require_once 'config.php';

// Yeni tatlılar
$desserts = array(
    array('name' => 'Sütlaç', 'desc' => 'Fırın sütlaç', 'price' => 35.00),
    array('name' => 'Muhallebi', 'desc' => 'Tavuk göğsü', 'price' => 40.00),
    array('name' => 'Dondurma', 'desc' => '3 top dondurma', 'price' => 30.00),
    array('name' => 'Tiramisu', 'desc' => 'İtalyan tatlısı', 'price' => 70.00),
    array('name' => 'Profiterol', 'desc' => 'Çikolata soslu profiterol', 'price' => 65.00),
    array('name' => 'Revani', 'desc' => 'Şerbetli revani', 'price' => 50.00),
    array('name' => 'Kazandibi', 'desc' => 'Geleneksel kazandibi', 'price' => 55.00)
);

$restaurantId = 5; // Tatlı Dünyası
$added = 0;
$errors = array();

foreach ($desserts as $dessert) {
    $sql = "INSERT INTO MenuItems (RestaurantID, Name, Description, Price, Category, IsAvailable, Stock) 
            VALUES (?, ?, ?, ?, 'Tatlı', 1, 50)";
    
    $params = array($restaurantId, $dessert['name'], $dessert['desc'], $dessert['price']);
    
    $stmt = sqlsrv_query($conn, $sql, $params);
    
    if ($stmt === false) {
        $errors[] = array(
            'dessert' => $dessert['name'],
            'error' => sqlsrv_errors()
        );
    } else {
        $added++;
        sqlsrv_free_stmt($stmt);
    }
}

closeConnection($conn);

// Sonuç
echo "<!DOCTYPE html><html><head><meta charset='UTF-8'><title>Tatlı Ekleme</title>";
echo "<style>body{font-family:Arial;padding:20px;background:#f5f5f5;}";
echo ".success{background:#d4edda;color:#155724;padding:15px;border-radius:5px;margin:10px 0;}";
echo ".error{background:#f8d7da;color:#721c24;padding:15px;border-radius:5px;margin:10px 0;}";
echo "h1{color:#d71f4b;}</style></head><body>";
echo "<h1>🍰 Tatlı Ekleme Sonucu</h1>";

if ($added > 0) {
    echo "<div class='success'><strong>✅ Başarılı!</strong><br>$added adet tatlı eklendi.</div>";
}

if (count($errors) > 0) {
    echo "<div class='error'><strong>❌ Hatalar:</strong><br>";
    foreach ($errors as $err) {
        echo "• " . $err['dessert'] . ": " . print_r($err['error'], true) . "<br>";
    }
    echo "</div>";
}

echo "<br><a href='/frontend/restaurant.html?id=5' style='background:#d71f4b;color:white;padding:10px 20px;text-decoration:none;border-radius:5px;'>Restoran Sayfasına Git</a>";
echo "</body></html>";
?>
