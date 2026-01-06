-- Beyzade Mantı - Mantılar Kategorisi Menü Ekleme
-- Tarih: 13 Aralık 2025
-- RestaurantID: 56 (Beyzade Mantı)
-- Category: Mantılar

-- Önce mevcut menüleri sil
DELETE FROM MenuItems WHERE RestaurantID = 56;

-- Menüleri ekle
INSERT INTO MenuItems (RestaurantID, Name, Description, Price, Category, ImageURL, IsAvailable)
VALUES 
-- Mantılar
(56, N'Mantı', N'Küçük hamur parçalarının kaynar su ile pişirilip üzerine yoğurt ve sos ilave edilerek servis edildiği bir yemektir.', 300.00, N'Mantılar', 'manti.jpg', 1),
(56, N'Mantı (1 kg.) (Pişirilmemiş)', N'Pişirilmemiş, 1 kg.', 500.00, N'Mantılar', 'pismemismanti.jpg', 1),
-- Pilavlar
(56, N'Pirinç Pilavı', N'Pirinçlerin yağ ile kavrulup su eklenerek pişirilmesiyle elde edilen yemektir.', 100.00, N'Pilavlar', 'pirincpilavi.jpg', 1),
(56, N'Bulgur Pilavı', N'Bulgur, mercimek', 90.00, N'Pilavlar', 'bulgurpilavi.jpg', 1),
-- Ana Yemekler
(56, N'Izgara Köfte', N'Pilav, ekmek, salata, patates kızartması ile', 300.00, N'Ana Yemekler', 'izgarakofte.jpg', 1),
(56, N'Kuru Fasulye & Pilav', N'Ekmek, salata ile', 300.00, N'Ana Yemekler', 'kurufasulye.jpg', 1),
(56, N'Lahana Sarması', N'Ekmek, salata, yoğurt ile', 300.00, N'Ana Yemekler', 'lahanasarmasi.jpg', 1),
(56, N'Sulu Köfte', N'Ekmek, salata ile', 300.00, N'Ana Yemekler', 'sulukofte.jpg', 1),
(56, N'Et Güveç', N'Ekmek, salata ile', 300.00, N'Ana Yemekler', 'etguvec.jpg', 1),
(56, N'Bayburt Tava', N'Ekmek, salata ile', 300.00, N'Ana Yemekler', 'bayburttava.jpg', 1),
-- Tatlılar
(56, N'Kabak Tatlısı', N'Tahin, ceviz ile', 100.00, N'Tatlılar', 'kabaktatlisi.jpg', 1),
-- İçecekler
(56, N'Coca-Cola (33 cl.)', N'Kutu İçecek', 55.00, N'İçecekler', 'cocacola.jpg', 1),
(56, N'Fanta (33 cl.)', N'Kutu İçecek', 55.00, N'İçecekler', 'fanta.jpg', 1),
(56, N'Cappy (33 cl.)', N'Kutu İçecek', 55.00, N'İçecekler', 'cappy.jpg', 1),
(56, N'Fuse Tea (33 cl.)', N'Kutu İçecek', 55.00, N'İçecekler', 'fusetea.jpg', 1),
(56, N'Su (50 cl.)', N'Pet Şişe', 15.00, N'İçecekler', 'su.jpg', 1),
(56, N'Ayran (20 cl.)', N'Küçük', 25.00, N'İçecekler', 'ayran.jpg', 1);

-- Eklenen menüleri göster
SELECT m.MenuItemID, m.Name, m.Description, m.Price, m.Category, m.ImageURL
FROM MenuItems m
WHERE m.RestaurantID = 56
ORDER BY m.Name;

PRINT 'Beyzade Mantı - Mantılar menüsü ekleme işlemi tamamlandı!';
