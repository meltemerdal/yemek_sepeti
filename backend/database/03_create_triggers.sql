-- Yemeksepeti Trigger'lar
-- Tarih: 24 Kasım 2025

USE YemekSepetiDB;
GO

-- TRIGGER 1: Sipariş detayı eklendiğinde stok azaltma ve toplam güncelleme
CREATE TRIGGER TRG_OrderDetail_AfterInsert
ON OrderDetails
AFTER INSERT
AS
BEGIN
    SET NOCOUNT ON;
    
    DECLARE @OrderID INT;
    DECLARE @MenuItemID INT;
    DECLARE @Quantity INT;
    
    -- Eklenen sipariş detaylarını al
    SELECT @OrderID = OrderID, @MenuItemID = MenuItemID, @Quantity = Quantity
    FROM inserted;
    
    -- Stok kontrolü ve azaltma
    UPDATE MenuItems
    SET Stock = Stock - @Quantity
    WHERE MenuItemID = @MenuItemID AND Stock >= @Quantity;
    
    -- Eğer stok yetersizse uyarı
    IF @@ROWCOUNT = 0
    BEGIN
        RAISERROR('Stok yetersiz!', 16, 1);
        ROLLBACK TRANSACTION;
        RETURN;
    END
    
    -- Sipariş toplam tutarını güncelle
    UPDATE Orders
    SET TotalAmount = (
        SELECT SUM(Subtotal) + DeliveryFee
        FROM OrderDetails
        WHERE OrderID = @OrderID
        GROUP BY OrderID
    )
    WHERE OrderID = @OrderID;
    
    PRINT 'Sipariş detayı eklendi, stok güncellendi!';
END
GO

-- TRIGGER 2: Yeni kullanıcı oluşturulduğunda log kayıt
CREATE TRIGGER TRG_User_AfterInsert
ON Users
AFTER INSERT
AS
BEGIN
    SET NOCOUNT ON;
    
    DECLARE @UserID INT;
    DECLARE @FullName NVARCHAR(100);
    
    SELECT @UserID = UserID, @FullName = FullName
    FROM inserted;
    
    -- Kullanıcı aktivite loguna ekle
    INSERT INTO UserActivityLog (UserID, Activity, LogDate)
    VALUES (@UserID, 'Yeni kullanıcı kaydı: ' + @FullName, GETDATE());
    
    PRINT 'Kullanıcı aktivite logu oluşturuldu!';
END
GO

-- TRIGGER 3: Sipariş durumu değiştiğinde log kayıt
CREATE TRIGGER TRG_Order_StatusChange
ON Orders
AFTER UPDATE
AS
BEGIN
    SET NOCOUNT ON;
    
    -- Sadece Status değiştiğinde çalış
    IF UPDATE(Status)
    BEGIN
        DECLARE @OrderID INT;
        DECLARE @OldStatus NVARCHAR(20);
        DECLARE @NewStatus NVARCHAR(20);
        
        SELECT @OrderID = i.OrderID, @NewStatus = i.Status
        FROM inserted i;
        
        SELECT @OldStatus = d.Status
        FROM deleted d;
        
        -- Durum değişikliği varsa log kaydet
        IF @OldStatus != @NewStatus
        BEGIN
            INSERT INTO OrderStatusLog (OrderID, OldStatus, NewStatus, ChangedDate)
            VALUES (@OrderID, @OldStatus, @NewStatus, GETDATE());
            
            PRINT 'Sipariş durum değişikliği loglandı!';
        END
    END
END
GO

-- TRIGGER 4: Sipariş iptal edildiğinde stok geri yükleme
CREATE TRIGGER TRG_Order_CancelStock
ON Orders
AFTER UPDATE
AS
BEGIN
    SET NOCOUNT ON;
    
    -- Sadece Status değiştiğinde ve iptal olduğunda çalış
    IF UPDATE(Status)
    BEGIN
        DECLARE @OrderID INT;
        DECLARE @NewStatus NVARCHAR(20);
        
        SELECT @OrderID = i.OrderID, @NewStatus = i.Status
        FROM inserted i;
        
        -- Sipariş iptal edildiyse stokları geri yükle
        IF @NewStatus = 'İptal'
        BEGIN
            UPDATE m
            SET m.Stock = m.Stock + od.Quantity
            FROM MenuItems m
            INNER JOIN OrderDetails od ON m.MenuItemID = od.MenuItemID
            WHERE od.OrderID = @OrderID;
            
            PRINT 'İptal edilen sipariş için stoklar geri yüklendi!';
        END
    END
END
GO

PRINT 'Trigger''lar başarıyla oluşturuldu!';
GO
