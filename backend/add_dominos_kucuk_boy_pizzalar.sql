-- Domino's Pizza Küçük Boy Pizzalar (1 Kişilik) Menüsü

USE YemekSepetiDB;
GO

-- RestaurantID'yi bul (Domino's Pizza için)
DECLARE @RestaurantID INT;
SELECT @RestaurantID = RestaurantID FROM Restaurants WHERE Name LIKE '%Domino%';

-- 1. Margarita (Küçük)
INSERT INTO MenuItems (RestaurantID, Name, Description, Price, Category, ImageURL, IsAvailable)
VALUES (@RestaurantID, N'Margarita (Küçük)', N'Özel pizza sosu ve mozzarella peyniri', 270.00, N'Küçük Boy Pizzalar (1 Kişilik)', 'margaritadominos.jpg', 1);

-- 2. Bol Sucuksever Pizza (Küçük)
INSERT INTO MenuItems (RestaurantID, Name, Description, Price, Category, ImageURL, IsAvailable)
VALUES (@RestaurantID, N'Bol Sucuksever Pizza (Küçük)', N'Pizza sosu, mozzarella peyniri, sucuk', 270.00, N'Küçük Boy Pizzalar (1 Kişilik)', 'bolsucuksever.jpg', 1);

-- 3. Süperos (Küçük)
INSERT INTO MenuItems (RestaurantID, Name, Description, Price, Category, ImageURL, IsAvailable)
VALUES (@RestaurantID, N'Süperos (Küçük)', N'Pizza sosu, mozzarella peyniri, sucuk, jambon, sosis, domates, siyah zeytin, kekik', 390.00, N'Küçük Boy Pizzalar (1 Kişilik)', 'superos.jpg', 1);

-- 4. Kantin Pizza (Küçük)
INSERT INTO MenuItems (RestaurantID, Name, Description, Price, Category, ImageURL, IsAvailable)
VALUES (@RestaurantID, N'Kantin Pizza (Küçük)', N'Pizza sosu, mozzarella peyniri, sosis, mısır', 290.00, N'Küçük Boy Pizzalar (1 Kişilik)', 'kantinpizza.jpg', 1);

-- 5. Sarımsak Soslu Bol Sucuksever (Küçük)
INSERT INTO MenuItems (RestaurantID, Name, Description, Price, Category, ImageURL, IsAvailable)
VALUES (@RestaurantID, N'Sarımsak Soslu Bol Sucuksever (Küçük)', N'Pizza sosu, mozzarella peyniri, sucuk, sarımsak sos (Sarımsak sos için malzeme değişimi yapılmamaktadır.)', 290.00, N'Küçük Boy Pizzalar (1 Kişilik)', 'sarimsakpizza.jpg', 1);

-- 6. Vegi (Küçük)
INSERT INTO MenuItems (RestaurantID, Name, Description, Price, Category, ImageURL, IsAvailable)
VALUES (@RestaurantID, N'Vegi (Küçük)', N'Pizza sosu, mozzarella peyniri, yeşil biber, mantar, mısır, siyah zeytin, domates, kekik, susam', 290.00, N'Küçük Boy Pizzalar (1 Kişilik)', 'vegipizza.jpg', 1);

GO

-- Kontrol - Eklenen menüleri göster
SELECT m.MenuItemID, r.Name AS RestaurantName, m.Name, m.Price, m.Category 
FROM MenuItems m
INNER JOIN Restaurants r ON m.RestaurantID = r.RestaurantID
WHERE r.Name LIKE '%Domino%' AND m.Category = N'Küçük Boy Pizzalar (1 Kişilik)'
ORDER BY m.Price, m.Name;
