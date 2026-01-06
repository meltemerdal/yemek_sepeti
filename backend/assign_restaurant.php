<?php
require_once 'config.php';

echo "=== RESTORAN SAHİBİ KULLANICILAR ===\n\n";

// RestaurantOwner rolündeki kullanıcıları listele
try {
    $stmt = $pdo->query("
        SELECT u.UserID, u.Email, u.FullName, r.RestaurantID, r.Name as RestaurantName
        FROM Users u
        LEFT JOIN Restaurants r ON u.UserID = r.OwnerUserID
        WHERE u.Email LIKE '%restaurant%' OR u.Email LIKE '%owner%' OR u.Email LIKE '%rest%'
    ");
    $owners = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (count($owners) > 0) {
        foreach ($owners as $owner) {
            echo "User ID: " . $owner['UserID'] . "\n";
            echo "Email: " . $owner['Email'] . "\n";
            echo "Ad: " . $owner['FullName'] . "\n";
            echo "Restoran ID: " . ($owner['RestaurantID'] ?? 'YOK') . "\n";
            echo "Restoran: " . ($owner['RestaurantName'] ?? 'YOK') . "\n";
            echo "---\n";
        }
        
        // Kullanıcı 8'i Usta Dönerci'ye ata (siparişleri olan restoran)
        echo "\nKullanıcı ID 8'i Usta Dönerci (ID: 38) restoranına atıyorum...\n";
        
        $stmt = $pdo->prepare("UPDATE Restaurants SET OwnerUserID = ? WHERE RestaurantID = 38");
        $stmt->execute([8]);
        
        echo "✓ Başarılı! User 8 artık Usta Dönerci'nin sahibi.\n";
        echo "Siparişleri görmek için restoran panelini yenileyin.\n";
    } else {
        echo "RestaurantOwner rolünde kullanıcı bulunamadı!\n";
    }
    
} catch (PDOException $e) {
    echo "Hata: " . $e->getMessage() . "\n";
}
