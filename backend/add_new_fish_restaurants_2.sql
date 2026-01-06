-- 6 Yeni Balık ve Deniz Ürünleri Restoranı Ekleme (2. Grup)

USE YemekSepetiDB;
GO

INSERT INTO dbo.Restaurants (Name, Category, Address, Phone, Rating, MinOrderAmount, DeliveryTime, IsActive) 
VALUES 
('Etiler Balıkçısı', N'Balık ve Deniz Ürünleri', N'Etiler Mah. Nispetiye Cad. No:67, Beşiktaş', '0212 358 44 55', 4.7, 270, 39, 1),
('Midye Town & Kokoreç', N'Balık ve Deniz Ürünleri', N'Kadıköy Mah. Moda Cad. No:234, Kadıköy', '0216 349 78 90', 4.5, 90, 25, 1),
('Balık Ekmek Evi', N'Balık ve Deniz Ürünleri', N'Eminönü Mah. Galata Köprüsü Altı No:12, Eminönü', '0212 522 33 44', 4.3, 50, 20, 1),
('İstakozi', N'Balık ve Deniz Ürünleri', N'Nişantaşı Mah. Teşvikiye Cad. No:89, Şişli', '0212 296 67 78', 4.9, 350, 50, 1),
('Orkinos Balık Evi', N'Balık ve Deniz Ürünleri', N'Bağdat Cad. No:456, Kadıköy', '0216 385 88 99', 4.8, 290, 42, 1),
('Gökhan Balık', N'Balık ve Deniz Ürünleri', N'Bebek Mah. Cevdetpaşa Cad. No:123, Beşiktaş', '0212 263 55 66', 4.6, 260, 38, 1);
