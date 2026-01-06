-- 5 Yeni Burger Restoranı Ekleme

USE YemekSepetiDB;
GO

INSERT INTO dbo.Restaurants (Name, Category, Address, Phone, Rating, MinOrderAmount, DeliveryTime, IsActive) 
VALUES 
('Cajun Corner', N'Burger', N'Bağdat Cad. No:234, Kadıköy', '0216 338 44 55', 4.5, 100, 30, 1),
('Musqa Burger Gez', N'Burger', N'İstiklal Cad. No:145, Beyoğlu', '0212 244 67 89', 4.6, 90, 28, 1),
('Musqa Burger MNG', N'Burger', N'Nispetiye Cad. No:78, Etiler', '0212 358 23 45', 4.7, 110, 32, 1),
('Cheff Pizza Cadde', N'Burger', N'Bağdat Cad. No:567, Suadiye', '0216 467 89 12', 4.4, 80, 25, 1),
('Ye Doy''s Burger', N'Burger', N'Ortaklar Cad. No:89, Mecidiyeköy', '0212 272 34 56', 4.5, 95, 27, 1);
