-- Restaurants tablosuna Status sütunu ekle
IF NOT EXISTS (SELECT * FROM sys.columns WHERE object_id = OBJECT_ID(N'Restaurants') AND name = 'Status')
BEGIN
    ALTER TABLE Restaurants
    ADD Status NVARCHAR(20) DEFAULT 'pending' NOT NULL;
END
GO

-- Mevcut restoranları onaylı yap
UPDATE Restaurants SET Status = 'approved' WHERE Status = 'pending';
GO

-- OwnerUserID ve ImageUrl sütunlarını ekle (yoksa)
IF NOT EXISTS (SELECT * FROM sys.columns WHERE object_id = OBJECT_ID(N'Restaurants') AND name = 'OwnerUserID')
BEGIN
    ALTER TABLE Restaurants
    ADD OwnerUserID INT NULL;
END
GO

IF NOT EXISTS (SELECT * FROM sys.columns WHERE object_id = OBJECT_ID(N'Restaurants') AND name = 'ImageUrl')
BEGIN
    ALTER TABLE Restaurants
    ADD ImageUrl NVARCHAR(200) NULL;
END
GO
