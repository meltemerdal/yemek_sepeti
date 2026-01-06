-- Domino's Pizza Pizzetta Menüsü

USE YemekSepetiDB;
GO

-- RestaurantID'yi bul (Domino's Pizza için)
DECLARE @RestaurantID INT;
SELECT @RestaurantID = RestaurantID FROM Restaurants WHERE Name LIKE '%Domino%';

-- 1. Sebzeli Pizzetta
INSERT INTO MenuItems (RestaurantID, Name, Description, Price, Category, ImageURL, IsAvailable)
VALUES (@RestaurantID, N'Sebzeli Pizzetta', N'Pizza sosu, mozzarella peyniri, köz biber, zeytin, mantar', 155.00, N'Pizzetta', 'sebzelipizzetta.jpg', 1);

-- 2. Sucuklu Pizzetta
INSERT INTO MenuItems (RestaurantID, Name, Description, Price, Category, ImageURL, IsAvailable)
VALUES (@RestaurantID, N'Sucuklu Pizzetta', N'Pizza sosu, mozzarella peyniri, sucuk, mantar, yeşil biber', 155.00, N'Pizzetta', 'sucuklupizzetta.jpg', 1);

-- 3. Karışık Pizzetta
INSERT INTO MenuItems (RestaurantID, Name, Description, Price, Category, ImageURL, IsAvailable)
VALUES (@RestaurantID, N'Karışık Pizzetta', N'Pizza sosu, mozzarella peyniri, sucuk, sosis, jambon, mısır, yeşil biber', 155.00, N'Pizzetta', 'karisikpizzetta.jpg', 1);

-- 4. Margarita Pizzetta
INSERT INTO MenuItems (RestaurantID, Name, Description, Price, Category, ImageURL, IsAvailable)
VALUES (@RestaurantID, N'Margarita Pizzetta', N'Pizza sosu, mozzarella peyniri', 155.00, N'Pizzetta', 'margaritapizzetta.jpg', 1);

GO

-- Kontrol - Eklenen menüleri göster
SELECT m.MenuItemID, r.Name AS RestaurantName, m.Name, m.Price, m.Category 
FROM MenuItems m
INNER JOIN Restaurants r ON m.RestaurantID = r.RestaurantID
WHERE r.Name LIKE '%Domino%' AND m.Category = N'Pizzetta'
ORDER BY m.Name;
