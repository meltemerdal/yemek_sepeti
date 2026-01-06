-- Şehr-i Palas - Börekler Kategorisi Menü Ekleme
-- Tarih: 12 Aralık 2025
-- RestaurantID: 45 (Şehr-i Palas)
-- Category: Börekler

DECLARE @RestaurantID INT = 45;
DECLARE @Category NVARCHAR(50) = N'Börekler';

-- Peynirli Sigara Böreği (10 Adet)
IF NOT EXISTS (SELECT 1 FROM MenuItems WHERE RestaurantID = @RestaurantID AND Name = N'Peynirli Sigara Böreği (10 Adet)')
BEGIN
    INSERT INTO MenuItems (RestaurantID, Category, Name, Description, Price, ImageURL, IsAvailable)
    VALUES (@RestaurantID, @Category, N'Peynirli Sigara Böreği (10 Adet)', N'Patates kızartması, sos, turşu ile', 120.00, 'peynirlisigaraboregi.jpg', 1);
    PRINT 'Peynirli Sigara Böreği (10 Adet) eklendi';
END
ELSE
    PRINT 'Peynirli Sigara Böreği (10 Adet) zaten mevcut';

-- Peynirli Su Böreği
IF NOT EXISTS (SELECT 1 FROM MenuItems WHERE RestaurantID = @RestaurantID AND Name = N'Peynirli Su Böreği')
BEGIN
    INSERT INTO MenuItems (RestaurantID, Category, Name, Description, Price, ImageURL, IsAvailable)
    VALUES (@RestaurantID, @Category, N'Peynirli Su Böreği', N'3 dilim. Turşu, salata ile', 124.00, 'peynirlisigaraboregi.jpg', 1);
    PRINT 'Peynirli Su Böreği eklendi';
END
ELSE
    PRINT 'Peynirli Su Böreği zaten mevcut';

-- Paçanga Böreği (3 Adet)
IF NOT EXISTS (SELECT 1 FROM MenuItems WHERE RestaurantID = @RestaurantID AND Name = N'Paçanga Böreği (3 Adet)')
BEGIN
    INSERT INTO MenuItems (RestaurantID, Category, Name, Description, Price, ImageURL, IsAvailable)
    VALUES (@RestaurantID, @Category, N'Paçanga Böreği (3 Adet)', N'Pastırma, sucuk, salam, kaşar peyniri, biber. Patates kızartması, sos, turşu ile', 136.00, 'pacangaboregi.jpg', 1);
    PRINT 'Paçanga Böreği (3 Adet) eklendi';
END
ELSE
    PRINT 'Paçanga Böreği (3 Adet) zaten mevcut';

-- Eklenen menüleri göster
SELECT m.MenuItemID, m.Name, m.Description, m.Price, m.Category, r.Name as RestaurantName
FROM MenuItems m
JOIN Restaurants r ON m.RestaurantID = r.RestaurantID
WHERE m.RestaurantID = @RestaurantID AND m.Category = @Category
ORDER BY m.Name;

PRINT 'Şehr-i Palas - Börekler menüsü ekleme işlemi tamamlandı!';
