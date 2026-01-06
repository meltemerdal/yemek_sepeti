-- Sipariş durum alanını güncelle
USE YemekSepetiDB;
GO

-- Status kolonunu yeniden yapılandır
ALTER TABLE Orders
ALTER COLUMN Status NVARCHAR(30);
GO

-- Mevcut siparişleri varsayılan duruma güncelle
UPDATE Orders 
SET Status = 'onaylandi' 
WHERE Status = 'Hazırlanıyor' OR Status IS NULL;
GO
