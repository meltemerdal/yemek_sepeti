-- Şehr-i Palas - Fırın Ürünleri Kategorisi Menü Ekleme
-- Tarih: 12 Aralık 2025
-- RestaurantID: 45 (Şehr-i Palas)
-- Category: Fırın Ürünleri

DECLARE @RestaurantID INT = 45;
DECLARE @Category NVARCHAR(50) = N'Fırın Ürünleri';

-- Odun Ateşinde Lahmacun
IF NOT EXISTS (SELECT 1 FROM MenuItems WHERE RestaurantID = @RestaurantID AND Name = N'Odun Ateşinde Lahmacun')
BEGIN
    INSERT INTO MenuItems (RestaurantID, Category, Name, Description, Price, ImageURL, IsAvailable)
    VALUES (@RestaurantID, @Category, N'Odun Ateşinde Lahmacun', N'Salata ve limon ile', 110.00, 'odunatesindelahmacun.jpg', 1);
    PRINT 'Odun Ateşinde Lahmacun eklendi';
END
ELSE
    PRINT 'Odun Ateşinde Lahmacun zaten mevcut';

-- Kaşarlı Pide
IF NOT EXISTS (SELECT 1 FROM MenuItems WHERE RestaurantID = @RestaurantID AND Name = N'Kaşarlı Pide')
BEGIN
    INSERT INTO MenuItems (RestaurantID, Category, Name, Description, Price, ImageURL, IsAvailable)
    VALUES (@RestaurantID, @Category, N'Kaşarlı Pide', N'Salata ile', 272.00, 'kasarlipide.jpg', 1);
    PRINT 'Kaşarlı Pide eklendi';
END
ELSE
    PRINT 'Kaşarlı Pide zaten mevcut';

-- Kıymalı Pide
IF NOT EXISTS (SELECT 1 FROM MenuItems WHERE RestaurantID = @RestaurantID AND Name = N'Kıymalı Pide')
BEGIN
    INSERT INTO MenuItems (RestaurantID, Category, Name, Description, Price, ImageURL, IsAvailable)
    VALUES (@RestaurantID, @Category, N'Kıymalı Pide', N'Salata ile', 304.00, 'kiymalipide.jpg', 1);
    PRINT 'Kıymalı Pide eklendi';
END
ELSE
    PRINT 'Kıymalı Pide zaten mevcut';

-- Sucuklu Pide
IF NOT EXISTS (SELECT 1 FROM MenuItems WHERE RestaurantID = @RestaurantID AND Name = N'Sucuklu Pide')
BEGIN
    INSERT INTO MenuItems (RestaurantID, Category, Name, Description, Price, ImageURL, IsAvailable)
    VALUES (@RestaurantID, @Category, N'Sucuklu Pide', N'Salata ile', 336.00, 'sucuklupide.jpg', 1);
    PRINT 'Sucuklu Pide eklendi';
END
ELSE
    PRINT 'Sucuklu Pide zaten mevcut';

-- Kuşbaşılı Pide
IF NOT EXISTS (SELECT 1 FROM MenuItems WHERE RestaurantID = @RestaurantID AND Name = N'Kuşbaşılı Pide')
BEGIN
    INSERT INTO MenuItems (RestaurantID, Category, Name, Description, Price, ImageURL, IsAvailable)
    VALUES (@RestaurantID, @Category, N'Kuşbaşılı Pide', N'Salata ile', 368.00, 'kusbasilipide.jpg', 1);
    PRINT 'Kuşbaşılı Pide eklendi';
END
ELSE
    PRINT 'Kuşbaşılı Pide zaten mevcut';

-- Karışık Pide
IF NOT EXISTS (SELECT 1 FROM MenuItems WHERE RestaurantID = @RestaurantID AND Name = N'Karışık Pide')
BEGIN
    INSERT INTO MenuItems (RestaurantID, Category, Name, Description, Price, ImageURL, IsAvailable)
    VALUES (@RestaurantID, @Category, N'Karışık Pide', N'Sucuk, kuşbaşı, pastırma', 480.00, 'karisikpide.jpg', 1);
    PRINT 'Karışık Pide eklendi';
END
ELSE
    PRINT 'Karışık Pide zaten mevcut';

-- Vejetaryen Pide
IF NOT EXISTS (SELECT 1 FROM MenuItems WHERE RestaurantID = @RestaurantID AND Name = N'Vejetaryen Pide')
BEGIN
    INSERT INTO MenuItems (RestaurantID, Category, Name, Description, Price, ImageURL, IsAvailable)
    VALUES (@RestaurantID, @Category, N'Vejetaryen Pide', N'Salata ile', 280.00, 'vejetaryenpide.jpg', 1);
    PRINT 'Vejetaryen Pide eklendi';
END
ELSE
    PRINT 'Vejetaryen Pide zaten mevcut';

-- Eklenen menüleri göster
SELECT m.MenuItemID, m.Name, m.Description, m.Price, m.Category, r.Name as RestaurantName
FROM MenuItems m
JOIN Restaurants r ON m.RestaurantID = r.RestaurantID
WHERE m.RestaurantID = @RestaurantID AND m.Category = @Category
ORDER BY m.Name;

PRINT 'Şehr-i Palas - Fırın Ürünleri menüsü ekleme işlemi tamamlandı!';
