<?php
require_once __DIR__ . '/config.php';

try {
    $sql = "
    IF NOT EXISTS (SELECT * FROM sys.tables WHERE name = 'Favorites')
    BEGIN
        CREATE TABLE Favorites (
            FavoriteID INT PRIMARY KEY IDENTITY(1,1),
            UserID INT NOT NULL,
            RestaurantID INT NOT NULL,
            CreatedAt DATETIME DEFAULT GETDATE(),
            FOREIGN KEY (UserID) REFERENCES Users(UserID),
            FOREIGN KEY (RestaurantID) REFERENCES Restaurants(RestaurantID),
            CONSTRAINT UQ_User_Restaurant UNIQUE (UserID, RestaurantID)
        );
        PRINT 'Favorites tablosu başarıyla oluşturuldu.';
    END
    ELSE
    BEGIN
        PRINT 'Favorites tablosu zaten mevcut.';
    END
    ";
    
    $pdo->exec($sql);
    echo "Favorites tablosu başarıyla oluşturuldu veya zaten mevcut.\n";
    
} catch (PDOException $e) {
    echo "Hata: " . $e->getMessage() . "\n";
}
?>
