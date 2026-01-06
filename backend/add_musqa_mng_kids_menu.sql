-- Musqa Burger MNG - Çocuk Menüleri
USE YemekSepetiDB;
GO

-- Çocuk Menüleri
INSERT INTO dbo.MenuItems (RestaurantID, Name, Description, Price, Category, IsAvailable, Stock)
VALUES
(31, N'Musqa Mini Tavuk', N'Ev yapımı mini hamburger ekmeği, ev yapımı tavuk burger köftesi, mayonez. Patates kızartması ile', 286.90, N'Çocuk Menüleri', 1, 100),
(31, N'Musqa Mini', N'Ev yapımı mini hamburger ekmeği, ev yapımı burger köftesi, ketçap. Patates kızartması ile', 346.90, N'Çocuk Menüleri', 1, 100),
(31, N'Musqa Mini Cheese', N'Ev yapımı mini hamburger ekmeği, ev yapımı burger köftesi, cheddar peyniri, ketçap. Patates kızartması ile', 346.90, N'Çocuk Menüleri', 1, 100);

PRINT 'Musqa Burger MNG çocuk menüleri başarıyla eklendi!';
