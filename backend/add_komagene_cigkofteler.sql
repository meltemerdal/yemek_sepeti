-- Komagene Etsiz Çiğköfte - Çiğ Köfteler
USE YemekSepetiDB;
GO

-- Çiğ Köfte (200 gr.)
INSERT INTO MenuItems (RestaurantID, Name, Description, Price, Category, ImageURL, IsAvailable)
VALUES (92, N'Çiğ Köfte (200 gr.)', N'Çeyrek göbek marul, limon, 2 adet lavaş, 1 adet çiğ köfte sosu ile', 160.00, N'Çiğ Köfteler', 'cigkofte200gr.jpg', 1);

-- Çiğ Köfte (400 gr.)
INSERT INTO MenuItems (RestaurantID, Name, Description, Price, Category, ImageURL, IsAvailable)
VALUES (92, N'Çiğ Köfte (400 gr.)', N'Yarım göbek marul, limon, 4 adet lavaş, 2 adet çiğ köfte sosu, 2 adet acılı ekşi çiğ köfte sosu', 270.00, N'Çiğ Köfteler', 'cigkofte400gr.jpg', 1);

-- Çiğ Köfte (600 gr.)
INSERT INTO MenuItems (RestaurantID, Name, Description, Price, Category, ImageURL, IsAvailable)
VALUES (92, N'Çiğ Köfte (600 gr.)', N'3 çeyrek göbek marul, limon, 6 adet lavaş, 3 adet çiğ köfte sosu ile', 395.00, N'Çiğ Köfteler', 'cigkofte600gr.jpg', 1);

-- Çiğ Köfte (800 gr.)
INSERT INTO MenuItems (RestaurantID, Name, Description, Price, Category, ImageURL, IsAvailable)
VALUES (92, N'Çiğ Köfte (800 gr.)', N'1 adet göbek marul, limon, 8 adet lavaş, 4 adet çiğ köfte sosu ile', 495.00, N'Çiğ Köfteler', 'cigkofte800gr.jpg', 1);

-- Çiğ Köfte (1 kg.)
INSERT INTO MenuItems (RestaurantID, Name, Description, Price, Category, ImageURL, IsAvailable)
VALUES (92, N'Çiğ Köfte (1 kg.)', N'5 çeyrek göbek marul, limon, 10 adet lavaş, 5 adet çiğ köfte sosu ile', 570.00, N'Çiğ Köfteler', 'cigkofte1kg.jpg', 1);

GO

PRINT 'Komagene Çiğ Köfteler kategorisi ve menü öğeleri başarıyla eklendi!';
