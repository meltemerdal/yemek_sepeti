<?php
require 'backend/config.php';

echo "=== Usta Dönerci Yerel Görsel Ayarlanıyor ===\n\n";

try {
    // Yerel görsel yolu
    $imageURL = '/frontend/images/ustadonerci.jpg';
    
    $sql = "UPDATE Restaurants 
            SET ImageURL = ?
            WHERE RestaurantID = 38";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$imageURL]);
    
    echo "✓ Usta Dönerci yerel görseli ayarlandı!\n\n";
    echo "Görsel Yolu: $imageURL\n";
    
    // Kontrol et
    $stmt = $pdo->query("SELECT Name, ImageURL FROM Restaurants WHERE RestaurantID = 38");
    $restaurant = $stmt->fetch();
    
    echo "\nGüncel Bilgi:\n";
    echo "Restoran: " . $restaurant['Name'] . "\n";
    echo "Görsel: " . $restaurant['ImageURL'] . "\n";
    
} catch (PDOException $e) {
    echo "❌ HATA: " . $e->getMessage() . "\n";
}
