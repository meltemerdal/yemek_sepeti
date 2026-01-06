-- Jamal - Frozens kategorisi ürün ekleme scripti
USE YemekSepetiDB;

DECLARE @RestaurantID INT;
SELECT TOP 1 @RestaurantID = RestaurantID FROM Restaurants WHERE Name LIKE N'%Jamal%';

IF @RestaurantID IS NULL
BEGIN
    PRINT 'Jamal adlı restoran bulunamadı. Lütfen restoran adını kontrol edin.';
    RETURN;
END

-- Eski Frozens kayıtlarını silmek isterseniz aşağıdaki satırın başındaki -- işaretini kaldırın
-- DELETE FROM MenuItems WHERE RestaurantID = @RestaurantID AND Category = N'Frozens';

INSERT INTO MenuItems (RestaurantID, Name, Description, Price, Category, ImageURL, IsAvailable)
VALUES
(@RestaurantID, N'Muzlu Frozen', N'Orta Boy', 112.00, N'Frozens', '', 1),
(@RestaurantID, N'Ananaslı Frozen', N'Orta Boy', 112.00, N'Frozens', '', 1),
(@RestaurantID, N'Kivili Frozen', N'Orta Boy', 112.00, N'Frozens', '', 1),
(@RestaurantID, N'Orman Meyveli Frozen', N'Orta Boy', 112.00, N'Frozens', '', 1),
(@RestaurantID, N'Karpuzlu Frozen', N'Orta Boy', 112.00, N'Frozens', '', 1);

PRINT 'Jamal için Frozens kategorisi ürünleri eklendi.';