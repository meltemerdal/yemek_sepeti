USE YemekSepetiDB;

-- Sariyer Börekçisi
INSERT INTO Restaurants (Name, Category, Address, Phone, Rating, DeliveryTime, MinOrderAmount, IsActive)
VALUES ('Sariyer Börekçisi', 'Kahvaltı & Börek', 'Sarıyer, Rumeli Hisarı Cad. No: 45, İstanbul', '0212 555 0801', 4.6, 25, 45.00, 1);

-- Pidecix
INSERT INTO Restaurants (Name, Category, Address, Phone, Rating, DeliveryTime, MinOrderAmount, IsActive)
VALUES ('Pidecix', 'Kahvaltı & Börek', 'Kadıköy, Bahariye Cad. No: 89, İstanbul', '0216 555 0802', 4.4, 30, 50.00, 1);

-- Duru Boutique Mutfak & Cafe (Kahvaltı kategorisi için)
INSERT INTO Restaurants (Name, Category, Address, Phone, Rating, DeliveryTime, MinOrderAmount, IsActive)
VALUES ('Duru Boutique Mutfak & Cafe', 'Kahvaltı & Börek', 'Kadıköy, Moda Cad. No: 234, İstanbul', '0216 555 0803', 4.5, 35, 60.00, 1);

-- Hünerli Eller (Kahvaltı kategorisi için)
INSERT INTO Restaurants (Name, Category, Address, Phone, Rating, DeliveryTime, MinOrderAmount, IsActive)
VALUES ('Hünerli Eller', 'Kahvaltı & Börek', 'Bahçelievler, 7. Cad. No: 45, Ankara', '0312 555 0804', 4.3, 30, 55.00, 1);
