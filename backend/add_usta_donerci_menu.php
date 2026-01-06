<?php
require_once 'config.php';

try {
    // PDO bağlantısı
    $serverName = "localhost\\SQLEXPRESS";
    $database = "YemekSepetiDB";
    $conn = new PDO("sqlsrv:Server=$serverName;Database=$database", null, null, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    
    $restaurantId = 38; // Usta Dönerci
    
    // Menü öğelerini ekle
    $menuItems = [
        [
            'name' => 'Coca-Cola Fırsatı (Seçmeli Baget Menü)',
            'description' => '3 Adet Baget Tavuk Döner + Patates Kızartması (Büyük) + Soğan Halkası (6\'lı) + Coca-Cola (1 L.)',
            'price' => 350.00,
            'category' => 'Popüler',
            'is_available' => 1
        ],
        [
            'name' => '2\'li Eko Baget Tavuk Döner Menü',
            'description' => '2 Adet Baget Tavuk Döner + Patates Kızartması (Orta) + Ayran (20 cl.)',
            'price' => 250.00,
            'category' => 'Popüler',
            'is_available' => 1
        ],
        [
            'name' => 'Gyro Soslu Dürüm Et Döner Menü',
            'description' => 'Dürüm Et Döner + Patates Kızartması (Orta) + Ayran (20 cl.)',
            'price' => 320.00,
            'category' => 'Popüler',
            'is_available' => 1
        ],
        [
            'name' => 'UD® Et İskender',
            'description' => 'İskender sosu, İskender tereyağı, sivri biber, domates, yoğurt',
            'price' => 330.00,
            'category' => 'Popüler',
            'is_available' => 1
        ],
        [
            'name' => 'Bi\'Kase Dolusu Menü',
            'description' => '2 Adet Bi\'Kase Tavuklu Salata + 2 Adet Ayran (20 cl.)',
            'price' => 325.00,
            'category' => 'Popüler',
            'is_available' => 1
        ],
        [
            'name' => 'Ustasından Tavuklu Karışık Bowl',
            'description' => 'Tavuk Döner (100 gr), Patates, Pilav, Akdeniz Yeşilliği, Domates, Maydanoz, Gyro Sos',
            'price' => 220.00,
            'category' => 'Popüler',
            'is_available' => 1
        ]
    ];
    
    $stmt = $conn->prepare("
        INSERT INTO MenuItems (RestaurantID, Name, Description, Price, Category, IsAvailable)
        VALUES (?, ?, ?, ?, ?, ?)
    ");
    
    $addedCount = 0;
    foreach ($menuItems as $item) {
        $stmt->execute([
            $restaurantId,
            $item['name'],
            $item['description'],
            $item['price'],
            $item['category'],
            $item['is_available']
        ]);
        $addedCount++;
        echo "✓ Eklendi: {$item['name']} - {$item['price']} TL\n";
    }
    
    echo "\n✅ Toplam $addedCount ürün Usta Dönerci menüsüne eklendi!\n";
    
    // Kontrol: Eklenen ürünleri listele
    echo "\n--- Usta Dönerci Popüler Menüsü ---\n";
    $stmt = $conn->prepare("
        SELECT MenuItemID, Name, Price, Category 
        FROM MenuItems 
        WHERE RestaurantID = ? AND Category = 'Popüler'
        ORDER BY MenuItemID
    ");
    $stmt->execute([$restaurantId]);
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($items as $item) {
        echo "ID: {$item['MenuItemID']} - {$item['Name']} - {$item['Price']} TL\n";
    }
    
} catch (Exception $e) {
    echo "❌ Hata: " . $e->getMessage() . "\n";
}
?>
