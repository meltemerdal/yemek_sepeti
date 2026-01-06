-- Yemeksepeti Örnek Veri Ekleme
-- Tarih: 24 Kasım 2025

USE YemekSepetiDB;
GO

-- Örnek Kullanıcılar
INSERT INTO Users (FullName, Email, Phone, Password, IsActive) VALUES
('Ahmet Yılmaz', 'ahmet@email.com', '5551234567', 'sifre123', 1),
('Ayşe Demir', 'ayse@email.com', '5559876543', 'sifre123', 1),
('Mehmet Kaya', 'mehmet@email.com', '5555555555', 'sifre123', 1);
GO

-- Örnek Adresler
INSERT INTO Addresses (UserID, Title, AddressText, District, City, IsDefault) VALUES
(1, 'Ev', 'Atatürk Cad. No:123 Daire:5', 'Kadıköy', 'İstanbul', 1),
(1, 'İş', 'İş Merkezi Kat:3', 'Beşiktaş', 'İstanbul', 0),
(2, 'Ev', 'Cumhuriyet Mah. 45. Sokak No:7', 'Çankaya', 'Ankara', 1),
(3, 'Ev', 'Karşıyaka Mah. Deniz Cad. No:89', 'Karşıyaka', 'İzmir', 1);
GO

-- Örnek Restoranlar
INSERT INTO Restaurants (Name, Category, Address, Phone, Rating, MinOrderAmount, DeliveryTime, IsActive) VALUES
('Pizza Palace', 'Pizza', 'Merkez Mah. Pizza Sok. No:10', '2121234567', 4.50, 40.00, 30, 1),
('Burger King', 'Burger', 'Cadde Üstü No:25', '2129876543', 4.20, 35.00, 25, 1),
('Kebapçı Halil', 'Kebap', 'Eski Şehir Merkez', '2125556677', 4.80, 50.00, 35, 1),
('Sushi World', 'Sushi', 'Modern Plaza Kat:1', '2123334455', 4.60, 80.00, 40, 1),
('Tatlı Dünyası', 'Tatlı', 'Pastane Sokak No:3', '2127778899', 4.40, 30.00, 20, 1);
GO

-- Örnek Menü Öğeleri
INSERT INTO MenuItems (RestaurantID, Name, Description, Price, Category, IsAvailable, Stock) VALUES
-- Pizza Palace
(1, 'Margarita Pizza', 'Domates, mozzarella, fesleğen', 65.00, 'Pizza', 1, 50),
(1, 'Karışık Pizza', 'Sucuk, sosis, mısır, mantar', 85.00, 'Pizza', 1, 40),
(1, 'Vejeteryan Pizza', 'Sebzeler ve mozzarella', 70.00, 'Pizza', 1, 30),
(1, 'Kola', 'Soğuk içecek', 15.00, 'İçecek', 1, 100),
-- Burger King
(2, 'Whopper Menü', 'Büyük hamburger, patates, içecek', 95.00, 'Menü', 1, 60),
(2, 'Chicken Burger', 'Tavuk burger, patates', 75.00, 'Burger', 1, 50),
(2, 'Soğan Halkası', 'Çıtır soğan halkası', 35.00, 'Atıştırmalık', 1, 80),
-- Kebapçı Halil
(3, 'Adana Kebap', 'Acılı adana kebap', 120.00, 'Kebap', 1, 40),
(3, 'Urfa Kebap', 'Acısız urfa kebap', 115.00, 'Kebap', 1, 40),
(3, 'İskender', 'Döner üstü yoğurt ve tereyağı', 130.00, 'Kebap', 1, 30),
(3, 'Ayran', 'Ev yapımı ayran', 12.00, 'İçecek', 1, 100),
-- Sushi World
(4, 'California Roll', '8 parça california roll', 85.00, 'Roll', 1, 30),
(4, 'Salmon Sushi', '6 parça somon sushi', 120.00, 'Sushi', 1, 25),
(4, 'Mix Sushi', 'Karışık sushi tabağı', 180.00, 'Set', 1, 20),
-- Tatlı Dünyası
(5, 'Baklava', '1 porsiyon antep fıstıklı baklava', 55.00, 'Tatlı', 1, 50),
(5, 'Künefe', 'Sıcak künefe', 65.00, 'Tatlı', 1, 40),
(5, 'Magnolia', 'Çikolatalı magnolia', 45.00, 'Tatlı', 1, 60),
(5, 'Sütlaç', 'Fırın sütlaç', 35.00, 'Tatlı', 1, 45),
(5, 'Muhallebi', 'Tavuk göğsü', 40.00, 'Tatlı', 1, 40),
(5, 'Dondurma', '3 top dondurma', 30.00, 'Tatlı', 1, 80),
(5, 'Tiramisu', 'İtalyan tatlısı', 70.00, 'Tatlı', 1, 35),
(5, 'Profiterol', 'Çikolata soslu profiterol', 65.00, 'Tatlı', 1, 30),
(5, 'Revani', 'Şerbetli revani', 50.00, 'Tatlı', 1, 40),
(5, 'Kazandibi', 'Geleneksel kazandibi', 55.00, 'Tatlı', 1, 35);
GO

PRINT 'Örnek veriler başarıyla eklendi!';
GO
