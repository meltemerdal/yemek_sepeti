-- Döner Restoranları Ekleme
-- Tarih: 1 Aralık 2025

USE YemekSepetiDB;
GO

-- Hot Döner
INSERT INTO Restaurants (Name, Category, Address, Phone, Rating, DeliveryTime, MinOrderAmount, IsActive)
VALUES ('Hot Döner', 'Döner', 'Kadıköy, İstanbul', '0216-555-0101', 4.3, 25, 60.00, 1);

-- Saray Döner
INSERT INTO Restaurants (Name, Category, Address, Phone, Rating, DeliveryTime, MinOrderAmount, IsActive)
VALUES ('Saray Döner', 'Döner', 'Beşiktaş, İstanbul', '0212-555-0102', 4.5, 30, 70.00, 1);

-- Öncü Döner
INSERT INTO Restaurants (Name, Category, Address, Phone, Rating, DeliveryTime, MinOrderAmount, IsActive)
VALUES ('Öncü Döner', 'Döner', 'Şişli, İstanbul', '0212-555-0103', 4.4, 28, 65.00, 1);

-- Üçler Döner & İskender
INSERT INTO Restaurants (Name, Category, Address, Phone, Rating, DeliveryTime, MinOrderAmount, IsActive)
VALUES ('Üçler Döner & İskender', 'Döner', 'Beyoğlu, İstanbul', '0212-555-0104', 4.6, 35, 80.00, 1);

-- Usta Dönerci
INSERT INTO Restaurants (Name, Category, Address, Phone, Rating, DeliveryTime, MinOrderAmount, IsActive)
VALUES ('Usta Dönerci', 'Döner', 'Üsküdar, İstanbul', '0216-555-0105', 4.2, 22, 55.00, 1);

-- Ye Doy's Döner
INSERT INTO Restaurants (Name, Category, Address, Phone, Rating, DeliveryTime, MinOrderAmount, IsActive)
VALUES ('Ye Doy''s Döner', 'Döner', 'Maltepe, İstanbul', '0216-555-0106', 4.4, 27, 65.00, 1);

-- Takiles Fast Food
INSERT INTO Restaurants (Name, Category, Address, Phone, Rating, DeliveryTime, MinOrderAmount, IsActive)
VALUES ('Takiles Fast Food', 'Döner', 'Ataşehir, İstanbul', '0216-555-0107', 4.3, 26, 60.00, 1);

PRINT 'Döner restoranları başarıyla eklendi!';
GO

-- Menü öğeleri ekle
DECLARE @HotDonerID INT = (SELECT RestaurantID FROM Restaurants WHERE Name = 'Hot Döner');
DECLARE @SarayDonerID INT = (SELECT RestaurantID FROM Restaurants WHERE Name = 'Saray Döner');
DECLARE @OncuDonerID INT = (SELECT RestaurantID FROM Restaurants WHERE Name = 'Öncü Döner');
DECLARE @UclerDonerID INT = (SELECT RestaurantID FROM Restaurants WHERE Name = 'Üçler Döner & İskender');
DECLARE @UstaDonerID INT = (SELECT RestaurantID FROM Restaurants WHERE Name = 'Usta Dönerci');
DECLARE @YeDoysDonerID INT = (SELECT RestaurantID FROM Restaurants WHERE Name = 'Ye Doy''s Döner');
DECLARE @TakilesID INT = (SELECT RestaurantID FROM Restaurants WHERE Name = 'Takiles Fast Food');

-- Hot Döner Menüsü
INSERT INTO MenuItems (RestaurantID, Name, Description, Price, Category, Stock, IsAvailable)
VALUES 
    (@HotDonerID, 'Tavuk Döner Porsiyon', 'Lavaş ekmek, patates, salata ile', 75.00, 'Ana Yemek', 100, 1),
    (@HotDonerID, 'Et Döner Porsiyon', 'Lavaş ekmek, patates, salata ile', 85.00, 'Ana Yemek', 80, 1),
    (@HotDonerID, 'Döner Dürüm', 'Tavuk veya et döner dürüm', 60.00, 'Ana Yemek', 120, 1),
    (@HotDonerID, 'Ayran', 'Soğuk ayran', 8.00, 'İçecek', 200, 1),
    (@HotDonerID, 'Kola', '330ml', 10.00, 'İçecek', 150, 1);

-- Saray Döner Menüsü
INSERT INTO MenuItems (RestaurantID, Name, Description, Price, Category, Stock, IsAvailable)
VALUES 
    (@SarayDonerID, 'İskender', 'Yoğurtlu, tereyağlı', 95.00, 'Ana Yemek', 60, 1),
    (@SarayDonerID, 'Döner Porsiyon', 'Pilav üstü döner', 80.00, 'Ana Yemek', 80, 1),
    (@SarayDonerID, 'Döner Dürüm', 'Özel lavaş ile', 65.00, 'Ana Yemek', 100, 1),
    (@SarayDonerID, 'Ayran', 'Ev yapımı', 10.00, 'İçecek', 200, 1),
    (@SarayDonerID, 'Şalgam', 'Acılı şalgam', 12.00, 'İçecek', 100, 1);

-- Öncü Döner Menüsü
INSERT INTO MenuItems (RestaurantID, Name, Description, Price, Category, Stock, IsAvailable)
VALUES 
    (@OncuDonerID, 'Tavuk Döner Dürüm', 'Özel sos ile', 58.00, 'Ana Yemek', 120, 1),
    (@OncuDonerID, 'Et Döner Dürüm', 'Özel sos ile', 68.00, 'Ana Yemek', 100, 1),
    (@OncuDonerID, 'Döner Porsiyon', 'Patates, pilav, salata', 78.00, 'Ana Yemek', 80, 1),
    (@OncuDonerID, 'Patates Kızartması', 'Büyük boy', 25.00, 'Yan Ürün', 150, 1),
    (@OncuDonerID, 'Ayran', '250ml', 8.00, 'İçecek', 200, 1);

-- Üçler Döner Menüsü
INSERT INTO MenuItems (RestaurantID, Name, Description, Price, Category, Stock, IsAvailable)
VALUES 
    (@UclerDonerID, 'İskender Porsiyon', 'Yoğurt, tereyağ, domates sos', 100.00, 'Ana Yemek', 50, 1),
    (@UclerDonerID, 'Döner Porsiyon', 'Pilav üstü', 85.00, 'Ana Yemek', 70, 1),
    (@UclerDonerID, 'Döner Dürüm', 'Lavaş ekmeği ile', 70.00, 'Ana Yemek', 90, 1),
    (@UclerDonerID, 'Mercimek Çorbası', 'Günlük taze', 20.00, 'Çorba', 100, 1),
    (@UclerDonerID, 'Ayran', 'Ev yapımı ayran', 10.00, 'İçecek', 200, 1);

-- Usta Dönerci Menüsü
INSERT INTO MenuItems (RestaurantID, Name, Description, Price, Category, Stock, IsAvailable)
VALUES 
    (@UstaDonerID, 'Döner Dürüm', 'Özel lavaş ile', 55.00, 'Ana Yemek', 130, 1),
    (@UstaDonerID, 'Döner Porsiyon', 'Patates ve salata ile', 70.00, 'Ana Yemek', 90, 1),
    (@UstaDonerID, 'Tavuk Şiş', '3 adet şiş', 75.00, 'Ana Yemek', 60, 1),
    (@UstaDonerID, 'Patates', 'Kızarmış patates', 20.00, 'Yan Ürün', 150, 1),
    (@UstaDonerID, 'Ayran', 'Soğuk ayran', 8.00, 'İçecek', 200, 1);

-- Ye Doy's Döner Menüsü
INSERT INTO MenuItems (RestaurantID, Name, Description, Price, Category, Stock, IsAvailable)
VALUES 
    (@YeDoysDonerID, 'Mega Döner Dürüm', 'XXL boy dürüm', 80.00, 'Ana Yemek', 80, 1),
    (@YeDoysDonerID, 'Klasik Döner Dürüm', 'Normal boy', 60.00, 'Ana Yemek', 120, 1),
    (@YeDoysDonerID, 'Döner Porsiyon', 'Patates, pilav, salata', 75.00, 'Ana Yemek', 100, 1),
    (@YeDoysDonerID, 'Soğan Halkası', 'Crispy', 25.00, 'Yan Ürün', 100, 1),
    (@YeDoysDonerID, 'Kola', '330ml', 10.00, 'İçecek', 150, 1);

-- Takiles Fast Food Menüsü
INSERT INTO MenuItems (RestaurantID, Name, Description, Price, Category, Stock, IsAvailable)
VALUES 
    (@TakilesID, 'Tavuk Döner Dürüm', 'Özel sos', 58.00, 'Ana Yemek', 110, 1),
    (@TakilesID, 'Et Döner Dürüm', 'Özel sos', 68.00, 'Ana Yemek', 90, 1),
    (@TakilesID, 'Döner Porsiyon', 'Pilav, patates', 78.00, 'Ana Yemek', 80, 1),
    (@TakilesID, 'Patates Kızartması', 'Büyük boy', 22.00, 'Yan Ürün', 150, 1),
    (@TakilesID, 'Ayran', 'Ev yapımı', 8.00, 'İçecek', 200, 1);

PRINT 'Döner restoranları menüleri başarıyla eklendi!';
GO
