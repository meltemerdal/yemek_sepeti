-- Şehr-i Palas - Sandviçler Kategorisi Menü Ekleme
-- Tarih: 12 Aralık 2025
-- RestaurantID: 45 (Şehr-i Palas)
-- Category: Sandviçler

DECLARE @RestaurantID INT = 45;
DECLARE @Category NVARCHAR(50) = N'Sandviçler';

-- Beyaz Peynirli Sandviç
IF NOT EXISTS (SELECT 1 FROM MenuItems WHERE RestaurantID = @RestaurantID AND Name = N'Beyaz Peynirli Sandviç')
BEGIN
    INSERT INTO MenuItems (RestaurantID, Category, Name, Description, Price, ImageURL, IsAvailable)
    VALUES (@RestaurantID, @Category, N'Beyaz Peynirli Sandviç', N'Patates kızartması, sos ile', 92.00, 'beyazpeynirlisandvic.jpg', 1);
    PRINT 'Beyaz Peynirli Sandviç eklendi';
END
ELSE
    PRINT 'Beyaz Peynirli Sandviç zaten mevcut';

-- Kaşarlı Sandviç
IF NOT EXISTS (SELECT 1 FROM MenuItems WHERE RestaurantID = @RestaurantID AND Name = N'Kaşarlı Sandviç')
BEGIN
    INSERT INTO MenuItems (RestaurantID, Category, Name, Description, Price, ImageURL, IsAvailable)
    VALUES (@RestaurantID, @Category, N'Kaşarlı Sandviç', N'Patates kızartması, sos ile', 92.00, 'kasarlisandvic.jpg', 1);
    PRINT 'Kaşarlı Sandviç eklendi';
END
ELSE
    PRINT 'Kaşarlı Sandviç zaten mevcut';

-- Karışık Sandviç
IF NOT EXISTS (SELECT 1 FROM MenuItems WHERE RestaurantID = @RestaurantID AND Name = N'Karışık Sandviç')
BEGIN
    INSERT INTO MenuItems (RestaurantID, Category, Name, Description, Price, ImageURL, IsAvailable)
    VALUES (@RestaurantID, @Category, N'Karışık Sandviç', N'Kaşar peyniri, domates, marul, salam. Patates kızartması, sos ile', 100.00, 'karisiksandvic.jpg', 1);
    PRINT 'Karışık Sandviç eklendi';
END
ELSE
    PRINT 'Karışık Sandviç zaten mevcut';

-- Eklenen menüleri göster
SELECT m.MenuItemID, m.Name, m.Description, m.Price, m.Category, r.Name as RestaurantName
FROM MenuItems m
JOIN Restaurants r ON m.RestaurantID = r.RestaurantID
WHERE m.RestaurantID = @RestaurantID AND m.Category = @Category
ORDER BY m.Name;

PRINT 'Şehr-i Palas - Sandviçler menüsü ekleme işlemi tamamlandı!';
