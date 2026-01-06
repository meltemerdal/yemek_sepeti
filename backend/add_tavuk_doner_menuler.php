<?php
require_once 'config.php';

try {
    echo "=== TAVUK DÖNER MENÜLERİ EKLEME (USTA DÖNERCİ) ===\n\n";
    
    $restaurantId = 38; // Usta Dönerci
    
    $items = [
        [
            'name' => 'Dürüm Tavuk Döner (80 gr.) Menü',
            'description' => 'Dürüm Tavuk Döner (80 gr.) + Patates Kızartması (Orta) + Ayran (20 cl.)',
            'price' => 255,
            'category' => 'Tavuk Döner Menüleri',
            'image' => 'tavukmenu.jpg'
        ],
        [
            'name' => 'Gyro Soslu Dürüm Tavuk Döner Menü',
            'description' => 'Gyro Soslu Dürüm Tavuk Döner + Patates Kızartması (Orta) + Ayran (20 cl.)',
            'price' => 270,
            'category' => 'Tavuk Döner Menüleri',
            'image' => 'gyrotavukmenu.jpg'
        ],
        [
            'name' => 'Acı Soslu Dürüm Tavuk Döner Menü',
            'description' => 'Acı Soslu Dürüm Tavuk Döner + Patates Kızartması (Orta) + Ayran (20 cl.)',
            'price' => 270,
            'category' => 'Tavuk Döner Menüleri',
            'image' => 'acitavukmenu.jpg'
        ],
        [
            'name' => 'Tombik Tavuk Döner Menü',
            'description' => 'Tombik Tavuk Döner + Patates Kızartması (Orta) + Ayran (20 cl.)',
            'price' => 245,
            'category' => 'Tavuk Döner Menüleri',
            'image' => 'tombiktavukmenu.jpg'
        ]
    ];
    
    $stmt = $pdo->prepare("
        INSERT INTO MenuItems (RestaurantID, Name, Description, Price, Category, Stock, ImageURL)
        VALUES (?, ?, ?, ?, ?, 100, ?)
    ");
    
    $addedCount = 0;
    foreach ($items as $item) {
        $stmt->execute([
            $restaurantId,
            $item['name'],
            $item['description'],
            $item['price'],
            $item['category'],
            $item['image']
        ]);
        echo "✅ Eklendi: {$item['name']} - {$item['price']} ₺ (Görsel: {$item['image']})\n";
        $addedCount++;
    }
    
    echo "\n✅ Toplam $addedCount tavuk döner menüsü eklendi!\n\n";
    
    // Kontrol
    echo "--- TAVUK DÖNER MENÜLERİ ---\n";
    $stmt = $pdo->prepare("
        SELECT MenuItemID, Name, Price, ImageURL
        FROM MenuItems
        WHERE RestaurantID = ? AND Category = 'Tavuk Döner Menüleri'
        ORDER BY Price DESC
    ");
    $stmt->execute([$restaurantId]);
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "ID: {$row['MenuItemID']} | {$row['Name']} | {$row['Price']} ₺ | Görsel: {$row['ImageURL']}\n";
    }
    
} catch (PDOException $e) {
    echo "❌ Hata: " . $e->getMessage() . "\n";
}
