-- Starbucks Coffee Sıcak Kahveler Menüsü - Açıklama Güncelleme

USE YemekSepetiDB;
GO

-- Caffe Latte - Açıklama güncelle
UPDATE MenuItems
SET Description = N'Koyu, zengin espressomuz buharda pişirilmiş sütle dengelenir ve üzerine hafif bir köpük tabakası eklenir. Alerjen: Süt',
    Price = 170.00
WHERE RestaurantID = 64 AND Name = N'Caffe Latte';

-- Caramel Latte - Açıklama güncelle
UPDATE MenuItems
SET Description = N'Espresso, kadifemsi buharda pişirilmiş süt ve karamel şurubu, Latte''mize tatlı bir dokunuş katmak için bir araya geliyor. Ekstra pürüzsüz bir deneyim için Starbucks Blonde® Roast ile deneyin. Alerjen: Süt',
    Price = 200.00
WHERE RestaurantID = 64 AND Name = N'Caramel Latte';

-- Americano - Açıklama güncelle
UPDATE MenuItems
SET Description = N'Espresso shot''ları, hafif bir krema tabakası elde etmek için sıcak su ile doldurulur.',
    Price = 145.00
WHERE RestaurantID = 64 AND Name = N'Americano';

-- Caramel Macchiato - Açıklama güncelle
UPDATE MenuItems
SET Description = N'Vanilya şurubu üzerine buharla özenle ısıtılmış süt, süt köpüğü ve espresso dokunuşu, karamel sosla süslenerek sunulur.',
    Price = 200.00
WHERE RestaurantID = 64 AND Name = N'Caramel Macchiato';

-- Mocha - Açıklama güncelle
UPDATE MenuItems
SET Description = N'Mocha şurubu ve buharda pişirilmiş süt ile birleştirilmiş espresso, krem şanti ile tamamlandı. Alerjen: Süt',
    Price = 205.00
WHERE RestaurantID = 64 AND Name = N'Mocha';

-- Blonde Latte - Açıklama güncelle
UPDATE MenuItems
SET Description = N'Yumuşak İçimli Sütlü Kahve Alerjen: Süt',
    Price = 195.00
WHERE RestaurantID = 64 AND Name = N'Blonde Latte';

-- Misto - Açıklama güncelle
UPDATE MenuItems
SET Description = N'Taze demlenmiş kahve ve buğulanmış sütün bire bir karışımı. Alerjen: Süt',
    Price = 140.00
WHERE RestaurantID = 64 AND Name = N'Misto';

GO

-- Kontrol
SELECT MenuItemID, Name, Price, Category 
FROM MenuItems 
WHERE RestaurantID = 64 AND Category = N'Sicak Kahveler'
ORDER BY Price DESC;
