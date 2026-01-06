-- Şehr-i Palas - İçecekler Kategorisi Menü Ekleme
-- Tarih: 12 Aralık 2025
-- RestaurantID: 45 (Şehr-i Palas)
-- Category: İçecekler

DECLARE @RestaurantID INT = 45;
DECLARE @Category NVARCHAR(50) = N'İçecekler';

-- Coca-Cola (33 cl.)
IF NOT EXISTS (SELECT 1 FROM MenuItems WHERE RestaurantID = @RestaurantID AND Name = N'Coca-Cola (33 cl.)')
BEGIN
    INSERT INTO MenuItems (RestaurantID, Category, Name, Description, Price, ImageURL, IsAvailable)
    VALUES (@RestaurantID, @Category, N'Coca-Cola (33 cl.)', N'Kutu içecek', 80.00, 'teneke.jpg', 1);
    PRINT 'Coca-Cola (33 cl.) eklendi';
END
ELSE
    PRINT 'Coca-Cola (33 cl.) zaten mevcut';

-- Coca-Cola Şekersiz (33 cl.)
IF NOT EXISTS (SELECT 1 FROM MenuItems WHERE RestaurantID = @RestaurantID AND Name = N'Coca-Cola Şekersiz (33 cl.)')
BEGIN
    INSERT INTO MenuItems (RestaurantID, Category, Name, Description, Price, ImageURL, IsAvailable)
    VALUES (@RestaurantID, @Category, N'Coca-Cola Şekersiz (33 cl.)', N'Kutu içecek', 80.00, 'zero.jpg', 1);
    PRINT 'Coca-Cola Şekersiz (33 cl.) eklendi';
END
ELSE
    PRINT 'Coca-Cola Şekersiz (33 cl.) zaten mevcut';

-- Fanta (33 cl.)
IF NOT EXISTS (SELECT 1 FROM MenuItems WHERE RestaurantID = @RestaurantID AND Name = N'Fanta (33 cl.)')
BEGIN
    INSERT INTO MenuItems (RestaurantID, Category, Name, Description, Price, ImageURL, IsAvailable)
    VALUES (@RestaurantID, @Category, N'Fanta (33 cl.)', N'Kutu içecek', 80.00, 'fanta.jpg', 1);
    PRINT 'Fanta (33 cl.) eklendi';
END
ELSE
    PRINT 'Fanta (33 cl.) zaten mevcut';

-- Sprite (33 cl.)
IF NOT EXISTS (SELECT 1 FROM MenuItems WHERE RestaurantID = @RestaurantID AND Name = N'Sprite (33 cl.)')
BEGIN
    INSERT INTO MenuItems (RestaurantID, Category, Name, Description, Price, ImageURL, IsAvailable)
    VALUES (@RestaurantID, @Category, N'Sprite (33 cl.)', N'Kutu içecek', 80.00, 'sprite.jpg', 1);
    PRINT 'Sprite (33 cl.) eklendi';
END
ELSE
    PRINT 'Sprite (33 cl.) zaten mevcut';

-- Fuse Tea (33 cl.)
IF NOT EXISTS (SELECT 1 FROM MenuItems WHERE RestaurantID = @RestaurantID AND Name = N'Fuse Tea (33 cl.)')
BEGIN
    INSERT INTO MenuItems (RestaurantID, Category, Name, Description, Price, ImageURL, IsAvailable)
    VALUES (@RestaurantID, @Category, N'Fuse Tea (33 cl.)', N'Kutu içecek', 80.00, 'fusetea.jpg', 1);
    PRINT 'Fuse Tea (33 cl.) eklendi';
END
ELSE
    PRINT 'Fuse Tea (33 cl.) zaten mevcut';

-- Cappy (33 cl.)
IF NOT EXISTS (SELECT 1 FROM MenuItems WHERE RestaurantID = @RestaurantID AND Name = N'Cappy (33 cl.)')
BEGIN
    INSERT INTO MenuItems (RestaurantID, Category, Name, Description, Price, ImageURL, IsAvailable)
    VALUES (@RestaurantID, @Category, N'Cappy (33 cl.)', N'Kutu içecek', 80.00, 'cappy.jpg', 1);
    PRINT 'Cappy (33 cl.) eklendi';
END
ELSE
    PRINT 'Cappy (33 cl.) zaten mevcut';

-- Eklenen menüleri göster
SELECT m.MenuItemID, m.Name, m.Description, m.Price, m.Category, r.Name as RestaurantName
FROM MenuItems m
JOIN Restaurants r ON m.RestaurantID = r.RestaurantID
WHERE m.RestaurantID = @RestaurantID AND m.Category = @Category
ORDER BY m.Name;

PRINT 'Şehr-i Palas - İçecekler menüsü ekleme işlemi tamamlandı!';
