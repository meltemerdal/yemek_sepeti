-- Starbucks Coffee Tatlılar Menüsü

USE YemekSepetiDB;
GO

-- Belçika Çikolatalı Muffin
INSERT INTO MenuItems (RestaurantID, Name, Description, Price, Category, ImageURL, IsAvailable)
VALUES (64, N'Belçika Çikolatalı Muffin', N'Belçika Çikolatası ile hazırlanmış muffin Alerjen: Gluten, süt ürünü, soya ürünü, yumurta', 150.00, N'Tatlilar', 'muffin.jpg', 1);

-- Limonlu Kek
INSERT INTO MenuItems (RestaurantID, Name, Description, Price, Category, ImageURL, IsAvailable)
VALUES (64, N'Limonlu Kek', N'Üzerinde glazür ve içinde limon kabuğu parçacıkları olan kek Alerjen: Gluten, yumurta, soya, süt ürünü', 145.00, N'Tatlilar', 'limonlukek.jpg', 1);

-- Triple Chocolate Cookie
INSERT INTO MenuItems (RestaurantID, Name, Description, Price, Category, ImageURL, IsAvailable)
VALUES (64, N'Triple Chocolate Cookie', N'Bitter, sütlü ve beyaz çikolata parçalı tereyağlı kurabiye lezzeti Alerjen: Gluten, yumurta, fındık, soya', 130.00, N'Tatlilar', 'cookie.jpg', 1);

-- Dolgulu Üçgen Cookie
INSERT INTO MenuItems (RestaurantID, Name, Description, Price, Category, ImageURL, IsAvailable)
VALUES (64, N'Dolgulu Üçgen Cookie', N'Kakaolu Fındık Kreması Dolgulu Çikolata Parçacıklı Kurabiye Alerjen: Gluten, süt ürünü, fındık, yumurta', 200.00, N'Tatlilar', 'ucgencookie.jpg', 1);

-- Mozaik Kek
INSERT INTO MenuItems (RestaurantID, Name, Description, Price, Category, ImageURL, IsAvailable)
VALUES (64, N'Mozaik Kek', N'Kakao ve vanilyalı mozaik kek Alerjen: Gluten, yumurta, süt ürünü', 145.00, N'Tatlilar', 'mozaikkek.jpg', 1);

GO

-- Kontrol
SELECT MenuItemID, Name, Price, Category 
FROM MenuItems 
WHERE RestaurantID = 64 AND Category = N'Tatlilar'
ORDER BY Name;
