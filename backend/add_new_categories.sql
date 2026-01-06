-- Yeni Kategoriler Ekleme
-- Tarih: 12 Aralık 2025

-- Gözlemeler kategorisi (zaten var, kontrol amaçlı)
IF NOT EXISTS (SELECT 1 FROM Categories WHERE Name = N'Gözlemeler')
BEGIN
    INSERT INTO Categories (Name, IsActive) VALUES (N'Gözlemeler', 1);
    PRINT 'Gözlemeler kategorisi eklendi';
END
ELSE
    PRINT 'Gözlemeler kategorisi zaten mevcut';

-- Sandviçler kategorisi
IF NOT EXISTS (SELECT 1 FROM Categories WHERE Name = N'Sandviçler')
BEGIN
    INSERT INTO Categories (Name, IsActive) VALUES (N'Sandviçler', 1);
    PRINT 'Sandviçler kategorisi eklendi';
END
ELSE
    PRINT 'Sandviçler kategorisi zaten mevcut';

-- Börekler kategorisi
IF NOT EXISTS (SELECT 1 FROM Categories WHERE Name = N'Börekler')
BEGIN
    INSERT INTO Categories (Name, IsActive) VALUES (N'Börekler', 1);
    PRINT 'Börekler kategorisi eklendi';
END
ELSE
    PRINT 'Börekler kategorisi zaten mevcut';

-- Fırın Ürünleri kategorisi
IF NOT EXISTS (SELECT 1 FROM Categories WHERE Name = N'Fırın Ürünleri')
BEGIN
    INSERT INTO Categories (Name, IsActive) VALUES (N'Fırın Ürünleri', 1);
    PRINT 'Fırın Ürünleri kategorisi eklendi';
END
ELSE
    PRINT 'Fırın Ürünleri kategorisi zaten mevcut';

-- Salatalar kategorisi
IF NOT EXISTS (SELECT 1 FROM Categories WHERE Name = N'Salatalar')
BEGIN
    INSERT INTO Categories (Name, IsActive) VALUES (N'Salatalar', 1);
    PRINT 'Salatalar kategorisi eklendi';
END
ELSE
    PRINT 'Salatalar kategorisi zaten mevcut';

-- İçecekler kategorisi
IF NOT EXISTS (SELECT 1 FROM Categories WHERE Name = N'İçecekler')
BEGIN
    INSERT INTO Categories (Name, IsActive) VALUES (N'İçecekler', 1);
    PRINT 'İçecekler kategorisi eklendi';
END
ELSE
    PRINT 'İçecekler kategorisi zaten mevcut';

-- Tüm kategorileri listele
SELECT CategoryID, Name, IsActive, CreatedDate 
FROM Categories 
ORDER BY Name;

PRINT 'Kategori ekleme işlemi tamamlandı!';
