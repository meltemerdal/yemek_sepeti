<?php
require 'backend/config.php';

echo "=== Usta Dönerci Görseli Güncelleniyor ===\n\n";

try {
    // Döner için uygun bir görsel URL
    $imageURL = 'https://images.unsplash.com/photo-1599487488170-d11ec9c172f0?w=800&h=600&fit=crop';
    
    $sql = "UPDATE Restaurants 
            SET ImageURL = ?
            WHERE RestaurantID = 38";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$imageURL]);
    
    echo "✓ Usta Dönerci görseli güncellendi!\n\n";
    echo "Yeni Görsel URL: $imageURL\n";
    
    // Kontrol et
    $stmt = $pdo->query("SELECT Name, ImageURL FROM Restaurants WHERE RestaurantID = 38");
    $restaurant = $stmt->fetch();
    
    echo "\nGüncel Bilgi:\n";
    echo "Restoran: " . $restaurant['Name'] . "\n";
    echo "Görsel: " . $restaurant['ImageURL'] . "\n";
    
} catch (PDOException $e) {
    echo "❌ HATA: " . $e->getMessage() . "\n";
}
