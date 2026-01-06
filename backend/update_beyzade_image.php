<?php
require_once 'config.php';

try {
    // Beyzade Mantı restoranını bul ve görseli güncelle
    $stmt = $pdo->prepare("
        UPDATE Restaurants 
        SET ImageURL = '/frontend/images/beyzademanti.jpg'
        WHERE Name LIKE '%Beyzade Mantı%' OR Name LIKE '%Beyzade Manti%'
    ");
    $stmt->execute();
    
    echo "<h2>✓ Beyzade Mantı görseli güncellendi!</h2>";
    
    // Kontrol et
    $stmt = $pdo->prepare("
        SELECT RestaurantID, Name, ImageURL 
        FROM Restaurants 
        WHERE Name LIKE '%Beyzade%'
    ");
    $stmt->execute();
    $restaurant = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($restaurant) {
        echo "<h3>Restoran Bilgileri:</h3>";
        echo "ID: {$restaurant['RestaurantID']}<br>";
        echo "Ad: {$restaurant['Name']}<br>";
        echo "Görsel: {$restaurant['ImageURL']}<br>";
        echo "<br><img src='{$restaurant['ImageURL']}' style='max-width: 300px;' />";
    } else {
        echo "<p>Beyzade Mantı bulunamadı!</p>";
    }
    
} catch (Exception $e) {
    echo "Hata: " . $e->getMessage();
}
?>
