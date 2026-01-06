-- Jamal - Soğuk Kahveler ekleme scripti
USE YemekSepetiDB;

-- Jamal restoranının ID'sini bul
DECLARE @RestaurantID INT;
SELECT TOP 1 @RestaurantID = RestaurantID FROM Restaurants WHERE Name LIKE N'%Jamal%';

IF @RestaurantID IS NULL
BEGIN
    PRINT 'Jamal adlı restoran bulunamadı. Lütfen restoran adını kontrol edin.';
    RETURN;
END

-- Eski 'Soğuk Kahveler' kayıtlarını silmek isterseniz aşağıdaki satırın başındaki -- işaretini kaldırın
-- DELETE FROM MenuItems WHERE RestaurantID = @RestaurantID AND Category = N'Soğuk Kahveler';

INSERT INTO MenuItems (RestaurantID, Name, Description, Price, Category, ImageURL, IsAvailable)
VALUES
(@RestaurantID, N'Iced Latte', N'Orta boy', 112.00, N'Soğuk Kahveler', '', 1),
(@RestaurantID, N'Iced Mocha', N'Soğuk servis edilir', 112.00, N'Soğuk Kahveler', '', 1),
(@RestaurantID, N'Iced Americano', N'Soğuk servis edilir', 112.00, N'Soğuk Kahveler', '', 1),
(@RestaurantID, N'Iced White Chocolate Mocha', N'Soğuk servis edilir', 112.00, N'Soğuk Kahveler', '', 1),
(@RestaurantID, N'Espresso Freddo', N'Sıcak servis edilir', 112.00, N'Soğuk Kahveler', '', 1);

PRINT 'Jamal için Soğuk Kahveler başarıyla eklendi (dosya çalıştırıldığında).';