-- Şehr-i Palas - Gözlemeler Kategorisi Menü Ekleme
-- Tarih: 12 Aralık 2025
-- RestaurantID: 45 (Şehr-i Palas)
-- Category: Gözlemeler

DECLARE @RestaurantID INT = 45;
DECLARE @Category NVARCHAR(50) = N'Gözlemeler';

-- Patatesli Gözleme
IF NOT EXISTS (SELECT 1 FROM MenuItems WHERE RestaurantID = @RestaurantID AND Name = N'Patatesli Gözleme')
BEGIN
    INSERT INTO MenuItems (RestaurantID, Category, Name, Description, Price, ImageURL, IsAvailable)
    VALUES (@RestaurantID, @Category, N'Patatesli Gözleme', N'Patates kızartması, sos, turşu ile', 228.00, 'gozleme.jpg', 1);
    PRINT 'Patatesli Gözleme eklendi';
END
ELSE
    PRINT 'Patatesli Gözleme zaten mevcut';

-- Kıymalı Gözleme
IF NOT EXISTS (SELECT 1 FROM MenuItems WHERE RestaurantID = @RestaurantID AND Name = N'Kıymalı Gözleme')
BEGIN
    INSERT INTO MenuItems (RestaurantID, Category, Name, Description, Price, ImageURL, IsAvailable)
    VALUES (@RestaurantID, @Category, N'Kıymalı Gözleme', N'Patates kızartması, sos, turşu ile', 248.00, 'gozleme.jpg', 1);
    PRINT 'Kıymalı Gözleme eklendi';
END
ELSE
    PRINT 'Kıymalı Gözleme zaten mevcut';

-- Beyaz Peynirli Gözleme
IF NOT EXISTS (SELECT 1 FROM MenuItems WHERE RestaurantID = @RestaurantID AND Name = N'Beyaz Peynirli Gözleme')
BEGIN
    INSERT INTO MenuItems (RestaurantID, Category, Name, Description, Price, ImageURL, IsAvailable)
    VALUES (@RestaurantID, @Category, N'Beyaz Peynirli Gözleme', N'Patates kızartması, sos, turşu ile', 240.00, 'gozleme.jpg', 1);
    PRINT 'Beyaz Peynirli Gözleme eklendi';
END
ELSE
    PRINT 'Beyaz Peynirli Gözleme zaten mevcut';

-- Kaşarlı Gözleme
IF NOT EXISTS (SELECT 1 FROM MenuItems WHERE RestaurantID = @RestaurantID AND Name = N'Kaşarlı Gözleme')
BEGIN
    INSERT INTO MenuItems (RestaurantID, Category, Name, Description, Price, ImageURL, IsAvailable)
    VALUES (@RestaurantID, @Category, N'Kaşarlı Gözleme', N'Patates kızartması, sos, turşu ile', 240.00, 'gozleme.jpg', 1);
    PRINT 'Kaşarlı Gözleme eklendi';
END
ELSE
    PRINT 'Kaşarlı Gözleme zaten mevcut';

-- Eklenen menüleri göster
SELECT m.MenuItemID, m.Name, m.Description, m.Price, m.Category, r.Name as RestaurantName
FROM MenuItems m
JOIN Restaurants r ON m.RestaurantID = r.RestaurantID
WHERE m.RestaurantID = @RestaurantID AND m.Category = @Category
ORDER BY m.Name;

PRINT 'Şehr-i Palas - Gözlemeler menüsü ekleme işlemi tamamlandı!';
