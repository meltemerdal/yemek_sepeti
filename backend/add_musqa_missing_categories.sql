-- Musqa Burger MNG - Boş Kategoriler Ekleme (Placeholder)
-- Bu sadece kategorilerin filtrede görünmesi için geçici ürünler içerir
-- Gerçek menü ürünleri daha sonra eklenecek

USE YemekSepetiDB;
GO

-- Burgerler Kategorisi - Geçici ürün
INSERT INTO dbo.MenuItems (RestaurantID, Name, Description, Price, Category, IsAvailable, Stock)
VALUES
(31, N'[Yakında]', N'Burgerler kategorisi için ürünler eklenecek', 0.01, N'Burgerler', 0, 0);

-- Tacolar Kategorisi - Geçici ürün
INSERT INTO dbo.MenuItems (RestaurantID, Name, Description, Price, Category, IsAvailable, Stock)
VALUES
(31, N'[Yakında]', N'Tacolar kategorisi için ürünler eklenecek', 0.01, N'Tacolar', 0, 0);

-- Atıştırmalıklar Kategorisi - Geçici ürün
INSERT INTO dbo.MenuItems (RestaurantID, Name, Description, Price, Category, IsAvailable, Stock)
VALUES
(31, N'[Yakında]', N'Atıştırmalıklar kategorisi için ürünler eklenecek', 0.01, N'Atıştırmalıklar', 0, 0);

PRINT 'Musqa Burger MNG - Boş kategoriler (Burgerler, Tacolar, Atıştırmalıklar) eklendi!';
GO
