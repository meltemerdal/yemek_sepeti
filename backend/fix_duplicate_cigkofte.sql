-- Veritabanındaki tekrarlanan çiğ köfte kayıtlarını temizleme
USE YemekSepetiDB;
GO

-- Önce tekrarlanan kayıtları kontrol et
SELECT Name, COUNT(*) as Tekrar
FROM MenuItems
WHERE RestaurantID = 92 AND Category = N'Çiğ Köfteler'
GROUP BY Name
HAVING COUNT(*) > 1;

-- Tekrarlanan kayıtlardan sadece en düşük ID'li olanı tut, diğerlerini sil
WITH CTE AS (
    SELECT 
        MenuItemID,
        Name,
        ROW_NUMBER() OVER (PARTITION BY Name ORDER BY MenuItemID) as rn
    FROM MenuItems
    WHERE RestaurantID = 92 AND Category = N'Çiğ Köfteler'
)
DELETE FROM CTE WHERE rn > 1;

-- Sonucu kontrol et
SELECT MenuItemID, Name, Price, Description
FROM MenuItems
WHERE RestaurantID = 92 AND Category = N'Çiğ Köfteler'
ORDER BY Name;

GO

PRINT 'Tekrarlanan çiğ köfte kayıtları başarıyla silindi!';
