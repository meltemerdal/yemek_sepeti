-- Starbucks Coffee Soğuk Kahveler Menüsü

USE YemekSepetiDB;
GO

-- Iced Vanilla Latte
INSERT INTO MenuItems (RestaurantID, Name, Description, Price, Category, ImageURL, IsAvailable)
VALUES (64, N'Iced Vanilla Latte', N'Espresso, süt ve vanilya şurubu, Latte''mize canlandırıcı bir dokunuş katmak için buz üzerinde bir araya geliyor. Ekstra pürüzsüz bir deneyim için Starbucks Blonde® Roast ile deneyin. Alerjen: Süt', 200.00, N'Soguk Kahveler', 'icedvanillalatte.jpg', 1);

-- Iced Brown Sugar Oat Shaken Espresso
INSERT INTO MenuItems (RestaurantID, Name, Description, Price, Category, ImageURL, IsAvailable)
VALUES (64, N'Iced Brown Sugar Oat Shaken Espresso', N'İnce tatlı Starbucks Blonde® Espresso rostomuzu kullanarak, espresso ve esmer şeker şurubunu buzla birlikte çalkalıyoruz, ardından gün boyunca size güç verecek canlandırıcı bir ikram için yulaf içeceği ile süslüyoruz. Alerjen: Gluten, Süt', 210.00, N'Soguk Kahveler', 'icedbrown.jpg', 1);

-- Cold Brew
INSERT INTO MenuItems (RestaurantID, Name, Description, Price, Category, ImageURL, IsAvailable)
VALUES (64, N'Cold Brew', N'Cold Brew kahvemiz el yapımıdır ve süper pürüzsüz bir tat vermek için 20 saat boyunca soğuk suda yavaş yavaş demlenir.', 180.00, N'Soguk Kahveler', 'coldbrew.jpg', 1);

-- Cold Brew Latte
INSERT INTO MenuItems (RestaurantID, Name, Description, Price, Category, ImageURL, IsAvailable)
VALUES (64, N'Cold Brew Latte', N'Sütlü Soğuk Demleme Kahve. Alerjen: Süt', 185.00, N'Soguk Kahveler', 'coldbrewlatte.jpg', 1);

-- Iced Mocha
INSERT INTO MenuItems (RestaurantID, Name, Description, Price, Category, ImageURL, IsAvailable)
VALUES (64, N'Iced Mocha', N'Mocha soslu, sütlü ve buzlu zengin, dolgun espresso, şekerli krem şanti ile süslenmiş. Alerjen: Süt', 205.00, N'Soguk Kahveler', 'icedmocha.jpg', 1);

-- Iced White Mocha
INSERT INTO MenuItems (RestaurantID, Name, Description, Price, Category, ImageURL, IsAvailable)
VALUES (64, N'Iced White Mocha', N'Espresso, sütlü ve beyaz çikolata aromalı sos buzun üzerine dökülür. Şekerli krem şanti ile tepesinde. Alerjen: Süt', 210.00, N'Soguk Kahveler', 'icedwhitemocha.jpg', 1);

GO

-- Kontrol
SELECT MenuItemID, Name, Price, Category 
FROM MenuItems 
WHERE RestaurantID = 64 AND Category = N'Soguk Kahveler'
ORDER BY Price DESC;
