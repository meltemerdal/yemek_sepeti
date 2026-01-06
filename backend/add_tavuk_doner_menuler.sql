-- Tavuk Döner Menüleri Ekleme (Usta Dönerci - RestaurantID: 38)

INSERT INTO MenuItems (RestaurantID, Name, Description, Price, Category, Stock, ImageURL)
VALUES 
(38, 'Dürüm Tavuk Döner (80 gr.) Menü', 'Dürüm Tavuk Döner (80 gr.) + Patates Kızartması (Orta) + Ayran (20 cl.)', 255.00, 'Tavuk Döner Menüleri', 100, 'tavukmenu.jpg'),
(38, 'Gyro Soslu Dürüm Tavuk Döner Menü', 'Gyro Soslu Dürüm Tavuk Döner + Patates Kızartması (Orta) + Ayran (20 cl.)', 270.00, 'Tavuk Döner Menüleri', 100, 'gyrotavukmenu.jpg'),
(38, 'Acı Soslu Dürüm Tavuk Döner Menü', 'Acı Soslu Dürüm Tavuk Döner + Patates Kızartması (Orta) + Ayran (20 cl.)', 270.00, 'Tavuk Döner Menüleri', 100, 'acitavukmenu.jpg'),
(38, 'Tombik Tavuk Döner Menü', 'Tombik Tavuk Döner + Patates Kızartması (Orta) + Ayran (20 cl.)', 245.00, 'Tavuk Döner Menüleri', 100, 'tombiktavukmenu.jpg');

-- Kontrol
SELECT MenuItemID, Name, Price, Category, ImageURL
FROM MenuItems
WHERE RestaurantID = 38 AND Category = 'Tavuk Döner Menüleri'
ORDER BY Price DESC;
