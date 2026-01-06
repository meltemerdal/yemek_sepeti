-- Şehr-i Palas - Sandviç Görsellerini Güncelle
-- Tarih: 12 Aralık 2025

DECLARE @RestaurantID INT = 45;

-- Beyaz Peynirli Sandviç görselini güncelle
UPDATE MenuItems 
SET ImageURL = 'beyazpeynirlisandvic.jpg'
WHERE RestaurantID = @RestaurantID 
AND Name = N'Beyaz Peynirli Sandviç';

-- Kaşarlı Sandviç görselini güncelle
UPDATE MenuItems 
SET ImageURL = 'kasarlisandvic.jpg'
WHERE RestaurantID = @RestaurantID 
AND Name = N'Kaşarlı Sandviç';

-- Karışık Sandviç görselini güncelle
UPDATE MenuItems 
SET ImageURL = 'karisiksandvic.jpg'
WHERE RestaurantID = @RestaurantID 
AND Name = N'Karışık Sandviç';

PRINT 'Sandviç görselleri güncellendi';

-- Güncellenmiş menüleri göster
SELECT MenuItemID, Name, ImageURL, Category
FROM MenuItems
WHERE RestaurantID = @RestaurantID AND Category = N'Sandviçler';
