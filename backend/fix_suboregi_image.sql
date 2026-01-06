-- Peynirli Su Böreği Görselini Güncelle
-- RestaurantID: 45 (Şehr-i Palas)

DECLARE @RestaurantID INT = 45;

-- Mevcut durumu göster
SELECT MenuItemID, Name, ImageURL, Category
FROM MenuItems
WHERE RestaurantID = @RestaurantID 
  AND Name LIKE N'%Su Böreği%'
  AND Category = N'Börekler';

-- Görseli güncelle
UPDATE MenuItems 
SET ImageURL = 'peynirlisigaraboregi.jpg'
WHERE RestaurantID = @RestaurantID 
  AND Name LIKE N'%Su Böreği%'
  AND Category = N'Börekler';

PRINT 'Peynirli Su Böreği görseli güncellendi';

-- Güncellenmiş durumu göster
SELECT MenuItemID, Name, ImageURL, Category
FROM MenuItems
WHERE RestaurantID = @RestaurantID 
  AND Name LIKE N'%Su Böreği%'
  AND Category = N'Börekler';
