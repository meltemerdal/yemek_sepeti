<?php
require_once 'config.php';

try {
    // Önce Şehr-i Palas için yeni bir RestaurantOwner kullanıcısı oluştur
    $email = 'sehripalas@gmail.com';
    $password = password_hash('123456', PASSWORD_DEFAULT);
    $fullName = 'Şehr-i Palas Yönetici';
    
    // Kullanıcı zaten var mı kontrol et
    $stmt = $pdo->prepare("SELECT UserID FROM Users WHERE Email = ?");
    $stmt->execute([$email]);
    $existingUser = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($existingUser) {
        $userId = $existingUser['UserID'];
        echo "Kullanıcı zaten mevcut. UserID: $userId<br>";
    } else {
        // Yeni kullanıcı oluştur
        $stmt = $pdo->prepare("
            INSERT INTO Users (Email, PasswordHash, FullName, UserType, CreatedAt)
            VALUES (?, ?, ?, 'RestaurantOwner', GETDATE())
        ");
        $stmt->execute([$email, $password, $fullName]);
        
        // Yeni kullanıcının ID'sini al
        $userId = $pdo->lastInsertId();
        echo "Yeni kullanıcı oluşturuldu. UserID: $userId<br>";
    }
    
    // Şehr-i Palas'ı bu kullanıcıya ata
    $stmt = $pdo->prepare("UPDATE Restaurants SET OwnerUserID = ? WHERE RestaurantID = 45");
    $stmt->execute([$userId]);
    
    echo "Şehr-i Palas (RestaurantID=45) kullanıcıya atandı!<br><br>";
    
    // Kontrol et
    $stmt = $pdo->prepare("
        SELECT r.RestaurantID, r.Name, r.OwnerUserID, u.Email, u.FullName
        FROM Restaurants r
        LEFT JOIN Users u ON r.OwnerUserID = u.UserID
        WHERE r.RestaurantID = 45
    ");
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo "<h3>Şehr-i Palas Bilgileri:</h3>";
    echo "RestaurantID: {$result['RestaurantID']}<br>";
    echo "Restoran Adı: {$result['Name']}<br>";
    echo "OwnerUserID: {$result['OwnerUserID']}<br>";
    echo "Owner Email: {$result['Email']}<br>";
    echo "Owner Adı: {$result['FullName']}<br>";
    
    echo "<br><h3>Giriş Bilgileri:</h3>";
    echo "Email: sehripalas@gmail.com<br>";
    echo "Şifre: 123456<br>";
    echo "<br>Bu bilgilerle <a href='/restaurant_login.php'>Restoran Login</a> sayfasından giriş yapabilirsiniz.";
    
} catch (Exception $e) {
    echo "Hata: " . $e->getMessage();
}
?>
