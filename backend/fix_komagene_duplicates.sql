-- Komagene Duplicate Menüleri Temizle
USE YemekSepetiDB;
GO

-- Duplicate kayıtları sil (en yeni olanı tut)
WITH CTE AS (
    SELECT 
        MenuItemID,
        ROW_NUMBER() OVER (PARTITION BY RestaurantID, Name, Category ORDER BY MenuItemID DESC) AS rn
    FROM MenuItems
    WHERE RestaurantID = 92 AND Category = N'Dürüm Menüler'
)
DELETE FROM CTE WHERE rn > 1;

GO

PRINT 'Duplicate kayıtlar temizlendi!';
