-- Starbucks Coffee Frappuccinolar Menüsü

USE YemekSepetiDB;
GO

-- Caramel Frappuccino
INSERT INTO MenuItems (RestaurantID, Name, Description, Price, Category, ImageURL, IsAvailable)
VALUES (64, N'Caramel Frappuccino', N'Kahve ve sütle birleştirilmiş, buzla harmanlanmış, krem şanti ve karamel sos ile süslenmiş karamel aromalı şurubu. Alerjen: Süt, Soya', 220.00, N'Frappuccinolar', 'caramelfrappuccino.jpg', 1);

-- Coffee Frappuccino
INSERT INTO MenuItems (RestaurantID, Name, Description, Price, Category, ImageURL, IsAvailable)
VALUES (64, N'Coffee Frappuccino', N'Frappuccino® kavrulmuş kahve ve süt, buzla karıştırılır. Alerjen: Süt', 220.00, N'Frappuccinolar', 'caramelfrappuccino.jpg', 1);

-- Espresso Frappuccino
INSERT INTO MenuItems (RestaurantID, Name, Description, Price, Category, ImageURL, IsAvailable)
VALUES (64, N'Espresso Frappuccino', N'Kahve aromalı şurup, bir shot espresso ve süt ile birleştirilmiş, buzla harmanlanmış. Alerjen: Süt', 220.00, N'Frappuccinolar', 'espresso.jpg', 1);

-- Java Chip Frappuccino
INSERT INTO MenuItems (RestaurantID, Name, Description, Price, Category, ImageURL, IsAvailable)
VALUES (64, N'Java Chip Frappuccino', N'Mocha sos, Frappuccino® damla çikolata, kahve aromalı şurup ve sütle birleştirilmiş, buzla harmanlanmış, üzerine krem şanti ve mocha Karamelli Çiseleyen çikolata. Alerjen: Gluten, Soya, Süt', 220.00, N'Frappuccinolar', 'javachip.jpg', 1);

-- White Chocolate Cream Frappuccino
INSERT INTO MenuItems (RestaurantID, Name, Description, Price, Category, ImageURL, IsAvailable)
VALUES (64, N'White Chocolate Cream Frappuccino', N'Beyaz çikolata aromalı sos, süt ve buzun pürüzsüz bir karışımı ve krem şanti ile süslenmiş. Alerjen: Süt', 220.00, N'Frappuccinolar', 'whitechocolate.jpg', 1);

-- Strawberries & Cream Frappuccino
INSERT INTO MenuItems (RestaurantID, Name, Description, Price, Category, ImageURL, IsAvailable)
VALUES (64, N'Strawberries & Cream Frappuccino', N'Frappuccino® krema şurubu, süt, çilek sosu ve buz ile harmanlanmıştır. Krem şanti ile tepesinde. Alerjen: Süt', 220.00, N'Frappuccinolar', 'strawberries.jpg', 1);

GO

-- Kontrol
SELECT MenuItemID, Name, Price, Category 
FROM MenuItems 
WHERE RestaurantID = 64 AND Category = N'Frappuccinolar'
ORDER BY Name;
