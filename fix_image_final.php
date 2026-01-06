<?php
require 'backend/config.php';

echo "=== Usta Dönerci Görsel Güncelleniyor ===\n\n";

try {
    $sql = "UPDATE Restaurants 
            SET ImageURL = 'ustadonerci.jpg'
            WHERE RestaurantID = 38";
    
    $pdo->exec($sql);
    
    echo "✓ Usta Dönerci görseli güncellendi!\n\n";
    
    // Kontrol et
    $stmt = $pdo->query("SELECT Name, ImageURL FROM Restaurants WHERE RestaurantID = 38");
    $restaurant = $stmt->fetch();
    
    echo "Restoran: " . $restaurant['Name'] . "\n";
    echo "ImageURL: " . $restaurant['ImageURL'] . "\n";
    
} catch (PDOException $e) {
    echo "❌ HATA: " . $e->getMessage() . "\n";
}
