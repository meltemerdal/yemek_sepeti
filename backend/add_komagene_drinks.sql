-- Komagene - İçecekler menü öğeleri
USE YemekSepetiDB;
GO

-- Önce Komagene (RestaurantID=92) için eski 'İçecekler' kayıtlarını sil
DELETE FROM MenuItems
WHERE RestaurantID = 92 AND Category = N'İçecekler';

PRINT 'Eski İçecekler kayıtları silindi (RestaurantID=92)';
GO

-- Yeni içecekler
INSERT INTO MenuItems (RestaurantID, Name, Description, Price, Category, ImageURL, IsAvailable)
VALUES (92, N'Ayran (20 cl.)', N'Soğuk ayran - 20 cl.', 25.00, N'İçecekler', 'ayran20cl.jpg', 1);

INSERT INTO MenuItems (RestaurantID, Name, Description, Price, Category, ImageURL, IsAvailable)
VALUES (92, N'Coca-cola (33 cl.)', N'Coca-cola 33 cl.', 50.00, N'İçecekler', 'coca_cola_33cl.jpg', 1);

INSERT INTO MenuItems (RestaurantID, Name, Description, Price, Category, ImageURL, IsAvailable)
VALUES (92, N'Cappy (33 cl.)', N'Cappy meyve suyu 33 cl.', 50.00, N'İçecekler', 'cappy_33cl.jpg', 1);

INSERT INTO MenuItems (RestaurantID, Name, Description, Price, Category, ImageURL, IsAvailable)
VALUES (92, N'Fuse Tea (33 cl.)', N'Fuse Tea 33 cl.', 50.00, N'İçecekler', 'fusetea_33cl.jpg', 1);

GO

PRINT 'Komagene için içecekler başarıyla eklendi (dosya çalıştırıldığında).';
