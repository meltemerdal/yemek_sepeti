-- Sarıyer Börekçisi Menü Ekleme (Restaurant ID: 59)
-- Kategori: Börekler

USE YemekSepetiDB;
GO

-- Peynirli Börek
INSERT INTO MenuItems (RestaurantID, Name, Description, Price, Category, ImageURL, IsAvailable)
VALUES (59, N'Peynirli Börek', N'Tek Kişilik', 152.00, N'Börekler', 'peynirlisigaraboregi.jpg', 1);

-- Patatesli Börek
INSERT INTO MenuItems (RestaurantID, Name, Description, Price, Category, ImageURL, IsAvailable)
VALUES (59, N'Patatesli Börek', N'Tek Kişilik', 152.00, N'Börekler', 'pacangaboregi.jpg', 1);

-- Küt Böreği
INSERT INTO MenuItems (RestaurantID, Name, Description, Price, Category, ImageURL, IsAvailable)
VALUES (59, N'Küt Böreği', N'Tek Kişilik', 152.00, N'Börekler', 'peynirlisuboregi.jpg', 1);

GO

-- Kontrol
SELECT MenuItemID, Name, Description, Price, Category 
FROM MenuItems 
WHERE RestaurantID = 59
ORDER BY MenuItemID;
