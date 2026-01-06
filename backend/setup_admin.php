<?php
/**
 * Admin Hesapları Kurulum ve Test Scripti
 * Bu script admin tablolarını günceller ve admin hesaplarını oluşturur
 */

require_once 'config.php';

echo "<h2>Admin Hesapları Kurulum Scripti</h2>\n";
echo "<pre>\n";

try {
    // 1. Users tablosuna UserType kolonu var mı kontrol et
    echo "1. Users tablosunu kontrol ediyorum...\n";
    $stmt = $pdo->query("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'Users' AND COLUMN_NAME = 'UserType'");
    $hasUserType = $stmt->fetch();
    
    if (!$hasUserType) {
        echo "   - UserType kolonu ekleniyor...\n";
        $pdo->exec("ALTER TABLE Users ADD UserType NVARCHAR(20) DEFAULT 'Customer' NOT NULL");
        echo "   ✓ UserType kolonu eklendi\n";
    } else {
        echo "   ✓ UserType kolonu zaten mevcut\n";
    }
    
    // 2. Restaurants tablosuna OwnerUserID kolonu var mı kontrol et
    echo "\n2. Restaurants tablosunu kontrol ediyorum...\n";
    $stmt = $pdo->query("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'Restaurants' AND COLUMN_NAME = 'OwnerUserID'");
    $hasOwnerUserID = $stmt->fetch();
    
    if (!$hasOwnerUserID) {
        echo "   - OwnerUserID kolonu ekleniyor...\n";
        $pdo->exec("ALTER TABLE Restaurants ADD OwnerUserID INT NULL");
        echo "   ✓ OwnerUserID kolonu eklendi\n";
    } else {
        echo "   ✓ OwnerUserID kolonu zaten mevcut\n";
    }
    
    // 3. Restaurants tablosuna Description kolonu var mı kontrol et
    $stmt = $pdo->query("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'Restaurants' AND COLUMN_NAME = 'Description'");
    $hasDescription = $stmt->fetch();
    
    if (!$hasDescription) {
        echo "   - Description kolonu ekleniyor...\n";
        $pdo->exec("ALTER TABLE Restaurants ADD Description NVARCHAR(500) NULL");
        echo "   ✓ Description kolonu eklendi\n";
    } else {
        echo "   ✓ Description kolonu zaten mevcut\n";
    }
    
    // 4. Restaurants tablosuna IsOpen kolonu var mı kontrol et
    $stmt = $pdo->query("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'Restaurants' AND COLUMN_NAME = 'IsOpen'");
    $hasIsOpen = $stmt->fetch();
    
    if (!$hasIsOpen) {
        echo "   - IsOpen kolonu ekleniyor...\n";
        $pdo->exec("ALTER TABLE Restaurants ADD IsOpen BIT DEFAULT 1");
        echo "   ✓ IsOpen kolonu eklendi\n";
    } else {
        echo "   ✓ IsOpen kolonu zaten mevcut\n";
    }
    
    // 5. MenuItems tablosuna ImageURL kolonu var mı kontrol et
    echo "\n3. MenuItems tablosunu kontrol ediyorum...\n";
    $stmt = $pdo->query("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'MenuItems' AND COLUMN_NAME = 'ImageURL'");
    $hasImageURL = $stmt->fetch();
    
    if (!$hasImageURL) {
        echo "   - ImageURL kolonu ekleniyor...\n";
        $pdo->exec("ALTER TABLE MenuItems ADD ImageURL NVARCHAR(500) NULL");
        echo "   ✓ ImageURL kolonu eklendi\n";
    } else {
        echo "   ✓ ImageURL kolonu zaten mevcut\n";
    }
    
    // 6. Categories tablosu var mı kontrol et
    echo "\n4. Categories tablosunu kontrol ediyorum...\n";
    $stmt = $pdo->query("SELECT * FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_NAME = 'Categories'");
    $hasCategories = $stmt->fetch();
    
    if (!$hasCategories) {
        echo "   - Categories tablosu oluşturuluyor...\n";
        $pdo->exec("
            CREATE TABLE Categories (
                CategoryID INT PRIMARY KEY IDENTITY(1,1),
                Name NVARCHAR(50) UNIQUE NOT NULL,
                IsActive BIT DEFAULT 1,
                CreatedDate DATETIME DEFAULT GETDATE()
            )
        ");
        echo "   ✓ Categories tablosu oluşturuldu\n";
        
        echo "   - Varsayılan kategoriler ekleniyor...\n";
        $pdo->exec("
            INSERT INTO Categories (Name) VALUES 
            ('Burger'), ('Pizza'), ('Kebap'), ('Çiğ Köfte'), 
            ('Balık'), ('Tatlı'), ('Kahve'), ('Döner'), ('Ev Yemekleri'), ('Pide')
        ");
        echo "   ✓ Varsayılan kategoriler eklendi\n";
    } else {
        echo "   ✓ Categories tablosu zaten mevcut\n";
    }
    
    // 7. Admin kullanıcısını oluştur
    echo "\n5. Admin kullanıcısını kontrol ediyorum...\n";
    $stmt = $pdo->prepare("SELECT * FROM Users WHERE Email = ? AND UserType = 'Admin'");
    $stmt->execute(['admin@yemeksepeti.com']);
    $adminUser = $stmt->fetch();
    
    if (!$adminUser) {
        echo "   - Admin kullanıcısı oluşturuluyor...\n";
        $stmt = $pdo->prepare("
            INSERT INTO Users (FullName, Email, Phone, Password, UserType, IsActive)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute(['Sistem Yöneticisi', 'admin@yemeksepeti.com', '05001234567', 'admin123', 'Admin', 1]);
        echo "   ✓ Admin kullanıcısı oluşturuldu\n";
        echo "   Giriş Bilgileri:\n";
        echo "   Email: admin@yemeksepeti.com\n";
        echo "   Şifre: admin123\n";
    } else {
        echo "   ✓ Admin kullanıcısı zaten mevcut\n";
        echo "   Giriş Bilgileri:\n";
        echo "   Email: admin@yemeksepeti.com\n";
        echo "   Şifre: admin123\n";
        echo "   UserID: " . $adminUser['UserID'] . "\n";
    }
    
    // 8. Tüm admin kullanıcılarını listele
    echo "\n6. Tüm Admin Kullanıcıları:\n";
    $stmt = $pdo->query("SELECT UserID, FullName, Email, Phone, IsActive FROM Users WHERE UserType = 'Admin'");
    $admins = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (count($admins) > 0) {
        foreach ($admins as $admin) {
            echo "   - " . $admin['FullName'] . " (" . $admin['Email'] . ") - ID: " . $admin['UserID'];
            echo " [" . ($admin['IsActive'] ? 'Aktif' : 'Pasif') . "]\n";
        }
    } else {
        echo "   Hiç admin kullanıcısı bulunamadı!\n";
    }
    
    // 9. Restoran sahipleri için UserType güncelle
    echo "\n7. Restoran sahiplerini kontrol ediyorum...\n";
    $stmt = $pdo->query("
        SELECT u.UserID, u.FullName, u.Email, u.UserType, r.Name as RestaurantName
        FROM Users u
        INNER JOIN Restaurants r ON r.OwnerUserID = u.UserID
        WHERE u.UserType = 'Customer'
    ");
    $restaurantOwners = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (count($restaurantOwners) > 0) {
        echo "   - " . count($restaurantOwners) . " restoran sahibi 'RestaurantOwner' olarak güncelleniyor...\n";
        $updateStmt = $pdo->prepare("UPDATE Users SET UserType = 'RestaurantOwner' WHERE UserID = ?");
        foreach ($restaurantOwners as $owner) {
            $updateStmt->execute([$owner['UserID']]);
            echo "   ✓ " . $owner['FullName'] . " (" . $owner['RestaurantName'] . ")\n";
        }
    } else {
        echo "   ✓ Güncellenecek restoran sahibi bulunamadı\n";
    }
    
    // 10. Özet bilgi
    echo "\n═══════════════════════════════════════════════════\n";
    echo "Kurulum Tamamlandı!\n";
    echo "═══════════════════════════════════════════════════\n\n";
    
    echo "Admin Paneline Giriş:\n";
    echo "URL: http://localhost:8000/admin_login.php\n";
    echo "Email: admin@yemeksepeti.com\n";
    echo "Şifre: admin123\n\n";
    
    // Kullanıcı istatistikleri
    $stmt = $pdo->query("SELECT UserType, COUNT(*) as Count FROM Users GROUP BY UserType");
    $stats = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Kullanıcı İstatistikleri:\n";
    foreach ($stats as $stat) {
        echo "  " . $stat['UserType'] . ": " . $stat['Count'] . "\n";
    }
    
} catch (PDOException $e) {
    echo "\n❌ HATA: " . $e->getMessage() . "\n";
    echo "Satır: " . $e->getLine() . "\n";
}

echo "\n</pre>";
?>
