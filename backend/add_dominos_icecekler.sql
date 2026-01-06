-- Domino's Pizza İçecekler Menüsü

USE YemekSepetiDB;
GO

-- RestaurantID'yi bul (Domino's Pizza için)
DECLARE @RestaurantID INT;
SELECT @RestaurantID = RestaurantID FROM Restaurants WHERE Name LIKE '%Domino%';

-- 1. Coca-Cola (33 cl.)
INSERT INTO MenuItems (RestaurantID, Name, Description, Price, Category, ImageURL, IsAvailable)
VALUES (@RestaurantID, N'Coca-Cola (33 cl.)', N'33 cl. Coca-Cola kutu içecek', 105.00, N'İçecekler', 'cocacola.jpg', 1);

-- 2. Coca-Cola Zero (33 cl.)
INSERT INTO MenuItems (RestaurantID, Name, Description, Price, Category, ImageURL, IsAvailable)
VALUES (@RestaurantID, N'Coca-Cola Zero (33 cl.)', N'33 cl. Coca-Cola Zero kutu içecek', 105.00, N'İçecekler', 'zero.jpg', 1);

-- 3. Fanta (33 cl.)
INSERT INTO MenuItems (RestaurantID, Name, Description, Price, Category, ImageURL, IsAvailable)
VALUES (@RestaurantID, N'Fanta (33 cl.)', N'33 cl. Fanta kutu içecek', 105.00, N'İçecekler', 'fanta.jpg', 1);

-- 4. Cappy (33 cl.)
INSERT INTO MenuItems (RestaurantID, Name, Description, Price, Category, ImageURL, IsAvailable)
VALUES (@RestaurantID, N'Cappy (33 cl.)', N'33 cl. Cappy meyve suyu', 105.00, N'İçecekler', 'cappy.jpg', 1);

-- 5. Fuse Tea (33 cl.)
INSERT INTO MenuItems (RestaurantID, Name, Description, Price, Category, ImageURL, IsAvailable)
VALUES (@RestaurantID, N'Fuse Tea (33 cl.)', N'33 cl. Fuse Tea soğuk çay', 105.00, N'İçecekler', 'fusetea.jpg', 1);

-- 6. Ayran (20 cl.)
INSERT INTO MenuItems (RestaurantID, Name, Description, Price, Category, ImageURL, IsAvailable)
VALUES (@RestaurantID, N'Ayran (20 cl.)', N'20 cl. ayran', 50.00, N'İçecekler', 'ayran.jpg', 1);

GO

-- Kontrol - Eklenen menüleri göster
SELECT m.MenuItemID, r.Name AS RestaurantName, m.Name, m.Price, m.Category 
FROM MenuItems m
INNER JOIN Restaurants r ON m.RestaurantID = r.RestaurantID
WHERE r.Name LIKE '%Domino%' AND m.Category = N'İçecekler'
ORDER BY m.Price DESC, m.Name;
