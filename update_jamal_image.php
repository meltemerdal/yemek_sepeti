<?php
require_once 'backend/config.php';

try {
    $stmt = $pdo->prepare("UPDATE Restaurants SET ImageURL = ? WHERE RestaurantID = ?");
    $stmt->execute(['images/jamal.jpg', 111]);
    
    echo "✅ Restoran görseli güncellendi!\n";
    echo "RestaurantID: 111\n";
    echo "ImageURL: images/jamal.jpg\n";
    
} catch (PDOException $e) {
    echo "❌ Hata: " . $e->getMessage() . "\n";
}
