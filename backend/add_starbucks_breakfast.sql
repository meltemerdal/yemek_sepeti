-- Starbucks Coffee Kahvaltı & Fırın Menüsü

USE YemekSepetiDB;
GO

-- Haşhaşlı Üç Peynirli
INSERT INTO MenuItems (RestaurantID, Name, Description, Price, Category, ImageURL, IsAvailable)
VALUES (64, N'Haşhaşlı Üç Peynirli', N'Haşhaşlı ekşi mayalı ekmek arasında beyaz peynir, cheddar ve kaşar peynirinin lezzet üçlemesi Alerjen: Gluten, süt ürünü', 210.00, N'Kahvalti & Firin', 'hashasli.jpg', 1);

-- Tahıllı Peynirli Poğaça
INSERT INTO MenuItems (RestaurantID, Name, Description, Price, Category, ImageURL, IsAvailable)
VALUES (64, N'Tahıllı Peynirli Poğaça', N'Buğday ve çavdar ununun peynirle buluştuğu tahıllı peynirli poğaça Alerjen: Gluten, süt ürünü, yumurta, ceviz', 85.00, N'Kahvalti & Firin', 'tahillipeynirli.jpg', 1);

-- Zeytinli Açma
INSERT INTO MenuItems (RestaurantID, Name, Description, Price, Category, ImageURL, IsAvailable)
VALUES (64, N'Zeytinli Açma', N'Zeytin ezmeli açma Alerjen: Gluten, yumurta, susam', 85.00, N'Kahvalti & Firin', 'zeytinliacma.jpg', 1);

-- Tavuklu ve Mantarlı Sandviç
INSERT INTO MenuItems (RestaurantID, Name, Description, Price, Category, ImageURL, IsAvailable)
VALUES (64, N'Tavuklu ve Mantarlı Sandviç', N'Yulaflı ekmek içerisinde tavuk, mantar ve kaşar peynirli sandviç Alerjen: Gluten, süt ürünü, soya ürünü, fıstık ürünü', 235.00, N'Kahvalti & Firin', 'tavuklusandvic.jpg', 1);

GO

-- Kontrol
SELECT MenuItemID, Name, Price, Category 
FROM MenuItems 
WHERE RestaurantID = 64 AND Category = N'Kahvalti & Firin'
ORDER BY Name;
