-- Favoriler tablosunu oluştur
USE YemekSepetiDB;
GO

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
GO
