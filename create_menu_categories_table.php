<?php
require_once 'backend/config.php';

try {
    echo "MenuCategories tablosu oluşturuluyor...\n\n";
    
    // MenuCategories tablosunu oluştur
    $pdo->exec("
        IF NOT EXISTS (SELECT * FROM sys.tables WHERE name = 'MenuCategories')
        BEGIN
            CREATE TABLE MenuCategories (
                CategoryID INT PRIMARY KEY IDENTITY(1,1),
                RestaurantID INT NOT NULL,
                CategoryName NVARCHAR(100) NOT NULL,
                CreatedDate DATETIME DEFAULT GETDATE(),
                FOREIGN KEY (RestaurantID) REFERENCES Restaurants(RestaurantID)
            );
            PRINT 'MenuCategories tablosu oluşturuldu';
        END
        ELSE
        BEGIN
            PRINT 'MenuCategories tablosu zaten mevcut';
        END
    ");
    
    echo "✓ MenuCategories tablosu hazır\n\n";
    echo "✓✓✓ Veritabanı başarıyla güncellendi! ✓✓✓\n";
    
} catch (PDOException $e) {
    echo "HATA: " . $e->getMessage() . "\n";
}
