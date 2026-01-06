<?php
require_once 'config.php';

try {
    echo "=== İÇECEKLER KATEGORİSİ EKLEME (USTA DÖNERCİ) ===\n\n";
    
    $restaurantId = 38; // Usta Dönerci
    
    $items = [
        [
            'name' => 'Coca-Cola Zero Sugar (33 cl.)',
            'description' => 'Kutu İçecek',
            'price' => 50,
            'category' => 'İçecekler',
            'image' => 'zero.jpg'
        ],
        [
            'name' => 'Coca-Cola (33 cl.)',
            'description' => 'Kutu İçecek',
            'price' => 50,
            'category' => 'İçecekler',
            'image' => 'teneke.jpg'
        ],
        [
            'name' => 'Fuse Tea Şeftali (33 cl.)',
            'description' => 'Kutu İçecek',
            'price' => 50,
            'category' => 'İçecekler',
            'image' => 'fusetea.jpg'
        ],
        [
            'name' => 'Ayran (20 cl.)',
            'description' => '20 cl.',
            'price' => 45,
            'category' => 'İçecekler',
            'image' => 'ayran.jpg'
        ],
        [
            'name' => 'Su (50 cl.)',
            'description' => 'Pet şişe',
            'price' => 25,
            'category' => 'İçecekler',
            'image' => 'su.jpg'
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
    
    echo "\n✅ Toplam $addedCount içecek eklendi!\n\n";
    
    // Kontrol
    echo "--- İÇECEKLER KATEGORİSİ ---\n";
    $stmt = $pdo->prepare("
        SELECT MenuItemID, Name, Price, ImageURL
        FROM MenuItems
        WHERE RestaurantID = ? AND Category = 'İçecekler'
        ORDER BY Price DESC
    ");
    $stmt->execute([$restaurantId]);
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "ID: {$row['MenuItemID']} | {$row['Name']} | {$row['Price']} ₺ | Görsel: {$row['ImageURL']}\n";
    }
    
} catch (PDOException $e) {
    echo "❌ Hata: " . $e->getMessage() . "\n";
}
