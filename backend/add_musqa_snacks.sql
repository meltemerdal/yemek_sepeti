-- Musqa Burger MNG - Atıştırmalıklar Kategorisi
USE YemekSepetiDB;
GO

-- Yakında placeholder'ı kaldır
DELETE FROM dbo.MenuItems 
WHERE RestaurantID = 31 
AND Category = N'Atıştırmalıklar' 
AND Name = N'[Yakında]';

-- 5 Atıştırmalık Ekleme
INSERT INTO dbo.MenuItems (RestaurantID, Name, Description, Price, Category, IsAvailable, Stock, CreatedAt)
VALUES 
(31, N'Patates Kızartması', N'Dilimlenmiş patateslerin kızartılarak servis edildiği bir atıştırmalıktır.', 85.00, N'Atıştırmalıklar', 1, 100, GETDATE()),
(31, N'Tavuk Nugget (7 Adet)', N'Çıtır tavuk parçaları', 105.00, N'Atıştırmalıklar', 1, 100, GETDATE()),
(31, N'Cheddar Soslu Patates Kızartması', N'Parmak dilim', 95.00, N'Atıştırmalıklar', 1, 100, GETDATE()),
(31, N'Susamlı Soğan Halkası (7 Adet)', N'Susamlı çıtır soğan halkaları', 85.00, N'Atıştırmalıklar', 1, 100, GETDATE()),
(31, N'Mozzarella Sticks (5 Adet)', N'Çıtır mozzarella çubukları', 125.00, N'Atıştırmalıklar', 1, 100, GETDATE());

PRINT '5 atıştırmalık ürünü başarıyla eklendi!';
GO
