-- Domino's Pizza Atıştırmalıklar Menüsü

USE YemekSepetiDB;
GO

-- RestaurantID'yi bul (Domino's Pizza için)
DECLARE @RestaurantID INT;
SELECT @RestaurantID = RestaurantID FROM Restaurants WHERE Name LIKE '%Domino%';

-- 1. 5'li Çıtır Tavuk Finger
INSERT INTO MenuItems (RestaurantID, Name, Description, Price, Category, ImageURL, IsAvailable)
VALUES (@RestaurantID, N'5''li Çıtır Tavuk Finger', N'5 adet çıtır tavuk finger', 100.00, N'Atıştırmalıklar', 'finger.jpg', 1);

-- 2. 4'lü Tavuk Parçaları
INSERT INTO MenuItems (RestaurantID, Name, Description, Price, Category, ImageURL, IsAvailable)
VALUES (@RestaurantID, N'4''lü Tavuk Parçaları', N'4 adet marine edilmiş tavuk parçaları', 95.00, N'Atıştırmalıklar', 'tavukparcalari.jpg', 1);

-- 3. Çıtır Karışık Kova
INSERT INTO MenuItems (RestaurantID, Name, Description, Price, Category, ImageURL, IsAvailable)
VALUES (@RestaurantID, N'Çıtır Karışık Kova', N'10 Adet Çıtır Tavuk Topları + 5 Adet Çıtır Tavuk Finger + 4 Adet Tavuk Parçaları + Tereyağ Aromalı Ekmek + Orta Boy Patates + Yoğurtlu Sos', 220.00, N'Atıştırmalıklar', 'citirkova.jpg', 1);

-- 4. Patates
INSERT INTO MenuItems (RestaurantID, Name, Description, Price, Category, ImageURL, IsAvailable)
VALUES (@RestaurantID, N'Patates', N'150 gr. gurme patates kızartması', 65.00, N'Atıştırmalıklar', 'patates.jpg', 1);

-- 5. Çıtırı Bol Tavuk ve Patates Mix
INSERT INTO MenuItems (RestaurantID, Name, Description, Price, Category, ImageURL, IsAvailable)
VALUES (@RestaurantID, N'Çıtırı Bol Tavuk ve Patates Mix', N'5 Adet Çıtırı Bol Tavuk Finger + 10 Adet Çıtır Tavuk Topları + Küçük Boy Patates', 145.00, N'Atıştırmalıklar', 'citirpatatesmix.jpg', 1);

GO

-- Kontrol - Eklenen menüleri göster
SELECT m.MenuItemID, r.Name AS RestaurantName, m.Name, m.Price, m.Category 
FROM MenuItems m
INNER JOIN Restaurants r ON m.RestaurantID = r.RestaurantID
WHERE r.Name LIKE '%Domino%' AND m.Category = N'Atıştırmalıklar'
ORDER BY m.Price, m.Name;
