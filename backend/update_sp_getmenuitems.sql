USE YemekSepetiDB;
GO

-- SP_GetMenuItems procedure'ünü güncelle - ImageURL ekle
ALTER PROCEDURE SP_GetMenuItems
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
        Stock,
        ImageURL
    FROM MenuItems
    WHERE 
        RestaurantID = @RestaurantID
        AND IsAvailable = 1
        AND Stock > 0
        AND (@Category IS NULL OR Category = @Category)
    ORDER BY Category, Name;
END
GO

PRINT 'SP_GetMenuItems güncellendi - ImageURL eklendi';
