-- Admin Panel için Tablo Güncellemeleri
-- Tarih: 4 Aralık 2025

USE YemekSepetiDB;
GO

-- Users tablosuna UserType kolonu ekle
IF NOT EXISTS (SELECT * FROM sys.columns WHERE object_id = OBJECT_ID(N'Users') AND name = 'UserType')
BEGIN
    ALTER TABLE Users
    ADD UserType NVARCHAR(20) DEFAULT 'Customer' NOT NULL; -- Customer, RestaurantOwner, Admin
END
GO

-- Restaurants tablosuna OwnerUserID kolonu ekle
IF NOT EXISTS (SELECT * FROM sys.columns WHERE object_id = OBJECT_ID(N'Restaurants') AND name = 'OwnerUserID')
BEGIN
    ALTER TABLE Restaurants
    ADD OwnerUserID INT NULL FOREIGN KEY REFERENCES Users(UserID);
END
GO

-- Restaurants tablosuna Description kolonu ekle
IF NOT EXISTS (SELECT * FROM sys.columns WHERE object_id = OBJECT_ID(N'Restaurants') AND name = 'Description')
BEGIN
    ALTER TABLE Restaurants
    ADD Description NVARCHAR(500) NULL;
END
GO

-- Restaurants tablosuna IsOpen kolonu ekle (Restoran açık/kapalı)
IF NOT EXISTS (SELECT * FROM sys.columns WHERE object_id = OBJECT_ID(N'Restaurants') AND name = 'IsOpen')
BEGIN
    ALTER TABLE Restaurants
    ADD IsOpen BIT DEFAULT 1;
END
GO

-- MenuItems tablosuna ImageURL kolonu ekle
IF NOT EXISTS (SELECT * FROM sys.columns WHERE object_id = OBJECT_ID(N'MenuItems') AND name = 'ImageURL')
BEGIN
    ALTER TABLE MenuItems
    ADD ImageURL NVARCHAR(500) NULL;
END
GO

-- Kategoriler için yeni tablo oluştur
IF NOT EXISTS (SELECT * FROM sys.tables WHERE name = 'Categories')
BEGIN
    CREATE TABLE Categories (
        CategoryID INT PRIMARY KEY IDENTITY(1,1),
        Name NVARCHAR(50) UNIQUE NOT NULL,
        IsActive BIT DEFAULT 1,
        CreatedDate DATETIME DEFAULT GETDATE()
    );
END
GO

-- Varsayılan kategorileri ekle
IF NOT EXISTS (SELECT * FROM Categories)
BEGIN
    INSERT INTO Categories (Name) VALUES 
    ('Burger'), ('Pizza'), ('Kebap'), ('Çiğ Köfte'), 
    ('Balık'), ('Tatlı'), ('Kahve'), ('Döner'), ('Ev Yemekleri'), ('Pide');
END
GO

-- Admin kullanıcısı oluştur (şifre: admin123)
IF NOT EXISTS (SELECT * FROM Users WHERE Email = 'admin@yemeksepeti.com')
BEGIN
    INSERT INTO Users (FullName, Email, Phone, Password, UserType, IsActive)
    VALUES ('Sistem Yöneticisi', 'admin@yemeksepeti.com', '05001234567', 'admin123', 'Admin', 1);
END
GO

PRINT 'Admin panel için tablo güncellemeleri tamamlandı.';
GO
