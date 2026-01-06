-- Domino's Pizza Coca-Cola Fırsat Menüleri - Duplicate Kayıtları Temizleme

USE YemekSepetiDB;
GO

-- Duplicate kayıtları silme (her menüden sadece 1 tane bırakır)
DELETE FROM MenuItems
WHERE MenuItemID IN (
    SELECT MenuItemID
    FROM (
        SELECT 
            m.MenuItemID,
            ROW_NUMBER() OVER (
                PARTITION BY m.RestaurantID, m.Name, m.Category 
                ORDER BY m.MenuItemID
            ) AS RowNum
        FROM MenuItems m
        INNER JOIN Restaurants r ON m.RestaurantID = r.RestaurantID
        WHERE r.Name LIKE '%Domino%' 
        AND m.Category = N'Coca-Cola Fırsat Menüleri'
    ) AS Duplicates
    WHERE RowNum > 1
);

GO

-- Kontrol - Kalan menüleri göster
SELECT m.MenuItemID, r.Name AS RestaurantName, m.Name, m.Price, m.Category 
FROM MenuItems m
INNER JOIN Restaurants r ON m.RestaurantID = r.RestaurantID
WHERE r.Name LIKE '%Domino%' AND m.Category = N'Coca-Cola Fırsat Menüleri'
ORDER BY m.Price;
