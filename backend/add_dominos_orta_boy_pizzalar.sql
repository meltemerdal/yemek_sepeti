-- Domino's Pizza Orta Boy Pizzalar (2 Kişilik) Menüsü

USE YemekSepetiDB;
GO

-- RestaurantID'yi bul (Domino's Pizza için)
DECLARE @RestaurantID INT;
SELECT @RestaurantID = RestaurantID FROM Restaurants WHERE Name LIKE '%Domino%';

-- 1. Margarita (Orta)
INSERT INTO MenuItems (RestaurantID, Name, Description, Price, Category, ImageURL, IsAvailable)
VALUES (@RestaurantID, N'Margarita (Orta)', N'Özel pizza sosu ve mozzarella peyniri', 320.00, N'Orta Boy Pizzalar (2 Kişilik)', 'margaritadominos.jpg', 1);

-- 2. Bol Sucuksever Pizza (Orta)
INSERT INTO MenuItems (RestaurantID, Name, Description, Price, Category, ImageURL, IsAvailable)
VALUES (@RestaurantID, N'Bol Sucuksever Pizza (Orta)', N'Pizza sosu, mozzarella peyniri, sucuk', 320.00, N'Orta Boy Pizzalar (2 Kişilik)', 'bolsucuksever.jpg', 1);

-- 3. Süperos (Orta)
INSERT INTO MenuItems (RestaurantID, Name, Description, Price, Category, ImageURL, IsAvailable)
VALUES (@RestaurantID, N'Süperos (Orta)', N'Pizza sosu, mozzarella peyniri, sucuk, jambon, sosis, domates, siyah zeytin, kekik', 350.00, N'Orta Boy Pizzalar (2 Kişilik)', 'superos.jpg', 1);

-- 4. Kantin Pizza (Orta)
INSERT INTO MenuItems (RestaurantID, Name, Description, Price, Category, ImageURL, IsAvailable)
VALUES (@RestaurantID, N'Kantin Pizza (Orta)', N'Pizza sosu, mozzarella peyniri, sosis, mısır', 350.00, N'Orta Boy Pizzalar (2 Kişilik)', 'kantinpizza.jpg', 1);

-- 5. Sarımsak Soslu Bol Sucuksever (Orta)
INSERT INTO MenuItems (RestaurantID, Name, Description, Price, Category, ImageURL, IsAvailable)
VALUES (@RestaurantID, N'Sarımsak Soslu Bol Sucuksever (Orta)', N'Pizza sosu, mozzarella peyniri, sucuk, sarımsak sos (Sarımsak sos için malzeme değişimi yapılmamaktadır.)', 350.00, N'Orta Boy Pizzalar (2 Kişilik)', 'sarimsakpizza.jpg', 1);

-- 6. Vegi (Orta)
INSERT INTO MenuItems (RestaurantID, Name, Description, Price, Category, ImageURL, IsAvailable)
VALUES (@RestaurantID, N'Vegi (Orta)', N'Pizza sosu, mozzarella peyniri, yeşil biber, mantar, mısır, siyah zeytin, domates, kekik, susam', 350.00, N'Orta Boy Pizzalar (2 Kişilik)', 'vegipizza.jpg', 1);

GO

-- Kontrol - Eklenen menüleri göster
SELECT m.MenuItemID, r.Name AS RestaurantName, m.Name, m.Price, m.Category 
FROM MenuItems m
INNER JOIN Restaurants r ON m.RestaurantID = r.RestaurantID
WHERE r.Name LIKE '%Domino%' AND m.Category = N'Orta Boy Pizzalar (2 Kişilik)'
ORDER BY m.Price, m.Name;
