-- Domino's Pizza Dürümler Menüsü

USE YemekSepetiDB;
GO

-- RestaurantID'yi bul (Domino's Pizza için)
DECLARE @RestaurantID INT;
SELECT @RestaurantID = RestaurantID FROM Restaurants WHERE Name LIKE '%Domino%';

-- 1. Bol Malzemeli Dürümos
INSERT INTO MenuItems (RestaurantID, Name, Description, Price, Category, ImageURL, IsAvailable)
VALUES (@RestaurantID, N'Bol Malzemeli Dürümos', N'Mozzarella peyniri, sucuk, sosis, jambon, mantar, mısır, siyah zeytin, mayonez (Malzeme değişimi yapılmamaktadır.)', 160.00, N'Dürümler', 'durumos.jpg', 1);

-- 2. Tavuklu Dürümos
INSERT INTO MenuItems (RestaurantID, Name, Description, Price, Category, ImageURL, IsAvailable)
VALUES (@RestaurantID, N'Tavuklu Dürümos', N'Mozzarella peyniri, tavuk parçaları, közlenmiş kırmızıbiber, mantar, yöresel lezzetler baharatı (Malzeme değişimi yapılmamaktadır.)', 160.00, N'Dürümler', 'tavukludurumos.jpg', 1);

-- 3. Bol Malzemeli Dürümos XL
INSERT INTO MenuItems (RestaurantID, Name, Description, Price, Category, ImageURL, IsAvailable)
VALUES (@RestaurantID, N'Bol Malzemeli Dürümos XL', N'Mozzarella peyniri, sucuk, sosis, jambon, mantar, mısır, siyah zeytin, mayonez (Malzeme değişimi yapılmamaktadır.)', 195.00, N'Dürümler', 'xldurumos.jpg', 1);

-- 4. Tavuklu Dürümos XL
INSERT INTO MenuItems (RestaurantID, Name, Description, Price, Category, ImageURL, IsAvailable)
VALUES (@RestaurantID, N'Tavuklu Dürümos XL', N'Mozzarella peyniri, tavuk parçaları, közlenmiş kırmızıbiber, mantar, yöresel lezzetler baharatı (Malzeme değişimi yapılmamaktadır.)', 195.00, N'Dürümler', 'xltavukludurumos.jpg', 1);

GO

-- Kontrol - Eklenen menüleri göster
SELECT m.MenuItemID, r.Name AS RestaurantName, m.Name, m.Price, m.Category 
FROM MenuItems m
INNER JOIN Restaurants r ON m.RestaurantID = r.RestaurantID
WHERE r.Name LIKE '%Domino%' AND m.Category = N'Dürümler'
ORDER BY m.Price, m.Name;
