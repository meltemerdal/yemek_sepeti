-- Sarıyer Börekçisi Menü Düzeltme
-- Tekrarlanan kayıtları temizle

USE YemekSepetiDB;
GO

-- Önce tüm Sarıyer Börekçisi menülerini sil
DELETE FROM MenuItems WHERE RestaurantID = 59;
GO

-- Şimdi sadece 1'er tane ekle
-- Peynirli Borek
INSERT INTO MenuItems (RestaurantID, Name, Description, Price, Category, ImageURL, IsAvailable)
VALUES (59, N'Peynirli Borek', N'Tek Kisilik', 152.00, N'Borekler', 'peynirliborek.jpg', 1);

-- Patatesli Borek
INSERT INTO MenuItems (RestaurantID, Name, Description, Price, Category, ImageURL, IsAvailable)
VALUES (59, N'Patatesli Borek', N'Tek Kisilik', 152.00, N'Borekler', 'patatesliborek.jpg', 1);

-- Küt Böreği
INSERT INTO MenuItems (RestaurantID, Name, Description, Price, Category, ImageURL, IsAvailable)
VALUES (59, N'Küt Böreği', N'Tek Kisilik', 152.00, N'Borekler', 'kutboregi.jpg', 1);

-- Ayran
INSERT INTO MenuItems (RestaurantID, Name, Description, Price, Category, ImageURL, IsAvailable)
VALUES (59, N'Ayran', N'20cl.', 20.00, N'Icecekler', 'ayran.jpg', 1);

-- Su
INSERT INTO MenuItems (RestaurantID, Name, Description, Price, Category, ImageURL, IsAvailable)
VALUES (59, N'Su', N'50cl.', 15.00, N'Icecekler', 'su.jpg', 1);

-- Cappy
INSERT INTO MenuItems (RestaurantID, Name, Description, Price, Category, ImageURL, IsAvailable)
VALUES (59, N'Cappy', N'33 cl.', 25.00, N'Icecekler', 'cappy.jpg', 1);

GO

-- Kontrol
SELECT MenuItemID, Name, Description, Price, Category 
FROM MenuItems 
WHERE RestaurantID = 59
ORDER BY MenuItemID;
