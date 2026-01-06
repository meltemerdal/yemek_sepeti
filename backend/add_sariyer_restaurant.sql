-- Sarıyer Börekçisi Restoran ve Menü Ekleme
-- Restaurant ID: 59

USE YemekSepetiDB;
GO

-- Önce var mı kontrol et
IF NOT EXISTS (SELECT 1 FROM Restaurants WHERE RestaurantID = 59)
BEGIN
    -- Sarıyer Börekçisi'ni ekle
    SET IDENTITY_INSERT Restaurants ON;
    
    INSERT INTO Restaurants (RestaurantID, Name, Category, Address, Image, Rating, DeliveryTime, MinOrderAmount, IsOpen, IsActive, Description)
    VALUES (
        59,
        N'Sarıyer Börekçisi',
        N'Kahvaltı & Börek',
        N'Sarıyer, Rumeli Hisarı Cad. No: 45, İstanbul',
        'sariyerborekcisi.jpg',
        4.60,
        25,
        45.00,
        1,
        1,
        N'Restoran Teslimatlı'
    );
    
    SET IDENTITY_INSERT Restaurants OFF;
    
    PRINT 'Sarıyer Börekçisi eklendi.';
END
ELSE
BEGIN
    -- Mevcut kaydı güncelle
    UPDATE Restaurants 
    SET Image = 'sariyerborekcisi.jpg',
        Name = N'Sarıyer Börekçisi',
        Category = N'Kahvaltı & Börek',
        Address = N'Sarıyer, Rumeli Hisarı Cad. No: 45, İstanbul',
        Rating = 4.60,
        DeliveryTime = 25,
        MinOrderAmount = 45.00,
        IsOpen = 1,
        IsActive = 1,
        Description = N'Restoran Teslimatlı'
    WHERE RestaurantID = 59;
    
    PRINT 'Sarıyer Börekçisi güncellendi.';
END
GO
