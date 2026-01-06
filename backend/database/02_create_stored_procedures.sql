-- Yemeksepeti Stored Procedure'ler
-- Tarih: 24 Kasım 2025

USE YemekSepetiDB;
GO

-- SP1: Yeni kullanıcı kaydı oluşturma
CREATE PROCEDURE SP_CreateUser
    @FullName NVARCHAR(100),
    @Email NVARCHAR(100),
    @Phone NVARCHAR(20),
    @Password NVARCHAR(255)
AS
BEGIN
    SET NOCOUNT ON;
    
    -- Email kontrolü
    IF EXISTS (SELECT 1 FROM Users WHERE Email = @Email)
    BEGIN
        PRINT 'Bu email adresi zaten kayıtlı!';
        RETURN -1;
    END
    
    -- Kullanıcı oluştur
    INSERT INTO Users (FullName, Email, Phone, Password, CreatedDate, IsActive)
    VALUES (@FullName, @Email, @Phone, @Password, GETDATE(), 1);
    
    PRINT 'Kullanıcı başarıyla oluşturuldu!';
    RETURN SCOPE_IDENTITY();
END
GO

-- SP2: Restoran listesini getirme (kategori ve rating filtreleme ile)
CREATE PROCEDURE SP_GetRestaurants
    @Category NVARCHAR(50) = NULL,
    @MinRating DECIMAL(3,2) = 0
AS
BEGIN
    SET NOCOUNT ON;
    
    SELECT 
        RestaurantID,
        Name,
        Category,
        Address,
        Phone,
        Rating,
        MinOrderAmount,
        DeliveryTime,
        IsActive
    FROM Restaurants
    WHERE 
        IsActive = 1
        AND (@Category IS NULL OR Category = @Category)
        AND Rating >= @MinRating
    ORDER BY Rating DESC, Name;
END
GO

-- SP3: Menü öğelerini getirme (restoran bazlı)
CREATE PROCEDURE SP_GetMenuItems
    @RestaurantID INT,
    @Category NVARCHAR(50) = NULL
AS
BEGIN
    SET NOCOUNT ON;
    
    SELECT 
        MenuItemID,
        RestaurantID,
        Name,
        Description,
        Price,
        Category,
        IsAvailable,
        Stock
    FROM MenuItems
    WHERE 
        RestaurantID = @RestaurantID
        AND IsAvailable = 1
        AND Stock > 0
        AND (@Category IS NULL OR Category = @Category)
    ORDER BY Category, Name;
END
GO

-- SP4: Sipariş oluşturma
CREATE PROCEDURE SP_CreateOrder
    @UserID INT,
    @RestaurantID INT,
    @AddressID INT,
    @PaymentMethod NVARCHAR(20),
    @Note NVARCHAR(300) = NULL,
    @DeliveryFee DECIMAL(10,2) = 10.00,
    @OrderID INT OUTPUT
AS
BEGIN
    SET NOCOUNT ON;
    BEGIN TRANSACTION;
    
    BEGIN TRY
        -- Kullanıcı kontrolü
        IF NOT EXISTS (SELECT 1 FROM Users WHERE UserID = @UserID AND IsActive = 1)
        BEGIN
            ROLLBACK TRANSACTION;
            PRINT 'Geçersiz kullanıcı!';
            RETURN -1;
        END
        
        -- Restoran kontrolü
        IF NOT EXISTS (SELECT 1 FROM Restaurants WHERE RestaurantID = @RestaurantID AND IsActive = 1)
        BEGIN
            ROLLBACK TRANSACTION;
            PRINT 'Geçersiz restoran!';
            RETURN -2;
        END
        
        -- Adres kontrolü
        IF NOT EXISTS (SELECT 1 FROM Addresses WHERE AddressID = @AddressID AND UserID = @UserID)
        BEGIN
            ROLLBACK TRANSACTION;
            PRINT 'Geçersiz adres!';
            RETURN -3;
        END
        
        -- Sipariş oluştur
        INSERT INTO Orders (UserID, RestaurantID, AddressID, OrderDate, TotalAmount, DeliveryFee, Status, PaymentMethod, Note)
        VALUES (@UserID, @RestaurantID, @AddressID, GETDATE(), 0, @DeliveryFee, 'Hazırlanıyor', @PaymentMethod, @Note);
        
        SET @OrderID = SCOPE_IDENTITY();
        
        COMMIT TRANSACTION;
        PRINT 'Sipariş başarıyla oluşturuldu!';
        RETURN @OrderID;
    END TRY
    BEGIN CATCH
        ROLLBACK TRANSACTION;
        PRINT 'Sipariş oluşturulurken hata oluştu: ' + ERROR_MESSAGE();
        RETURN -99;
    END CATCH
END
GO

-- SP5: Kullanıcının sipariş geçmişini getirme
CREATE PROCEDURE SP_GetUserOrders
    @UserID INT,
    @Status NVARCHAR(20) = NULL
AS
BEGIN
    SET NOCOUNT ON;
    
    SELECT 
        o.OrderID,
        o.OrderDate,
        r.Name AS RestaurantName,
        r.Category AS RestaurantCategory,
        a.AddressText,
        o.TotalAmount,
        o.DeliveryFee,
        o.Status,
        o.PaymentMethod,
        o.Note
    FROM Orders o
    INNER JOIN Restaurants r ON o.RestaurantID = r.RestaurantID
    INNER JOIN Addresses a ON o.AddressID = a.AddressID
    WHERE 
        o.UserID = @UserID
        AND (@Status IS NULL OR o.Status = @Status)
    ORDER BY o.OrderDate DESC;
END
GO

PRINT 'Stored Procedure''ler başarıyla oluşturuldu!';
GO
