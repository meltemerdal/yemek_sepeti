-- Şehr-i Palas - Salatalar Kategorisi Menü Ekleme
-- Tarih: 12 Aralık 2025
-- RestaurantID: 45 (Şehr-i Palas)
-- Category: Salatalar

DECLARE @RestaurantID INT = 45;
DECLARE @Category NVARCHAR(50) = N'Salatalar';

-- Çoban Salata
IF NOT EXISTS (SELECT 1 FROM MenuItems WHERE RestaurantID = @RestaurantID AND Name = N'Çoban Salata')
BEGIN
    INSERT INTO MenuItems (RestaurantID, Category, Name, Description, Price, ImageURL, IsAvailable)
    VALUES (@RestaurantID, @Category, N'Çoban Salata', N'Domates, salatalık, köy biberi, maydanoz, akdeniz yeşilliği, kuruton ekmeği, özel sos', 172.00, 'cobansalata.jpg', 1);
    PRINT 'Çoban Salata eklendi';
END
ELSE
    PRINT 'Çoban Salata zaten mevcut';

-- Akdeniz Salata
IF NOT EXISTS (SELECT 1 FROM MenuItems WHERE RestaurantID = @RestaurantID AND Name = N'Akdeniz Salata')
BEGIN
    INSERT INTO MenuItems (RestaurantID, Category, Name, Description, Price, ImageURL, IsAvailable)
    VALUES (@RestaurantID, @Category, N'Akdeniz Salata', N'Akdeniz yeşilliği, dere otu, domates, salatalık, kırmızı soğan, beyaz peynir, kuruton ekmeği, özel sos', 232.00, 'akdenizsalata.jpg', 1);
    PRINT 'Akdeniz Salata eklendi';
END
ELSE
    PRINT 'Akdeniz Salata zaten mevcut';

-- Mantarlı Salata
IF NOT EXISTS (SELECT 1 FROM MenuItems WHERE RestaurantID = @RestaurantID AND Name = N'Mantarlı Salata')
BEGIN
    INSERT INTO MenuItems (RestaurantID, Category, Name, Description, Price, ImageURL, IsAvailable)
    VALUES (@RestaurantID, @Category, N'Mantarlı Salata', N'Akdeniz yeşiliği, mantar, mısır, köy biberi, domates, kapya biberi, kuruton ekmeği, özel sos', 244.00, 'mantarlisalata.jpg', 1);
    PRINT 'Mantarlı Salata eklendi';
END
ELSE
    PRINT 'Mantarlı Salata zaten mevcut';

-- Ton Balıklı Salata
IF NOT EXISTS (SELECT 1 FROM MenuItems WHERE RestaurantID = @RestaurantID AND Name = N'Ton Balıklı Salata')
BEGIN
    INSERT INTO MenuItems (RestaurantID, Category, Name, Description, Price, ImageURL, IsAvailable)
    VALUES (@RestaurantID, @Category, N'Ton Balıklı Salata', N'Akdeniz yeşilliği, ton balığı, mısır, dilimli siyah zeytin, domates, kuruton ekmeği, özel sos', 276.00, 'tonbaliklisalata.jpg', 1);
    PRINT 'Ton Balıklı Salata eklendi';
END
ELSE
    PRINT 'Ton Balıklı Salata zaten mevcut';

-- Eklenen menüleri göster
SELECT m.MenuItemID, m.Name, m.Description, m.Price, m.Category, r.Name as RestaurantName
FROM MenuItems m
JOIN Restaurants r ON m.RestaurantID = r.RestaurantID
WHERE m.RestaurantID = @RestaurantID AND m.Category = @Category
ORDER BY m.Name;

PRINT 'Şehr-i Palas - Salatalar menüsü ekleme işlemi tamamlandı!';
