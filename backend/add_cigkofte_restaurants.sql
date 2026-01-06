-- Çiğ Köfte Restoranları Ekleme Script'i
-- Bu script 4 adet Çiğ Köfte restoranı ekler

USE YemekSepetiDB;
GO

-- O Ses Çiğköfte
INSERT INTO Restaurants (Name, Category, Address, Phone, Rating, MinOrderAmount, DeliveryTime, IsActive)
VALUES ('O Ses Çiğköfte', 'Çiğ Köfte', 'Çankaya Mahallesi, 19. Sokak No:45, Ankara', '0312 456 78 90', 4.6, 50.00, 25, 1);

-- Biberzade Çiğköfte
INSERT INTO Restaurants (Name, Category, Address, Phone, Rating, MinOrderAmount, DeliveryTime, IsActive)
VALUES ('Biberzade Çiğköfte', 'Çiğ Köfte', 'Kızılay Mahallesi, Atatürk Bulvarı No:88, Ankara', '0312 567 89 01', 4.5, 40.00, 20, 1);

-- Komagene Etsiz Çiğköfte
INSERT INTO Restaurants (Name, Category, Address, Phone, Rating, MinOrderAmount, DeliveryTime, IsActive)
VALUES ('Komagene Etsiz Çiğköfte', 'Çiğ Köfte', 'Ulus Mahallesi, Cumhuriyet Caddesi No:12, Ankara', '0312 678 90 12', 4.7, 60.00, 30, 1);

-- Çiğköftem
INSERT INTO Restaurants (Name, Category, Address, Phone, Rating, MinOrderAmount, DeliveryTime, IsActive)
VALUES ('Çiğköftem', 'Çiğ Köfte', 'Bahçelievler Mahallesi, 7. Cadde No:34, Ankara', '0312 789 01 23', 4.4, 45.00, 22, 1);

GO

PRINT 'Çiğ Köfte restoranları başarıyla eklendi!';
