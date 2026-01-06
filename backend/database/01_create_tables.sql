-- Yemeksepeti Veritabanı Tablo Oluşturma Script'i
-- Tarih: 24 Kasım 2025

-- Veritabanı oluştur
CREATE DATABASE YemekSepetiDB;
GO

USE YemekSepetiDB;
GO

-- Kullanıcılar tablosu
CREATE TABLE Users (
    UserID INT PRIMARY KEY IDENTITY(1,1),
    FullName NVARCHAR(100) NOT NULL,
    Email NVARCHAR(100) UNIQUE NOT NULL,
    Phone NVARCHAR(20) NOT NULL,
    Password NVARCHAR(255) NOT NULL,
    CreatedDate DATETIME DEFAULT GETDATE(),
    IsActive BIT DEFAULT 1
);
GO

-- Adresler tablosu
CREATE TABLE Addresses (
    AddressID INT PRIMARY KEY IDENTITY(1,1),
    UserID INT FOREIGN KEY REFERENCES Users(UserID),
    Title NVARCHAR(50) NOT NULL, -- Ev, İş vb.
    AddressText NVARCHAR(500) NOT NULL,
    District NVARCHAR(50) NOT NULL,
    City NVARCHAR(50) NOT NULL,
    IsDefault BIT DEFAULT 0,
    CreatedDate DATETIME DEFAULT GETDATE()
);
GO

-- Restoranlar tablosu
CREATE TABLE Restaurants (
    RestaurantID INT PRIMARY KEY IDENTITY(1,1),
    Name NVARCHAR(100) NOT NULL,
    Category NVARCHAR(50) NOT NULL, -- Pizza, Burger, Kebap vb.
    Address NVARCHAR(300) NOT NULL,
    Phone NVARCHAR(20) NOT NULL,
    Rating DECIMAL(3,2) DEFAULT 0.00, -- 0.00 - 5.00
    MinOrderAmount DECIMAL(10,2) DEFAULT 0,
    DeliveryTime INT NOT NULL, -- Dakika cinsinden
    IsActive BIT DEFAULT 1,
    CreatedDate DATETIME DEFAULT GETDATE()
);
GO

-- Menü öğeleri tablosu
CREATE TABLE MenuItems (
    MenuItemID INT PRIMARY KEY IDENTITY(1,1),
    RestaurantID INT FOREIGN KEY REFERENCES Restaurants(RestaurantID),
    Name NVARCHAR(100) NOT NULL,
    Description NVARCHAR(300),
    Price DECIMAL(10,2) NOT NULL,
    Category NVARCHAR(50) NOT NULL, -- Ana Yemek, İçecek, Tatlı vb.
    IsAvailable BIT DEFAULT 1,
    Stock INT DEFAULT 100,
    CreatedDate DATETIME DEFAULT GETDATE()
);
GO

-- Siparişler tablosu
CREATE TABLE Orders (
    OrderID INT PRIMARY KEY IDENTITY(1,1),
    UserID INT FOREIGN KEY REFERENCES Users(UserID),
    RestaurantID INT FOREIGN KEY REFERENCES Restaurants(RestaurantID),
    AddressID INT FOREIGN KEY REFERENCES Addresses(AddressID),
    OrderDate DATETIME DEFAULT GETDATE(),
    TotalAmount DECIMAL(10,2) DEFAULT 0,
    DeliveryFee DECIMAL(10,2) DEFAULT 0,
    Status NVARCHAR(20) DEFAULT 'Hazırlanıyor', -- Hazırlanıyor, Yolda, Teslim Edildi, İptal
    PaymentMethod NVARCHAR(20) NOT NULL, -- Kredi Kartı, Nakit
    Note NVARCHAR(300)
);
GO

-- Sipariş detayları tablosu
CREATE TABLE OrderDetails (
    OrderDetailID INT PRIMARY KEY IDENTITY(1,1),
    OrderID INT FOREIGN KEY REFERENCES Orders(OrderID),
    MenuItemID INT FOREIGN KEY REFERENCES MenuItems(MenuItemID),
    Quantity INT NOT NULL,
    UnitPrice DECIMAL(10,2) NOT NULL,
    Subtotal DECIMAL(10,2) NOT NULL,
    CreatedDate DATETIME DEFAULT GETDATE()
);
GO

-- Kullanıcı işlem log tablosu (Trigger için)
CREATE TABLE UserActivityLog (
    LogID INT PRIMARY KEY IDENTITY(1,1),
    UserID INT,
    Activity NVARCHAR(200) NOT NULL,
    LogDate DATETIME DEFAULT GETDATE()
);
GO

-- Sipariş durum log tablosu (Trigger için)
CREATE TABLE OrderStatusLog (
    LogID INT PRIMARY KEY IDENTITY(1,1),
    OrderID INT,
    OldStatus NVARCHAR(20),
    NewStatus NVARCHAR(20),
    ChangedDate DATETIME DEFAULT GETDATE()
);
GO

PRINT 'Tablolar başarıyla oluşturuldu!';
GO
