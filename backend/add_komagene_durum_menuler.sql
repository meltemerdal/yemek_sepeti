-- Komagene Etsiz Çiğköfte - Dürüm Menüler
USE YemekSepetiDB;
GO

-- Dürüm Menü
INSERT INTO MenuItems (RestaurantID, Name, Description, Price, Category, ImageURL, IsAvailable)
VALUES (92, N'Dürüm Menü', N'Çiğ Köfte Dürüm (90 gr. çiğ köfte, tek lavaş, seçeceğiniz 5 çeşit garnitür, seçeceğiniz 2 çeşit sos) + Komagene Ayran (17 cl.)', 130.00, N'Dürüm Menüler', 'durummenu.jpg', 1);

-- Mega Dürüm Menü
INSERT INTO MenuItems (RestaurantID, Name, Description, Price, Category, ImageURL, IsAvailable)
VALUES (92, N'Mega Dürüm Menü', N'Mega Çiğ Köfte Dürüm (125 gr. çiğ köfte, çift lavaş, seçeceğiniz 5 çeşit garnitür, seçeceğiniz 2 çeşit sos) + Komagene Ayran (17 cl.)', 140.00, N'Dürüm Menüler', 'durummenu.jpg', 1);

-- Ülker Halley Combo Menü
INSERT INTO MenuItems (RestaurantID, Name, Description, Price, Category, ImageURL, IsAvailable)
VALUES (92, N'Ülker Halley Combo Menü', N'2 Adet Çiğ Köfte Dürüm + 2 Adet Ayran (17 cl.) + 2 Adet Ülker Halley Çikolata Kaplı Bisküvi (30 gr.)', 300.00, N'Dürüm Menüler', 'halleydurummenu.jpg', 1);

-- Bi' Tatlı Fırsat Menü
INSERT INTO MenuItems (RestaurantID, Name, Description, Price, Category, ImageURL, IsAvailable)
VALUES (92, N'Bi'' Tatlı Fırsat Menü', N'Mega Çiğ Köfte Dürüm (125 gr. çiğ köfte, çift lavaş, seçeceğiniz 5 çeşit garnitür, seçeceğiniz 2 çeşit sos) + Komagene Ayran (17 cl.) + Danette', 170.00, N'Dürüm Menüler', 'firsatdurummenu.jpg', 1);

-- Doritos'lu Double Dürüm Menü
INSERT INTO MenuItems (RestaurantID, Name, Description, Price, Category, ImageURL, IsAvailable)
VALUES (92, N'Doritos''lu Double Dürüm Menü', N'Doritos''lu Double Dürüm (175 gr. çiğ köfte, Doritos tako, çift lavaş, seçeceğiniz 5 çeşit garnitür, seçeceğiniz 2 çeşit sos) + Komagene Ayran (17 cl.)', 180.00, N'Dürüm Menüler', 'doritosdurummenu.jpg', 1);

GO

PRINT 'Komagene Dürüm Menüler kategorisi ve menü öğeleri başarıyla eklendi!';
