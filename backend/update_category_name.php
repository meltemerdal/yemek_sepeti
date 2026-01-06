<?php
require_once 'config.php';

try {
    $stmt = $pdo->prepare("
        UPDATE MenuItems 
        SET Category = 'Et Döner Menüleri' 
        WHERE RestaurantID = 38 AND Category = 'Et Döner Menüler'
    ");
    
    $stmt->execute();
    
    echo "✅ Kategori adı güncellendi: 'Et Döner Menüler' → 'Et Döner Menüleri'\n";
    echo "Etkilenen satır sayısı: " . $stmt->rowCount() . "\n";
    
} catch (PDOException $e) {
    echo "❌ Hata: " . $e->getMessage() . "\n";
}
