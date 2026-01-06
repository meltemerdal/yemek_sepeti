<?php
require_once 'config.php';

try {
    // Mevcut restoran kullanıcısının ID'sini al (restoran@gmail.com)
    $stmt = $pdo->prepare("SELECT UserID, Email, FullName FROM Users WHERE Email = ?");
    $stmt->execute(['restoran@gmail.com']);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$user) {
        die("Restoran kullanıcısı bulunamadı!");
    }
    
    $userId = $user['UserID'];
    echo "<h2>Mevcut Kullanıcı:</h2>";
    echo "UserID: {$userId}<br>";
    echo "Email: {$user['Email']}<br>";
    echo "Ad: {$user['FullName']}<br><br>";
    
    // Şehr-i Palas'ı bu kullanıcıya ata
    $stmt = $pdo->prepare("UPDATE Restaurants SET OwnerUserID = ? WHERE RestaurantID = 45");
    $stmt->execute([$userId]);
    
    echo "<h2>✓ Şehr-i Palas hesabınıza eklendi!</h2>";
    
    // Şimdi bu kullanıcıya ait tüm restoranları göster
    $stmt = $pdo->prepare("
        SELECT RestaurantID, Name, Status 
        FROM Restaurants 
        WHERE OwnerUserID = ?
        ORDER BY RestaurantID
    ");
    $stmt->execute([$userId]);
    $restaurants = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<h3>Hesabınızdaki Restoranlar:</h3>";
    echo "<ul>";
    foreach ($restaurants as $rest) {
        echo "<li><strong>{$rest['Name']}</strong> (ID: {$rest['RestaurantID']}, Durum: {$rest['Status']})</li>";
    }
    echo "</ul>";
    
    echo "<br><p>Artık restoran panelinde her iki restoranın siparişlerini de görebilirsiniz!</p>";
    echo "<p><a href='/restaurant_login.php'>Restoran Paneline Git</a></p>";
    
} catch (Exception $e) {
    echo "Hata: " . $e->getMessage();
}
?>
