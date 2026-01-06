<?php
require_once 'config.php';

try {
    // Status kolonunu yeniden yapılandır
    $pdo->exec("ALTER TABLE Orders ALTER COLUMN Status NVARCHAR(30)");
    echo "✓ Status kolonu güncellendi\n";
    
    // Mevcut siparişleri varsayılan duruma güncelle
    $pdo->exec("UPDATE Orders SET Status = 'onaylandi' WHERE Status = 'Hazırlanıyor' OR Status = 'Hazirlanıyor' OR Status IS NULL");
    echo "✓ Mevcut siparişler 'onaylandi' durumuna güncellendi\n";
    
    echo "\n✅ Sipariş durum sistemi başarıyla kuruldu!\n";
} catch (Exception $e) {
    echo "❌ Hata: " . $e->getMessage() . "\n";
}
