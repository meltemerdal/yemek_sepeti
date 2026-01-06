<?php
require_once 'config.php';

$categories = [
    'Balık ve Deniz Ürünleri',
    'Döner',
    'Kahvaltı & Börek',
    'Çiğ Köfte'
];

foreach ($categories as $cat) {
    // Kategori var mı kontrol et
    $stmt = $pdo->prepare("SELECT CategoryID, IsActive FROM Categories WHERE Name = ?");
    $stmt->execute([$cat]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        // Pasifse aktif yap
        if ($row['IsActive'] == 0) {
            $update = $pdo->prepare("UPDATE Categories SET IsActive = 1 WHERE CategoryID = ?");
            $update->execute([$row['CategoryID']]);
            echo "Aktif edildi: $cat<br>";
        } else {
            echo "Zaten aktif: $cat<br>";
        }
    } else {
        // Yoksa ekle
        $insert = $pdo->prepare("INSERT INTO Categories (Name, IsActive) VALUES (?, 1)");
        $insert->execute([$cat]);
        echo "Eklendi: $cat<br>";
    }
}
echo "\nİşlem tamamlandı.";
