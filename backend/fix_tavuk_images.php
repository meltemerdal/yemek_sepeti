<?php
require_once 'config.php';

try {
    // Tavuk döner menüleri için doğru görselleri ata
    $updates = [
        203 => 'etmenu.jpg',           // Dürüm Tavuk Döner (80 gr.) Menü
        204 => 'gyromenu.jpg',          // Gyro Soslu Dürüm Tavuk Döner Menü
        205 => 'acietmenu.jpg',         // Acı Soslu Dürüm Tavuk Döner Menü
        206 => 'tombikmenu.jpg'         // Tombik Tavuk Döner Menü
    ];
    
    foreach ($updates as $menuItemId => $imageUrl) {
        $stmt = $pdo->prepare("UPDATE MenuItems SET ImageURL = ? WHERE MenuItemID = ?");
        $stmt->execute([$imageUrl, $menuItemId]);
        echo "Updated MenuItemID $menuItemId -> $imageUrl\n";
    }
    
    echo "\n✓ Tüm tavuk döner menü görselleri güncellendi!\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
