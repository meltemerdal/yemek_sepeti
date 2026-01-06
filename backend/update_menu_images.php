<?php
require_once 'config.php';

try {
    $serverName = "localhost\\SQLEXPRESS";
    $database = "YemekSepetiDB";
    $conn = new PDO("sqlsrv:Server=$serverName;Database=$database", null, null, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    
    $restaurantId = 38; // Usta Dönerci
    
    echo "=== MENÜ GÖRSELLERİNİ GÜNCELLE ===\n\n";
    
    // Menü öğelerini ve görsellerini eşleştir
    $updates = [
        'Coca-Cola Fırsatı (Seçmeli Baget Menü)' => 'cocacolafirsati.jpg',
        '2\'li Eko Baget Tavuk Döner Menü' => 'ikilibaget.jpg',
        'Gyro Soslu Dürüm Et Döner Menü' => 'gyromenu.jpg',
        'UD® Et İskender' => 'udiskender.jpg',
        'Bi\'Kase Dolusu Menü' => 'bikase.jpg',
        'Ustasından Tavuklu Karışık Bowl' => 'udbowl.jpg'
    ];
    
    $stmt = $conn->prepare("
        UPDATE MenuItems 
        SET ImageURL = ? 
        WHERE RestaurantID = ? AND Name = ?
    ");
    
    foreach ($updates as $name => $image) {
        $stmt->execute([$image, $restaurantId, $name]);
        echo "✓ {$name} → {$image}\n";
    }
    
    echo "\n✅ Tüm görseller güncellendi!\n";
    
    // Kontrol
    echo "\n--- MENÜ ÖĞELERİ VE GÖRSELLERİ ---\n";
    $stmt = $conn->prepare("
        SELECT Name, ImageURL, Price 
        FROM MenuItems 
        WHERE RestaurantID = ? 
        ORDER BY MenuItemID
    ");
    $stmt->execute([$restaurantId]);
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($items as $item) {
        echo "• {$item['Name']}\n";
        echo "  Görsel: {$item['ImageURL']}\n";
        echo "  Fiyat: {$item['Price']} TL\n\n";
    }
    
} catch (Exception $e) {
    echo "❌ Hata: " . $e->getMessage() . "\n";
}
?>
