<?php
require_once 'config.php';

try {
    $serverName = "localhost\\SQLEXPRESS";
    $database = "YemekSepetiDB";
    $conn = new PDO("sqlsrv:Server=$serverName;Database=$database", null, null, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    
    $restaurantId = 38; // Usta Dönerci
    
    echo "=== ET DÖNER MENÜ GÖRSELLERİNİ GÜNCELLE ===\n\n";
    
    // Doğru eşleştirmeler
    $updates = [
        'Kokteyl Soslu Dürüm Et Döner Menü' => 'etmenu.jpg',
        'Cacık Soslu Dürüm Et Döner Menü' => 'cacikmenu.jpg',
        'Acı Soslu Dürüm Et Döner Menü' => 'acietmenu.jpg',
        'UD Et İskender Menü' => 'udiskendermenu.jpg',
        'Tombik Et Döner Menü' => 'tombikmenu.jpg',
        'UD Beyti Döner Menü' => 'beytimenu.jpg'
    ];
    
    $stmt = $conn->prepare("
        UPDATE MenuItems 
        SET ImageURL = ? 
        WHERE RestaurantID = ? AND Name = ?
    ");
    
    foreach ($updates as $name => $image) {
        $stmt->execute([$image, $restaurantId, $name]);
        echo "✓ {$name}\n  → {$image}\n\n";
    }
    
    echo "✅ Tüm görseller güncellendi!\n\n";
    
    // Kontrol
    echo "--- ET DÖNER MENÜLER (GÖRSEL KONTROLÜ) ---\n";
    $stmt = $conn->prepare("
        SELECT Name, ImageURL, Price 
        FROM MenuItems 
        WHERE RestaurantID = ? AND Category = 'Et Döner Menüler'
        ORDER BY Name
    ");
    $stmt->execute([$restaurantId]);
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($items as $item) {
        echo "{$item['Name']}\n";
        echo "  Görsel: {$item['ImageURL']} | Fiyat: {$item['Price']} TL\n\n";
    }
    
} catch (Exception $e) {
    echo "❌ Hata: " . $e->getMessage() . "\n";
}
?>
