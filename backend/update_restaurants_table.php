<?php
/**
 * Restaurants tablosuna eksik kolonları ekle
 */

require_once 'config.php';

echo "=== Restaurants Tablosu Güncelleniyor ===\n\n";

try {
    // ImageURL kolonu ekle
    echo "1. ImageURL kolonu kontrol ediliyor...\n";
    $checkColumn = $pdo->query("SELECT * FROM INFORMATION_SCHEMA.COLUMNS 
                                WHERE TABLE_NAME = 'Restaurants' AND COLUMN_NAME = 'ImageURL'");
    
    if ($checkColumn->rowCount() == 0) {
        $pdo->exec("ALTER TABLE Restaurants ADD ImageURL NVARCHAR(500)");
        echo "   ✓ ImageURL kolonu eklendi\n\n";
    } else {
        echo "   ✓ ImageURL kolonu zaten mevcut\n\n";
    }
    
    // Description kolonu ekle
    echo "2. Description kolonu kontrol ediliyor...\n";
    $checkColumn = $pdo->query("SELECT * FROM INFORMATION_SCHEMA.COLUMNS 
                                WHERE TABLE_NAME = 'Restaurants' AND COLUMN_NAME = 'Description'");
    
    if ($checkColumn->rowCount() == 0) {
        $pdo->exec("ALTER TABLE Restaurants ADD Description NVARCHAR(500)");
        echo "   ✓ Description kolonu eklendi\n\n";
    } else {
        echo "   ✓ Description kolonu zaten mevcut\n\n";
    }
    
    // IsOpen kolonu ekle
    echo "3. IsOpen kolonu kontrol ediliyor...\n";
    $checkColumn = $pdo->query("SELECT * FROM INFORMATION_SCHEMA.COLUMNS 
                                WHERE TABLE_NAME = 'Restaurants' AND COLUMN_NAME = 'IsOpen'");
    
    if ($checkColumn->rowCount() == 0) {
        $pdo->exec("ALTER TABLE Restaurants ADD IsOpen BIT DEFAULT 1");
        echo "   ✓ IsOpen kolonu eklendi\n\n";
    } else {
        echo "   ✓ IsOpen kolonu zaten mevcut\n\n";
    }
    
    echo "=== Tablo Güncelleme Tamamlandı! ===\n";
    
} catch (PDOException $e) {
    echo "❌ HATA: " . $e->getMessage() . "\n";
    exit(1);
}
