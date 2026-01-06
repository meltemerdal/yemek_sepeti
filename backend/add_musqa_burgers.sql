-- Musqa Burger MNG - Burgerler Kategorisi Menü Ekleme
USE YemekSepetiDB;
GO

-- Burgerler Kategorisi - Gerçek menü ürünleri
-- Önce placeholder ürünleri sil
DELETE FROM dbo.MenuItems 
WHERE RestaurantID = 31 
AND Category = N'Burgerler' 
AND Name = N'[Yakında]';

-- Yeni burger menülerini ekle
INSERT INTO dbo.MenuItems (RestaurantID, Name, Description, Price, Category, IsAvailable, Stock)
VALUES
(31, N'Musqa Burger', N'Ev yapımı hamburger ekmeği, ev yapımı hamburger köftesi, özel sos, domates, yeşillik, turşu. Patates kızartması ile', 390.90, N'Burgerler', 1, 100),
(31, N'Dejavu Burger', N'Ev yapımı hamburger ekmeği, ev yapımı hamburger köftesi, köri sos, közlenmiş biber, demi glace soslu mantar. Patates kızartması ile', 418.90, N'Burgerler', 1, 100),
(31, N'Five Boss Burger', N'Ev yapımı hamburger ekmeği, ev yapımı 5 adet 70 gr. burger köftesi, özel sos, 3 dilim kaşar peyniri, 5 dilim cheddar peyniri, 3 adet dana jambon. Patates kızartması ile', 659.90, N'Burgerler', 1, 100),
(31, N'Big Bang Burger', N'Ev yapımı hamburger ekmeği, ev yapımı 2 adet 70 gr. burger köftesi, özel sos, 2 dilim kaşar peyniri, 2 dilim cheddar peyniri, ortası et soslu lokum iç bonfile, patates kızartması ile', 514.90, N'Burgerler', 1, 100),
(31, N'Musqa Rich Burger', N'Ev yapımı hamburger ekmeği, ev yapımı hamburger köftesi, özel sos, cheddar peyniri, soğan, turşu, domates, yeşillik. Patates kızartması ile', 418.90, N'Burgerler', 1, 100),
(31, N'Tripple Boss Burger', N'Ev yapımı hamburger ekmeği, ev yapımı 3 adet 70 gr. burger köftesi, özel sos, 2 dilim kaşar peyniri, 3 dilim cheddar peyniri, 2 adet dana jambon. Patates kızartması ile', 539.90, N'Burgerler', 1, 100);

PRINT 'Musqa Burger MNG - Burgerler menüsü başarıyla eklendi!';
GO
