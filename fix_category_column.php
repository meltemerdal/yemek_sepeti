<?php
// Category kolonunu NULL kabul edecek şekilde güncelle
require_once 'backend/config.php';

try {
    // Category alanını NULL kabul edecek şekilde değiştir
    $pdo->exec("ALTER TABLE Restaurants ALTER COLUMN Category NVARCHAR(50) NULL");
    echo "✓ Category kolonu NULL kabul edecek şekilde güncellendi.\n";
    
    // Mevcut NULL değerlere 'Genel' ata
    $updated = $pdo->exec("UPDATE Restaurants SET Category = N'Genel' WHERE Category IS NULL");
    echo "✓ $updated adet NULL Category değeri 'Genel' olarak güncellendi.\n";
    
    echo "\n✓✓✓ Veritabanı başarıyla güncellendi! ✓✓✓\n";
} catch (PDOException $e) {
    echo "HATA: " . $e->getMessage() . "\n";
}
