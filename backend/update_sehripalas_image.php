<?php
require_once 'config.php';

try {
    // Şehr-i Palas'ın görselini güncelle
    $stmt = $pdo->prepare("UPDATE Restaurants SET ImageURL = ? WHERE Name LIKE ?");
    $stmt->execute(['sehripalas.jpg', '%Palas%']);
    
    echo "✅ Görsel güncellendi!\n\n";
    
    // Kontrol et
    $check = $pdo->query("SELECT RestaurantID, Name, ImageURL FROM Restaurants WHERE Name LIKE '%Palas%'");
    $result = $check->fetch(PDO::FETCH_ASSOC);
    
    if ($result) {
        echo "Restoran: " . $result['Name'] . "\n";
        echo "RestaurantID: " . $result['RestaurantID'] . "\n";
        echo "Yeni ImageURL: " . $result['ImageURL'] . "\n";
    }
    
} catch (PDOException $e) {
    echo "❌ Hata: " . $e->getMessage();
}
?>
