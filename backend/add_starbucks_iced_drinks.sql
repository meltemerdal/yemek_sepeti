-- Starbucks Coffee Buzlu İçecekler Menüsü

USE YemekSepetiDB;
GO

-- Cool Lime Starbucks Refresha
INSERT INTO MenuItems (RestaurantID, Name, Description, Price, Category, ImageURL, IsAvailable)
VALUES (64, N'Cool Lime Starbucks Refresha', N'Cool Lime, gerçek misket limonu dilimleri ile çalkalanmış misket limonu, narenciye ve nane ve salatalık ipuçlarının canlı bir karışımıdır', 210.00, N'Buzlu Icecekler', 'coollime.jpg', 1);

-- Berry Hibiscus Refresha
INSERT INTO MenuItems (RestaurantID, Name, Description, Price, Category, ImageURL, IsAvailable)
VALUES (64, N'Berry Hibiscus Refresha', N'Meyveli ve ferahlatıcı bir pickme-up için gerçek meyveler ve buzla elde çalkalanan Berry ve Hibiscus aroma tabanı.', 210.00, N'Buzlu Icecekler', 'berryhibiscus.jpg', 1);

-- Frozen Mango Dragonfruit Starbucks Refresha
INSERT INTO MenuItems (RestaurantID, Name, Description, Price, Category, ImageURL, IsAvailable)
VALUES (64, N'Frozen Mango Dragonfruit Starbucks Refresha', N'Mango ve Ejder Meyvesi Aromalı Buz Parçacıklı İçecek', 220.00, N'Buzlu Icecekler', 'frozen.jpg', 1);

-- Frozen Very Berry Hibiscus Starbucks Refresha
INSERT INTO MenuItems (RestaurantID, Name, Description, Price, Category, ImageURL, IsAvailable)
VALUES (64, N'Frozen Very Berry Hibiscus Starbucks Refresha', N'Böğürtlen ve Hibiskus Çiçeği Aromalı Buz Parçacıklı İçecek', 220.00, N'Buzlu Icecekler', 'frozenvery.jpg', 1);

-- Fresh Orange Juice
INSERT INTO MenuItems (RestaurantID, Name, Description, Price, Category, ImageURL, IsAvailable)
VALUES (64, N'Fresh Orange Juice', N'Sıkma Portakal Suyu', 155.00, N'Buzlu Icecekler', 'orange.jpg', 1);

-- Orange Mango Starbucks Refresha
INSERT INTO MenuItems (RestaurantID, Name, Description, Price, Category, ImageURL, IsAvailable)
VALUES (64, N'Orange Mango Starbucks Refresha', N'Portakal ve Mango Aromalı Buzlu İçecek', 210.00, N'Buzlu Icecekler', 'orange.jpg', 1);

GO

-- Kontrol
SELECT MenuItemID, Name, Price, Category 
FROM MenuItems 
WHERE RestaurantID = 64 AND Category = N'Buzlu Icecekler'
ORDER BY Price DESC;
