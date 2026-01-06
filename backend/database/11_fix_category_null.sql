-- Category kolonunu NULL kabul edecek ve varsayılan değer atayacak şekilde düzelt
USE YemekSepetiDB;
GO

-- Category alanını NULL kabul edecek şekilde değiştir ve DEFAULT değer ekle
IF EXISTS (SELECT * FROM sys.columns WHERE object_id = OBJECT_ID('Restaurants') AND name = 'Category')
BEGIN
    ALTER TABLE Restaurants
    ALTER COLUMN Category NVARCHAR(50) NULL;
    
    PRINT 'Category kolonu NULL kabul edecek şekilde güncellendi.';
    
    -- Mevcut NULL değerlere 'Genel' ata
    UPDATE Restaurants SET Category = N'Genel' WHERE Category IS NULL;
    
    PRINT 'NULL Category değerleri Genel olarak güncellendi.';
END
GO
