<?php
require_once 'config.php';

try {
    $stmt = $pdo->prepare("
        UPDATE MenuItems 
        SET Name = 'Fanta (33 cl.)', 
            ImageURL = 'fanta.jpg'
        WHERE MenuItemID = 200
    ");
    
    $stmt->execute();
    
    echo "✅ Güncellendi: Fanta (33 cl.) - fanta.jpg\n";
    
} catch (PDOException $e) {
    echo "❌ Hata: " . $e->getMessage() . "\n";
}
