-- Ton Balıklı Salata görsel düzeltme
UPDATE MenuItems 
SET ImageURL = 'tonbaliklisalata.jpg'
WHERE RestaurantID = 45 
  AND Name = N'Ton Balıklı Salata'
  AND Category = N'Salatalar';

SELECT * FROM MenuItems 
WHERE RestaurantID = 45 AND Category = N'Salatalar'
ORDER BY Name;
