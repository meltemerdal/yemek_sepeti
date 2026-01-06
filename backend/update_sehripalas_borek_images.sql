-- Şehr-i Palas - Börek Görsellerini Güncelleme
-- Tarih: 12 Aralık 2025
-- RestaurantID: 45 (Şehr-i Palas)

DECLARE @RestaurantID INT = 45;

-- Paçanga Böreği görselini güncelle
UPDATE MenuItems 
SET ImageURL = 'pacangaboregi.jpg'
WHERE RestaurantID = @RestaurantID 
  AND Name = N'Paçanga Böreği (3 Adet)'
  AND Category = N'Börekler';
PRINT 'Paçanga Böreği görseli güncellendi';

-- Peynirli Sigara Böreği görselini güncelle
UPDATE MenuItems 
SET ImageURL = 'peynirlisigaraboregi.jpg'
WHERE RestaurantID = @RestaurantID 
  AND Name = N'Peynirli Sigara Böreği (10 Adet)'
  AND Category = N'Börekler';
PRINT 'Peynirli Sigara Böreği görseli güncellendi';

-- Peynirli Su Böreği görselini güncelle
UPDATE MenuItems 
SET ImageURL = 'peynirlisigaraboregi.jpg'
WHERE RestaurantID = @RestaurantID 
  AND Name = N'Peynirli Su Böreği'
  AND Category = N'Börekler';
PRINT 'Peynirli Su Böreği görseli güncellendi';

-- Güncellenen kayıtları göster
SELECT MenuItemID, Name, ImageURL, Category
FROM MenuItems
WHERE RestaurantID = @RestaurantID AND Category = N'Börekler'
ORDER BY Name;

PRINT 'Şehr-i Palas - Börek görselleri güncelleme işlemi tamamlandı!';
