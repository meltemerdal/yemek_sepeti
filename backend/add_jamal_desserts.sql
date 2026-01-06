-- Jamal - Tatlılar (Desserts) ekleme scripti
USE YemekSepetiDB;
GO

-- Jamal restoranının ID'sini bul
DECLARE @RestaurantID INT;
SELECT TOP 1 @RestaurantID = RestaurantID FROM Restaurants WHERE Name LIKE N'%Jamal%';

IF @RestaurantID IS NULL
BEGIN
    PRINT 'Jamal adlı restoran bulunamadı. Lütfen restoran adını kontrol edin.';
    RETURN;
END

-- Eski 'Tatlılar' kayıtlarını sil
DELETE FROM MenuItems
WHERE RestaurantID = @RestaurantID AND Category = N'Tatlılar';

PRINT 'Eski Tatlılar kayıtları silindi (Jamal).';

-- Aşağıdaki ImageURL değerlerini frontend/images klasöründeki dosya adlarıyla eşleştirin.
-- Mevcut eşlemeler (varsayılan olarak):
-- Çilek Rüyası -> cileklipasta.jpg
-- Profiterol -> profiterol.jpg
-- Supangle -> supangle.jpg (dosya yoksa ekleyin)
-- Tiramisu -> tiramisu.jpg
-- Aşure -> asure.jpg (dosya yoksa ekleyin)
-- Sütlaç -> sutlac.jpg

INSERT INTO MenuItems (RestaurantID, Name, Description, Price, Category, ImageURL, IsAvailable)
VALUES
(@RestaurantID, N'Çilek Rüyası', N'Taze çilek ve kremalı tatlı', 180.00, N'Tatlılar', 'cileklipasta.jpg', 1),
(@RestaurantID, N'Profiterol', N'Çikolatalı profiterol', 168.00, N'Tatlılar', 'profiterol.jpg', 1),
(@RestaurantID, N'Supangle', N'Kakaolu muhallebi tipi tatlı (supangle)', 168.00, N'Tatlılar', 'supangle.jpg', 1),
(@RestaurantID, N'Tiramisu', N'Klasik tiramisu', 180.00, N'Tatlılar', 'tiramisu.jpg', 1),
(@RestaurantID, N'Aşure', N'Geleneksel aşure', 175.00, N'Tatlılar', 'asure.jpg', 1),
(@RestaurantID, N'Sütlaç', N'Fırınlanmış sütlaç', 168.00, N'Tatlılar', 'sutlac.jpg', 1);

GO

PRINT 'Jamal için Tatlılar başarıyla eklendi (dosya çalıştırıldığında).';
