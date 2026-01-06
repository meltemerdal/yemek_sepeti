-- Komagene - Ekstralar menü öğeleri
USE YemekSepetiDB;
GO

-- İlk olarak: eski 'Ekstralar' kayıtlarını Komagene (RestaurantID=92) için sil
DELETE FROM MenuItems
WHERE RestaurantID = 92 AND Category = N'Ekstralar';

PRINT 'Eski Ekstralar kayıtları silindi (RestaurantID=92)';
GO

-- Not: Aşağıdaki ImageURL alanlarını, frontend/images klasöründeki gerçek dosya adlarıyla güncelleyin

INSERT INTO MenuItems (RestaurantID, Name, Description, Price, Category, ImageURL, IsAvailable)
VALUES (92, N'Çiğ Köfte Sos (18 gr.)', N'Çiğ köfte ile servis için özel sos (18 gr.)', 10.00, N'Ekstralar', 'cigkofte_sos_18gr.jpg', 1);

INSERT INTO MenuItems (RestaurantID, Name, Description, Price, Category, ImageURL, IsAvailable)
VALUES (92, N'Çok Acı Sos (18 gr.)', N'Çok acı sos (18 gr.)', 10.00, N'Ekstralar', 'cok_aci_sos_18gr.jpg', 1);

INSERT INTO MenuItems (RestaurantID, Name, Description, Price, Category, ImageURL, IsAvailable)
VALUES (92, N'Lavaş (2 Adet)', N'2 adet taze lavaş', 10.00, N'Ekstralar', 'lavas_2adet.jpg', 1);

INSERT INTO MenuItems (RestaurantID, Name, Description, Price, Category, ImageURL, IsAvailable)
VALUES (92, N'Yeşillik (Büyük)', N'Büyük porsiyon taze yeşillik', 60.00, N'Ekstralar', 'yesillik_buyuk.jpg', 1);

GO

PRINT 'Komagene için ekstralar başarıyla eklendi (dosya çalıştırıldığında).';
