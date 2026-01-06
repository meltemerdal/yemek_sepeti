-- Jamal Soğuk Kahveler kategorisinde çift kayıtları siler, her üründen sadece bir tane bırakır
USE YemekSepetiDB;

DECLARE @RestaurantID INT;
SELECT TOP 1 @RestaurantID = RestaurantID FROM Restaurants WHERE Name LIKE N'%Jamal%';

WITH Duplicates AS (
    SELECT MenuItemID, Name,
           ROW_NUMBER() OVER (PARTITION BY Name ORDER BY MenuItemID) AS rn
    FROM MenuItems
    WHERE RestaurantID = @RestaurantID AND Category = N'Soğuk Kahveler'
)
DELETE FROM MenuItems WHERE MenuItemID IN (SELECT MenuItemID FROM Duplicates WHERE rn > 1);

PRINT 'Jamal Soğuk Kahveler kategorisinde çift kayıtlar silindi.';