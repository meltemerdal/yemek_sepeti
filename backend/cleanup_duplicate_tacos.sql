-- Musqa Burger MNG - Tacolar Kategorisi Tekrarlanan Kayıtları Temizleme
USE YemekSepetiDB;
GO

-- Tekrarlanan kayıtları bul ve sadece en düşük MenuItemID'ye sahip olanı tut
WITH CTE AS (
    SELECT 
        MenuItemID,
        Name,
        ROW_NUMBER() OVER (PARTITION BY Name ORDER BY MenuItemID ASC) AS RowNum
    FROM dbo.MenuItems
    WHERE RestaurantID = 31 
    AND Category = N'Tacolar'
    AND Name IN (N'Musqa Taco', N'Mexico Taco')
)
DELETE FROM dbo.MenuItems
WHERE MenuItemID IN (
    SELECT MenuItemID FROM CTE WHERE RowNum > 1
);

PRINT 'Tekrarlanan taco kayıtları temizlendi!';
GO
