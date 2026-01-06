<?php
require_once 'backend/config.php';

try {
    echo "MenuItems tablosu güncelleniyor...\n\n";
    
    // 1. Yeni CategoryID kolonu ekle
    echo "1. CategoryID kolonu ekleniyor...\n";
    $pdo->exec("
        IF NOT EXISTS (SELECT * FROM sys.columns WHERE object_id = OBJECT_ID('MenuItems') AND name = 'CategoryID')
        BEGIN
            ALTER TABLE MenuItems ADD CategoryID INT NULL;
            PRINT 'CategoryID kolonu eklendi';
        END
        ELSE
        BEGIN
            PRINT 'CategoryID kolonu zaten mevcut';
        END
    ");
    echo "   ✓ CategoryID kolonu hazır\n\n";
    
    // 2. Mevcut Category değerlerini CategoryID'ye dönüştür
    echo "2. Mevcut veriler dönüştürülüyor...\n";
    $stmt = $pdo->query("SELECT DISTINCT m.RestaurantID, m.Category FROM MenuItems m WHERE m.Category IS NOT NULL AND m.CategoryID IS NULL");
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($items as $item) {
        // Bu restoran için bu kategorinin ID'sini bul veya oluştur
        $stmtCat = $pdo->prepare("SELECT CategoryID FROM MenuCategories WHERE RestaurantID = ? AND CategoryName = ?");
        $stmtCat->execute([$item['RestaurantID'], $item['Category']]);
        $category = $stmtCat->fetch(PDO::FETCH_ASSOC);
        
        if (!$category) {
            // Kategori yoksa oluştur
            $stmtInsert = $pdo->prepare("INSERT INTO MenuCategories (RestaurantID, CategoryName) VALUES (?, ?)");
            $stmtInsert->execute([$item['RestaurantID'], $item['Category']]);
            $categoryId = $pdo->lastInsertId();
        } else {
            $categoryId = $category['CategoryID'];
        }
        
        // MenuItems'daki CategoryID'yi güncelle
        $stmtUpdate = $pdo->prepare("UPDATE MenuItems SET CategoryID = ? WHERE RestaurantID = ? AND Category = ? AND CategoryID IS NULL");
        $stmtUpdate->execute([$categoryId, $item['RestaurantID'], $item['Category']]);
    }
    echo "   ✓ Mevcut veriler dönüştürüldü\n\n";
    
    // 3. Category kolonunu NULL yapılabilir hale getir (silmeden önce)
    echo "3. Eski Category kolonu temizleniyor...\n";
    $pdo->exec("ALTER TABLE MenuItems ALTER COLUMN Category NVARCHAR(50) NULL");
    echo "   ✓ Category kolonu NULL yapıldı\n\n";
    
    // 4. Foreign key ekle
    echo "4. Foreign Key ekleniyor...\n";
    $pdo->exec("
        IF NOT EXISTS (SELECT * FROM sys.foreign_keys WHERE name = 'FK_MenuItems_MenuCategories')
        BEGIN
            ALTER TABLE MenuItems ADD CONSTRAINT FK_MenuItems_MenuCategories 
            FOREIGN KEY (CategoryID) REFERENCES MenuCategories(CategoryID);
            PRINT 'Foreign Key eklendi';
        END
        ELSE
        BEGIN
            PRINT 'Foreign Key zaten mevcut';
        END
    ");
    echo "   ✓ Foreign Key eklendi\n\n";
    
    echo "✓✓✓ Veritabanı başarıyla güncellendi! ✓✓✓\n";
    echo "\nArtık MenuItems tablosunda:\n";
    echo "- CategoryID (INT, FK) kullanılıyor\n";
    echo "- Category (NVARCHAR) eski veri için NULL olarak tutulabilir\n";
    
} catch (PDOException $e) {
    echo "HATA: " . $e->getMessage() . "\n";
}
