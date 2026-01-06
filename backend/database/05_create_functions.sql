-- Yemeksepeti Fonksiyonlar
-- Tarih: 24 Kasım 2025

USE YemekSepetiDB;
GO

-- FUNCTION 1: Sipariş toplam tutarını hesaplama (detaylardan)
CREATE FUNCTION FN_CalculateOrderTotal
(
    @OrderID INT
)
RETURNS DECIMAL(10,2)
AS
BEGIN
    DECLARE @Total DECIMAL(10,2);
    
    -- Sipariş detaylarından toplam hesapla
    SELECT @Total = SUM(Subtotal)
    FROM OrderDetails
    WHERE OrderID = @OrderID;
    
    -- NULL kontrolü
    IF @Total IS NULL
        SET @Total = 0;
    
    RETURN @Total;
END
GO

-- FUNCTION 2: Restoranın ortalama puanını hesaplama
CREATE FUNCTION FN_GetRestaurantRating
(
    @RestaurantID INT
)
RETURNS DECIMAL(3,2)
AS
BEGIN
    DECLARE @Rating DECIMAL(3,2);
    
    -- Restoran puanını al
    SELECT @Rating = Rating
    FROM Restaurants
    WHERE RestaurantID = @RestaurantID;
    
    -- NULL kontrolü
    IF @Rating IS NULL
        SET @Rating = 0.00;
    
    RETURN @Rating;
END
GO

-- FUNCTION 3: Kullanıcının toplam sipariş sayısını getirme
CREATE FUNCTION FN_GetUserOrderCount
(
    @UserID INT
)
RETURNS INT
AS
BEGIN
    DECLARE @OrderCount INT;
    
    -- Kullanıcının sipariş sayısını hesapla
    SELECT @OrderCount = COUNT(*)
    FROM Orders
    WHERE UserID = @UserID;
    
    -- NULL kontrolü
    IF @OrderCount IS NULL
        SET @OrderCount = 0;
    
    RETURN @OrderCount;
END
GO

PRINT 'Fonksiyonlar başarıyla oluşturuldu!';
GO
