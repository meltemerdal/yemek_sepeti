<?php
require_once 'config.php';

try {
    // PDO bağlantısı
    $serverName = "localhost\\SQLEXPRESS";
    $database = "YemekSepetiDB";
    $conn = new PDO("sqlsrv:Server=$serverName;Database=$database", null, null, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    
    // ImageURL'yi güncelle
    $stmt = $conn->prepare("UPDATE Restaurants SET ImageURL = ? WHERE RestaurantID = ?");
    $stmt->execute(['ustadonerci.jpg', 38]);
    
    echo "Usta Dönerci görseli güncellendi: ustadonerci.jpg\n";
    
    // Kontrol et
    $stmt = $conn->prepare("SELECT Name, ImageURL FROM Restaurants WHERE RestaurantID = ?");
    $stmt->execute([38]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo "Güncel kayıt:\n";
    echo "Ad: " . $result['Name'] . "\n";
    echo "Görsel: " . $result['ImageURL'] . "\n";
    
    // Diğer döner restoranlarını kontrol et
    echo "\n--- Diğer Döner Restoranları ---\n";
    $stmt = $conn->prepare("SELECT RestaurantID, Name, ImageURL FROM Restaurants WHERE Category LIKE '%Döner%'");
    $stmt->execute();
    $others = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($others as $r) {
        echo "ID: {$r['RestaurantID']} - {$r['Name']} - {$r['ImageURL']}\n";
    }
    
} catch (Exception $e) {
    echo "Hata: " . $e->getMessage() . "\n";
}
?>
