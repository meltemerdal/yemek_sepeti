<?php
require_once 'config.php';

session_start();

echo "=== OTURUM BİLGİLERİ ===\n\n";
echo "User ID: " . ($_SESSION['user_id'] ?? 'YOK') . "\n";
echo "Email: " . ($_SESSION['email'] ?? 'YOK') . "\n";
echo "Role: " . ($_SESSION['role'] ?? 'YOK') . "\n";
echo "Full Name: " . ($_SESSION['full_name'] ?? 'YOK') . "\n";

if (isset($_SESSION['user_id'])) {
    $stmt = $pdo->prepare("SELECT * FROM Users WHERE UserID = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo "\n=== VERITABANI BİLGİLERİ ===\n\n";
    print_r($user);
    
    // Bu kullanıcının restoranını kontrol et
    echo "\n=== RESTORAN BİLGİLERİ ===\n\n";
    $stmt = $pdo->prepare("SELECT * FROM Restaurants WHERE OwnerUserID = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $restaurant = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($restaurant) {
        echo "Restoran bulundu:\n";
        print_r($restaurant);
    } else {
        echo "Bu kullanıcıya ait restoran yok!\n\n";
        echo "Usta Dönerci restoranını bu kullanıcıya atayalım mı?\n";
        
        // Usta Dönerci'yi ata
        try {
            $stmt = $pdo->prepare("UPDATE Restaurants SET OwnerUserID = ? WHERE RestaurantID = 38");
            $stmt->execute([$_SESSION['user_id']]);
            echo "✓ Usta Dönerci (ID: 38) bu kullanıcıya atandı!\n";
            
            // Kontrol et
            $stmt = $pdo->prepare("SELECT * FROM Restaurants WHERE RestaurantID = 38");
            $stmt->execute();
            $updatedRestaurant = $stmt->fetch(PDO::FETCH_ASSOC);
            print_r($updatedRestaurant);
            
        } catch (PDOException $e) {
            echo "Hata: " . $e->getMessage() . "\n";
        }
    }
}
