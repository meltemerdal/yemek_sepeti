-- Yemeksepeti View Oluşturma
-- Tarih: 24 Kasım 2025

USE YemekSepetiDB;
GO

-- VIEW: Detaylı sipariş görünümü (tüm ilişkili bilgilerle)
CREATE VIEW VW_OrderDetails
AS
SELECT 
    o.OrderID,
    o.OrderDate,
    u.FullName AS CustomerName,
    u.Email AS CustomerEmail,
    u.Phone AS CustomerPhone,
    r.Name AS RestaurantName,
    r.Category AS RestaurantCategory,
    r.Phone AS RestaurantPhone,
    a.Title AS AddressTitle,
    a.AddressText AS DeliveryAddress,
    a.District,
    a.City,
    mi.Name AS ItemName,
    mi.Category AS ItemCategory,
    od.Quantity,
    od.UnitPrice,
    od.Subtotal,
    o.DeliveryFee,
    o.TotalAmount,
    o.Status,
    o.PaymentMethod,
    o.Note
FROM Orders o
INNER JOIN Users u ON o.UserID = u.UserID
INNER JOIN Restaurants r ON o.RestaurantID = r.RestaurantID
INNER JOIN Addresses a ON o.AddressID = a.AddressID
INNER JOIN OrderDetails od ON o.OrderID = od.OrderID
INNER JOIN MenuItems mi ON od.MenuItemID = mi.MenuItemID;
GO

PRINT 'View başarıyla oluşturuldu!';
GO
