-- Starbucks Coffee Sıcak Çaylar ve İçecekler Menüsü

USE YemekSepetiDB;
GO

-- Classic Hot Chocolate
INSERT INTO MenuItems (RestaurantID, Name, Description, Price, Category, ImageURL, IsAvailable)
VALUES (64, N'Classic Hot Chocolate', N'Mocha aromalı şuruplu buharda pişirilmiş süt, krem şanti ile tamamlanmıştır. Alerjen: Süt', 200.00, N'Sicak Caylar ve Icecekler', 'classicchocolate.jpg', 1);

-- Chai Tea Latte
INSERT INTO MenuItems (RestaurantID, Name, Description, Price, Category, ImageURL, IsAvailable)
VALUES (64, N'Chai Tea Latte', N'Kadifemsi buharda pişirilmiş süte eklenen kakule, tarçın, karabiber ve yıldız anason ile demlenmiş siyah çay. Alerjen: Süt', 205.00, N'Sicak Caylar ve Icecekler', 'chaitealatte.jpg', 1);

-- Turkish Tea
INSERT INTO MenuItems (RestaurantID, Name, Description, Price, Category, ImageURL, IsAvailable)
VALUES (64, N'Turkish Tea', N'Siyah Çay', 85.00, N'Sicak Caylar ve Icecekler', 'tea.jpg', 1);

GO

-- Kontrol
SELECT MenuItemID, Name, Price, Category 
FROM MenuItems 
WHERE RestaurantID = 64 AND Category = N'Sicak Caylar ve Icecekler'
ORDER BY Name;
