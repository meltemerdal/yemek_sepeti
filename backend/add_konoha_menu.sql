-- Konoha Sushi & Chinese Menü Öğeleri
-- RestaurantID'yi bulmak için önce sorgu çalıştırın: SELECT RestaurantID FROM Restaurants WHERE Name LIKE '%Konoha%'

USE YemekSepetiDB;
GO

-- Konoha'nın RestaurantID'sini değişkene ata (Manuel olarak güncellenecek)
DECLARE @KonohaID INT;
SELECT @KonohaID = RestaurantID FROM dbo.Restaurants WHERE Name LIKE N'%Konoha%';

-- Sushi Setler
INSERT INTO dbo.MenuItems (RestaurantID, Name, Description, Price, Category, IsAvailable, Stock)
VALUES
(@KonohaID, N'Acı Badem Menü (24 Pcs.)', N'California Roll (8 Pcs.) + Sake Ten Roll (4 Pcs.) + Ebi Ten Roll (4 Pcs.) + Spicy Tuna Roll (4 Pcs.) + Philadelphia Roll (4 Pcs.)', 1383, N'Sushi Setler', 1, 50),
(@KonohaID, N'Assorted (46 Pcs.)', N'California Roll (8 Pcs.) + Edo Roll (6 Pcs.) + Sake Maki (6 Pcs.) + Ebi Nigiri (6 Pcs.) + Maguro Nigiri (6 Pcs.) + Canadian Roll (4 Pcs.) + Unagi Roll (4 Pcs.) + Sake Nigiri (6 Pcs.)', 2540, N'Sushi Setler', 1, 30),
(@KonohaID, N'Hinata Salmon Lovers (28 Pcs.)', N'Philadelphia Roll (8 Pcs.) + Sake Maki (6 Pcs.) + Sake Sashimi (3 Pcs.) + Sake Nigiri (6 Pcs.) + Sake Gunkan (2 Pcs.) + Salmon Skin Roll (3 Pcs.)', 1791, N'Sushi Setler', 1, 40),
(@KonohaID, N'Konoha Set (24 Pcs.)', N'Sake Ten Roll (8 Pcs.) + Omg Roll (8 Pcs.) + Gaara Roll (8 Pcs.)', 1504, N'Sushi Setler', 1, 50),
(@KonohaID, N'Mini Assorted (20 Pcs.)', N'Ebi Ten Roll (4 Pcs.) + California Roll (4 Pcs.) + Canadian Roll (4 Pcs.) + Sake Nigiri (4 Pcs.) + Maguro Nigiri (4 Pcs.)', 1197, N'Sushi Setler', 1, 60),
(@KonohaID, N'Naruto Menü (26 Pcs.)', N'California Roll (8 Pcs.) + Sake Maki (6 Pcs.) + Kappa Maki (6 Pcs.) + Maguro Nigiri (6 Pcs.)', 1375, N'Sushi Setler', 1, 50),

-- Menüler
(@KonohaID, N'Tavuklu Noodle Menü', N'Tavuklu Noodle + Çin Böreği (1 pcs.) + İçecek', 601, N'Menüler', 1, 100),
(@KonohaID, N'Dana Etli Noodle Menü', N'Dana Etli Noodle + Çin Böreği (1 pcs.) + İçecek', 650, N'Menüler', 1, 80),
(@KonohaID, N'Karidesli Noodle Menü', N'Karidesli Noodle + Çin Böreği (1 Pcs.) + İçecek', 675, N'Menüler', 1, 70),
(@KonohaID, N'Sebzeli Noodle Menü', N'Sebzeli Noodle + Çin Böreği (1 Pcs.) + İçecek', 581, N'Menüler', 1, 100),

-- Başlangıçlar
(@KonohaID, N'Çin Böreği / Spring Roll (2 Pcs.)', N'Tek kişilik', 253, N'Başlangıçlar', 1, 150),
(@KonohaID, N'Çıtır Çin Mantısı / Crispy Wonton (4 Pcs.)', N'Tek kişilik', 330, N'Başlangıçlar', 1, 120),
(@KonohaID, N'Buharda Çin Mantısı / Steamed Beef Dumplings', N'Tek Kişilik', 330, N'Başlangıçlar', 1, 100),
(@KonohaID, N'Karides Cipsi / Prawn Crackers', N'Tek kişilik', 220, N'Başlangıçlar', 1, 200),
(@KonohaID, N'Moğol İşi Tavuk / Mongolian Chicken', N'Tek kişilik', 367, N'Başlangıçlar', 1, 80),
(@KonohaID, N'Edamame', N'Tek kişilik', 230, N'Başlangıçlar', 1, 150),
(@KonohaID, N'Chili Edamame', N'Tek kişilik', 252, N'Başlangıçlar', 1, 150),
(@KonohaID, N'Atom Karides / Atomic Shrimp', N'Tek kişilik', 578, N'Başlangıçlar', 1, 60),
(@KonohaID, N'Çıtır Karides / Crispy Shrimp', N'Tek kişilik', 557, N'Başlangıçlar', 1, 70),
(@KonohaID, N'Dana Etli Gyoza (4 Pcs.)', N'Tek kişilik', 377, N'Başlangıçlar', 1, 100),
(@KonohaID, N'Çıtır Pane Tavuk (10 Pcs.)', N'Tek kişilik', 333, N'Başlangıçlar', 1, 80),
(@KonohaID, N'Hoisen Soslu Ördek Bun (2 Adet)', N'Kuru soğan, dolma biber, ördek (2 adet olarak servis edilmektedir.)', 498, N'Başlangıçlar', 1, 40),
(@KonohaID, N'Black Bean Soslu Dana Bun (2 Adet)', N'Kapya biber, dolma biber, dana eti (2 adet olarak servis edilmektedir.)', 387, N'Başlangıçlar', 1, 50),
(@KonohaID, N'Mu Shu Tavuk Bun (Acılı) (2 Adet)', N'Soya filizi, dolma biber, kapya biber, mantar (2 adet olarak servis edilmektedir.)', 334, N'Başlangıçlar', 1, 60),

-- Çorbalar
(@KonohaID, N'Wonton Çorbası / Wonton Soup', N'Yumurta, taze soğan / Egg, green onion', 250, N'Çorbalar', 1, 100),
(@KonohaID, N'Acılı Ekşili Çorba / Hot & Sour Soup', N'Lahana, havuç, tavuk, kulak mantarı, soya filizi, yumurta / Cabbage, carrot, chicken, wood ear mushroom, bean sprout, egg', 222, N'Çorbalar', 1, 100),
(@KonohaID, N'Deniz Mahsulleri Çorbası / Seafood Soup', N'Kalamar, karides, mantar, taze soğan, yumurta / Calamari, shrimp, mushroom, green onion, egg', 325, N'Çorbalar', 1, 80),
(@KonohaID, N'Sebze Çorbası / Vegetable Soup', N'Lahana, havuç, soya filizi, brokoli, kulak mantarı / Cabbage, carrot, soy bean sprout, broccoli, wood ear mushroom', 226, N'Çorbalar', 1, 120),
(@KonohaID, N'Miso Çorbası', N'Japon mutfağına ait olan miso, tofu, deniz yosunu ile hazırlanan bir çorba türüdür.', 307, N'Çorbalar', 1, 100),

-- Beyaz Etler
(@KonohaID, N'Tatlı Ekşi Soslu Tavuk / Sweet & Sour Chicken', N'Kırmızıbiber, dolmalık biber, ananas, kuru soğan', 532, N'Beyaz Etler', 1, 70),
(@KonohaID, N'Mançuryan Usulü Tavuk / Chicken Manchurian', N'Mantar, sivri biber, kabak, kırmızıbiber, yer fıstığı, soya filizi', 532, N'Beyaz Etler', 1, 70),
(@KonohaID, N'Zencefil Soslu Çıtır Tavuk / Crispy Chicken With Ginger', N'Kuru kırmızıbiber, taze soğan', 547, N'Beyaz Etler', 1, 60),
(@KonohaID, N'General Tso Tavuk / General Tso''s Chicken', N'Kuru kırmızıbiber, susam', 542, N'Beyaz Etler', 1, 70),
(@KonohaID, N'Bademli Tavuk', N'Havuç, dolma biber, kapya biber, kültür mantarı, baby mısır, salatalık, bambu, brokoli, badem', 520, N'Beyaz Etler', 1, 60),
(@KonohaID, N'Teriyaki Soslu Tavuk / Chicken With Teriyaki Sauce', N'Garnitür, Akdeniz yeşillikleri / Garniture, Mediterranean greenery', 547, N'Beyaz Etler', 1, 70),

-- Noodle (Erişte)
(@KonohaID, N'General Tso Tavuk / General Tso''s Chicken', N'Kuru kırmızıbiber, susam', 542, N'Noodle (Erişte)', 1, 70),
(@KonohaID, N'Sebzeli Erişte / Noodle With Vegetables', N'Lahana, havuç, brokoli, taze soğan, soya filizi, kırmızı biber', 377, N'Noodle (Erişte)', 1, 100),
(@KonohaID, N'Dana Etli Erişte / Noodle With Meat', N'Lahana, havuç, brokoli, taze soğan, soya filizi, kırmızı biber', 450, N'Noodle (Erişte)', 1, 80),
(@KonohaID, N'Tavuklu Erişte / Noodle With Chicken', N'Lahana, havuç, brokoli, taze soğan, soya filizi, kırmızı biber', 402, N'Noodle (Erişte)', 1, 90),
(@KonohaID, N'Acılı Ekşili Çorba / Hot & Sour Soup', N'Lahana, havuç, tavuk, kulak mantarı, soya filizi, yumurta / Cabbage, carrot...', 222, N'Noodle (Erişte)', 1, 100),
(@KonohaID, N'California Roll (8 Pcs.)', N'Salatalık, avokado, yengeç, mayonez, tobiko / Cucumber, avocado, crab, mayonnaise, tobiko', 457, N'Noodle (Erişte)', 1, 80);

PRINT 'Konoha Sushi & Chinese menü öğeleri başarıyla eklendi!';
