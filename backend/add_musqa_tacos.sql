-- Musqa Burger MNG - Tacolar Kategorisi Menü Ekleme
USE YemekSepetiDB;
GO

-- Önce placeholder ürünleri sil
DELETE FROM dbo.MenuItems 
WHERE RestaurantID = 31 
AND Category = N'Tacolar' 
AND Name = N'[Yakında]';

-- Tacolar Kategorisi - Yeni menü ürünleri
INSERT INTO dbo.MenuItems (RestaurantID, Name, Description, Price, Category, IsAvailable, Stock)
VALUES
(31, N'Musqa Taco', N'Taco ekmeğine; baharatlı kaburga kıyması, marul, domates, soğan, turşu, çıtır jambon, sarımsaklı mayonez, cheddar sos, patlıcan beğendi. Patates kızartması ile', 370.90, N'Tacolar', 1, 100),
(31, N'Mexico Taco', N'Taco ekmeğine; baharatlı kaburga kıyması, jalapeno biber, soğan turşusu, sriracha sos, közlenmiş kapya biber. Patates kızartması ile', 699.90, N'Tacolar', 1, 100);

PRINT 'Musqa Burger MNG - Tacolar menüsü başarıyla eklendi!';
GO
