-- Jamal Soğuk Kahveler kategorisini sıfırlar ve ürünleri tekrar ekler
USE YemekSepetiDB;

DECLARE @RestaurantID INT;
SELECT TOP 1 @RestaurantID = RestaurantID FROM Restaurants WHERE Name LIKE N'%Jamal%';

IF @RestaurantID IS NULL
BEGIN
    PRINT 'Jamal adlı restoran bulunamadı. Lütfen restoran adını kontrol edin.';
    RETURN;
END

-- Tüm Soğuk Kahveler kayıtlarını sil
DELETE FROM MenuItems WHERE RestaurantID = @RestaurantID AND Category = N'Soğuk Kahveler';

-- Doğru ImageURL ile tekrar ekle
INSERT INTO MenuItems (RestaurantID, Name, Description, Price, Category, ImageURL, IsAvailable)
VALUES
(@RestaurantID, N'Iced Latte', N'Orta boy', 112.00, N'Soğuk Kahveler', 'jamalicedlatte.jpg', 1),
(@RestaurantID, N'Iced Mocha', N'Soğuk servis edilir', 112.00, N'Soğuk Kahveler', 'jamalicedmocha.jpg', 1),
(@RestaurantID, N'Iced Americano', N'Soğuk servis edilir', 112.00, N'Soğuk Kahveler', 'jamalicedamericano.jpg', 1),
(@RestaurantID, N'Iced White Chocolate Mocha', N'Soğuk servis edilir', 112.00, N'Soğuk Kahveler', 'jamalicedwhite.jpg', 1),
(@RestaurantID, N'Espresso Freddo', N'Sıcak servis edilir', 112.00, N'Soğuk Kahveler', 'jamalespresso.jpg', 1);

PRINT 'Jamal Soğuk Kahveler kategorisi sıfırlandı ve ürünler tekrar eklendi.';