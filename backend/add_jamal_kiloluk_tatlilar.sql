-- Jamal - Kiloluk Tatlılar ekleme scripti
USE YemekSepetiDB;

-- Jamal restoranının ID'sini bul
DECLARE @RestaurantID INT;
SELECT TOP 1 @RestaurantID = RestaurantID FROM Restaurants WHERE Name LIKE N'%Jamal%';

IF @RestaurantID IS NULL
BEGIN
    PRINT 'Jamal adlı restoran bulunamadı. Lütfen restoran adını kontrol edin.';
    RETURN;
END

-- Eski 'Kiloluk Tatlılar' kayıtlarını sil (isteğe bağlı, yoruma alabilirsiniz)
-- DELETE FROM MenuItems
-- WHERE RestaurantID = @RestaurantID AND Category = N'Kiloluk Tatlılar';

INSERT INTO MenuItems (RestaurantID, Name, Description, Price, Category, ImageURL, IsAvailable)
VALUES
(@RestaurantID, N'Klasik Ekler (1 kg.)', N'1 kg klasik ekler', 420.00, N'Kiloluk Tatlılar', '', 1),
(@RestaurantID, N'Karışık Kuru Pasta', N'Tatlı, Tuzlu karışık kuru pasta (1 kg)', 385.00, N'Kiloluk Tatlılar', '', 1);

PRINT 'Jamal için Kiloluk Tatlılar başarıyla eklendi (dosya çalıştırıldığında).';