-- Kullanıcıya özel sipariş numarası ekleme
-- Her kullanıcı için sipariş numarası 1'den başlar
-- Tarih: 8 Aralık 2025

USE YemekSepetiDB;
GO

-- UserOrderNumber kolonu ekle
IF NOT EXISTS (SELECT * FROM sys.columns WHERE object_id = OBJECT_ID(N'Orders') AND name = 'UserOrderNumber')
BEGIN
    ALTER TABLE Orders ADD UserOrderNumber INT;
END
GO

-- Mevcut siparişler için UserOrderNumber değerlerini güncelle
WITH OrderedOrders AS (
    SELECT 
        OrderID,
        UserID,
        ROW_NUMBER() OVER (PARTITION BY UserID ORDER BY OrderDate, OrderID) AS RowNum
    FROM Orders
)
UPDATE Orders
SET UserOrderNumber = oo.RowNum
FROM Orders o
INNER JOIN OrderedOrders oo ON o.OrderID = oo.OrderID;
GO

-- Yeni siparişler için otomatik UserOrderNumber hesaplayan trigger
IF OBJECT_ID('trg_SetUserOrderNumber', 'TR') IS NOT NULL
    DROP TRIGGER trg_SetUserOrderNumber;
GO

CREATE TRIGGER trg_SetUserOrderNumber
ON Orders
AFTER INSERT
AS
BEGIN
    SET NOCOUNT ON;
    
    -- Yeni eklenen sipariş için UserOrderNumber hesapla
    UPDATE o
    SET UserOrderNumber = (
        SELECT COUNT(*) 
        FROM Orders 
        WHERE UserID = i.UserID 
        AND OrderID <= i.OrderID
    )
    FROM Orders o
    INNER JOIN inserted i ON o.OrderID = i.OrderID;
END
GO

PRINT 'UserOrderNumber kolonu ve trigger başarıyla eklendi.';
GO
