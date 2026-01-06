<?php
require_once 'config.php';

try {
    $serverName = "localhost\\SQLEXPRESS";
    $database = "YemekSepetiDB";
    $conn = new PDO("sqlsrv:Server=$serverName;Database=$database", null, null, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    
    $restaurantId = 38; // Usta Dönerci
    
    echo "=== ET DÖNER MENÜLER KATEGORİSİ EKLEME ===\n\n";
    
    // Et Döner Menüler kategorisi
    $menuItems = [
        [
            'name' => 'Kokteyl Soslu Dürüm Et Döner Menü',
            'description' => 'Kokteyl Soslu Dürüm Et Döner + Patates Kızartması (Orta) + Ayran (20 cl.)',
            'price' => 320.00,
            'category' => 'Et Döner Menüler',
            'image' => 'gyromenu.jpg'
        ],
        [
            'name' => 'Cacık Soslu Dürüm Et Döner Menü',
            'description' => 'Cacık Soslu Dürüm Et Döner + Patates Kızartması (Orta) + Ayran (20 cl.)',
            'price' => 320.00,
            'category' => 'Et Döner Menüler',
            'image' => 'gyromenu.jpg'
        ],
        [
            'name' => 'Acı Soslu Dürüm Et Döner Menü',
            'description' => 'Acı Soslu Dürüm Et Döner + Patates Kızartması (Orta) + Ayran (20 cl.)',
            'price' => 320.00,
            'category' => 'Et Döner Menüler',
            'image' => 'gyromenu.jpg'
        ],
        [
            'name' => 'UD Et İskender Menü',
            'description' => 'UD Et İskender + Ayran (20 cl.)',
            'price' => 350.00,
            'category' => 'Et Döner Menüler',
            'image' => 'udiskender.jpg'
        ],
        [
            'name' => 'Tombik Et Döner Menü',
            'description' => 'Tombik Et Döner + Patates Kızartması (Orta) + Ayran (20 cl.)',
            'price' => 295.00,
            'category' => 'Et Döner Menüler',
            'image' => 'gyromenu.jpg'
        ],
        [
            'name' => 'UD Beyti Döner Menü',
            'description' => 'UD Beyti Döner + Ayran (20 cl.)',
            'price' => 350.00,
            'category' => 'Et Döner Menüler',
            'image' => 'beyti.jpg'
        ]
    ];
    
    $stmt = $conn->prepare("
        INSERT INTO MenuItems (RestaurantID, Name, Description, Price, Category, ImageURL, IsAvailable, Stock)
        VALUES (?, ?, ?, ?, ?, ?, 1, 999)
    ");
    
    $addedCount = 0;
    foreach ($menuItems as $item) {
        $stmt->execute([
            $restaurantId,
            $item['name'],
            $item['description'],
            $item['price'],
            $item['category'],
            $item['image']
        ]);
        $addedCount++;
        echo "✓ {$item['name']} - {$item['price']} TL\n";
    }
    
    echo "\n✅ Toplam $addedCount ürün 'Et Döner Menüler' kategorisine eklendi!\n";
    
    // Kontrol
    echo "\n--- ET DÖNER MENÜLER KATEGORİSİ ---\n";
    $stmt = $conn->prepare("
        SELECT Name, Price, ImageURL 
        FROM MenuItems 
        WHERE RestaurantID = ? AND Category = 'Et Döner Menüler'
        ORDER BY Price DESC
    ");
    $stmt->execute([$restaurantId]);
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($items as $item) {
        echo "• {$item['Name']} - {$item['Price']} TL (Görsel: {$item['ImageURL']})\n";
    }
    
    // Toplam kategori sayısı
    echo "\n--- USTA DÖNERCİ TÜM KATEGORİLER ---\n";
    $stmt = $conn->prepare("
        SELECT Category, COUNT(*) as count 
        FROM MenuItems 
        WHERE RestaurantID = ? 
        GROUP BY Category
    ");
    $stmt->execute([$restaurantId]);
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($categories as $cat) {
        echo "{$cat['Category']}: {$cat['count']} ürün\n";
    }
    
} catch (Exception $e) {
    echo "❌ Hata: " . $e->getMessage() . "\n";
}
?>
