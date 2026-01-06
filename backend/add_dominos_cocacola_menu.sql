-- Domino's Pizza Coca-Cola Fırsat Menüleri

USE YemekSepetiDB;
GO

-- RestaurantID'yi bul (Domino's Pizza için)
DECLARE @RestaurantID INT;
SELECT @RestaurantID = RestaurantID FROM Restaurants WHERE Name LIKE '%Domino%';

-- 1. 2 Orta Boy Bol Malzemos + Coca-Cola 1 L (Coca-Cola Fırsatı)
INSERT INTO MenuItems (RestaurantID, Name, Description, Price, Category, ImageURL, IsAvailable)
VALUES (@RestaurantID, N'2 Orta Boy Bol Malzemos + Coca-Cola 1 L', N'Bol Malzemos pizzada geçerlidir. Seçeceğiniz 2 Orta Boy Pizza + Coca-Cola (1 L.)', 470.00, N'Coca-Cola Fırsat Menüleri', 'bolmalzemosfirsat.jpg', 1);

-- 2. 2 Orta Boy Pizza + Coca-Cola 1 L (Coca-Cola Fırsatı)
INSERT INTO MenuItems (RestaurantID, Name, Description, Price, Category, ImageURL, IsAvailable)
VALUES (@RestaurantID, N'2 Orta Boy Pizza + Coca-Cola 1 L', N'Seçeceğiniz 2 Orta Boy Pizza + Coca-Cola 1 L', 470.00, N'Coca-Cola Fırsat Menüleri', 'pizzafirsat.jpg', 1);

-- 3. Pizza X-Large + Coca-Cola 1 L (Coca-Cola Fırsatı)
INSERT INTO MenuItems (RestaurantID, Name, Description, Price, Category, ImageURL, IsAvailable)
VALUES (@RestaurantID, N'Pizza X-Large + Coca-Cola 1 L', N'Seçeceğiniz Pizza X-Large + Coca-Cola (1 L)', 480.00, N'Coca-Cola Fırsat Menüleri', 'xlargepizzafirsat.jpg', 1);

-- 4. Büyük Boy Pizza + Coca-Cola 1 L (Coca-Cola Fırsatı)
INSERT INTO MenuItems (RestaurantID, Name, Description, Price, Category, ImageURL, IsAvailable)
VALUES (@RestaurantID, N'Büyük Boy Pizza + Coca-Cola 1 L', N'Seçeceğiniz Büyük Boy Pizza + Coca-Cola (1 L.)', 430.00, N'Coca-Cola Fırsat Menüleri', 'buyukpizzafirsat.jpg', 1);

-- 5. 2 Orta Boy Pan Pizza + Coca-Cola 1 L (Coca-Cola Fırsatı)
INSERT INTO MenuItems (RestaurantID, Name, Description, Price, Category, ImageURL, IsAvailable)
VALUES (@RestaurantID, N'2 Orta Boy Pan Pizza + Coca-Cola 1 L', N'Seçeceğiniz 2 Orta Boy Pan Pizza + Litrelik İçecek', 530.00, N'Coca-Cola Fırsat Menüleri', 'panpizzafirsat.jpg', 1);

GO

-- Kontrol - Eklenen menüleri göster
SELECT m.MenuItemID, r.Name AS RestaurantName, m.Name, m.Price, m.Category 
FROM MenuItems m
INNER JOIN Restaurants r ON m.RestaurantID = r.RestaurantID
WHERE r.Name LIKE '%Domino%' AND m.Category = N'Coca-Cola Fırsat Menüleri'
ORDER BY m.Price;
