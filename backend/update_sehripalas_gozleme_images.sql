-- Şehr-i Palas - Gözleme Görsellerini Güncelle
-- Tarih: 12 Aralık 2025

DECLARE @RestaurantID INT = 45;

-- Tüm gözlemelerin görselini güncelle
UPDATE MenuItems 
SET ImageURL = 'gozleme.jpg'
WHERE RestaurantID = @RestaurantID 
AND Category = N'Gözlemeler';

PRINT 'Gözleme görselleri güncellendi';

-- Güncellenmiş menüleri göster
SELECT MenuItemID, Name, ImageURL, Category
FROM MenuItems
WHERE RestaurantID = @RestaurantID AND Category = N'Gözlemeler';
