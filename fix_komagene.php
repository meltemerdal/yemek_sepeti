<?php
require 'backend/config.php';

// Duplicate kayıtları temizle - Her menüden sadece bir tane bırak
$items = ['Dürüm Menü', 'Mega Dürüm Menü', 'Ülker Halley Combo Menü', 'Bi\' Tatlı Fırsat Menü', 'Doritos\'lu Double Dürüm Menü'];
$deleted = 0;

foreach ($items as $itemName) {
    // Her menü için ID'leri al
    $stmt = $pdo->prepare("SELECT MenuItemID FROM MenuItems WHERE RestaurantID = 92 AND Name = ? AND Category = 'Dürüm Menüler' ORDER BY MenuItemID");
    $stmt->execute([$itemName]);
    $ids = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    // İlk kaydı tut, diğerlerini sil
    if (count($ids) > 1) {
        array_shift($ids); // İlk ID'yi çıkar
        foreach ($ids as $id) {
            $delStmt = $pdo->prepare("DELETE FROM MenuItems WHERE MenuItemID = ?");
            $delStmt->execute([$id]);
            $deleted++;
        }
    }
}

echo "Silinen duplicate kayıt sayısı: $deleted\n";

// Görselleri kontrol et
$images = [
    'durummenu.jpg' => 'durumos.jpg',
    'halleydurummenu.jpg' => 'durumos.jpg',
    'firsatdurummenu.jpg' => 'xldurumos.jpg',
    'doritosdurummenu.jpg' => 'xltavukludurumos.jpg'
];

foreach ($images as $target => $source) {
    $sourcePath = "frontend/images/$source";
    $targetPath = "frontend/images/$target";
    
    if (!file_exists($targetPath) && file_exists($sourcePath)) {
        copy($sourcePath, $targetPath);
        echo "Oluşturuldu: $target\n";
    } else if (file_exists($targetPath)) {
        echo "Zaten mevcut: $target\n";
    } else {
        echo "Kaynak bulunamadı: $source\n";
    }
}

echo "\nTamamlandı!\n";
