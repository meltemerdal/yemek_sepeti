<?php
require_once 'config.php';

try {
    $serverName = "localhost\\SQLEXPRESS";
    $database = "YemekSepetiDB";
    $conn = new PDO("sqlsrv:Server=$serverName;Database=$database", null, null, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    
    $restaurantId = 38; // Usta Dönerci
    
    echo "=== USTA DÖNERCİ MENÜSÜNÜ TEMİZLE VE YENİDEN OLUŞTUR ===\n\n";
    
    // Tüm eski ürünleri sil
    $stmt = $conn->prepare("DELETE FROM MenuItems WHERE RestaurantID = ?");
    $stmt->execute([$restaurantId]);
    echo "✓ Eski menü temizlendi\n\n";
    
    // Yeni menü öğelerini ekle
    $menuItems = [
        // POPÜLER
        [
            'name' => 'Coca-Cola Fırsatı (Seçmeli Baget Menü)',
            'description' => '3 Adet Baget Tavuk Döner + Patates Kızartması (Büyük) + Soğan Halkası (6\'lı) + Coca-Cola (1 L.)',
            'price' => 350.00,
            'category' => 'Popüler'
        ],
        [
            'name' => '2\'li Eko Baget Tavuk Döner Menü',
            'description' => '2 Adet Baget Tavuk Döner + Patates Kızartması (Orta) + Ayran (20 cl.)',
            'price' => 250.00,
            'category' => 'Popüler'
        ],
        [
            'name' => 'Gyro Soslu Dürüm Et Döner Menü',
            'description' => 'Dürüm Et Döner + Patates Kızartması (Orta) + Ayran (20 cl.)',
            'price' => 320.00,
            'category' => 'Popüler'
        ],
        [
            'name' => 'UD® Et İskender',
            'description' => 'İskender sosu, İskender tereyağı, sivri biber, domates, yoğurt',
            'price' => 330.00,
            'category' => 'Popüler'
        ],
        [
            'name' => 'Bi\'Kase Dolusu Menü',
            'description' => '2 Adet Bi\'Kase Tavuklu Salata + 2 Adet Ayran (20 cl.)',
            'price' => 325.00,
            'category' => 'Popüler'
        ],
        [
            'name' => 'Ustasından Tavuklu Karışık Bowl',
            'description' => 'Tavuk Döner (100 gr), Patates, Pilav, Akdeniz Yeşilliği, Domates, Maydanoz, Gyro Sos',
            'price' => 220.00,
            'category' => 'Popüler'
        ]
    ];
    
    $stmt = $conn->prepare("
        INSERT INTO MenuItems (RestaurantID, Name, Description, Price, Category, IsAvailable)
        VALUES (?, ?, ?, ?, ?, 1)
    ");
    
    echo "Eklenen ürünler:\n";
    foreach ($menuItems as $item) {
        $stmt->execute([
            $restaurantId,
            $item['name'],
            $item['description'],
            $item['price'],
            $item['category']
        ]);
        echo "✓ {$item['category']}: {$item['name']} - {$item['price']} TL\n";
    }
    
    echo "\n✅ Usta Dönerci menüsü başarıyla oluşturuldu!\n";
    
    // Kontrol
    echo "\n--- POPÜLER KATEGORİSİ ---\n";
    $stmt = $conn->prepare("
        SELECT Name, Price FROM MenuItems 
        WHERE RestaurantID = ? AND Category = 'Popüler'
        ORDER BY MenuItemID
    ");
    $stmt->execute([$restaurantId]);
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($items as $item) {
        echo "• {$item['Name']} - {$item['Price']} TL\n";
    }
    
} catch (Exception $e) {
    echo "❌ Hata: " . $e->getMessage() . "\n";
}
?>
