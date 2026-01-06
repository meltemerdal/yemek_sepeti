-- SP_GetRestaurants prosedürünü güncelle - sadece onaylı restoranları getir
IF OBJECT_ID('SP_GetRestaurants', 'P') IS NOT NULL
    DROP PROCEDURE SP_GetRestaurants;
GO

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
        IsActive,
        ImageUrl
    FROM Restaurants
    WHERE 
        IsActive = 1
        AND Status = 'approved'
        AND (@Category IS NULL OR Category = @Category)
        AND Rating >= @MinRating
    ORDER BY Rating DESC, Name;
END
GO
