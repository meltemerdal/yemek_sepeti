<?php
/**
 * Usta Dönerci Restoranını Ekle
 * Tarih: 8 Aralık 2025
 */

require_once 'config.php';

echo "=== Usta Dönerci Restoranı Ekleniyor ===\n\n";

try {
    // Usta Dönerci restoranını ekle
    $sql = "INSERT INTO Restaurants (Name, Category, Address, Phone, Rating, MinOrderAmount, DeliveryTime, IsActive, ImageURL, Description, IsOpen) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'Usta Dönerci',
        'Döner',
        'Bağdat Cad. No:156, Kadıköy',
        '0216 445 67 89',
        4.6,
        85.00,
        28,
        1,
        'https://images.unsplash.com/photo-1599487488170-d11ec9c172f0?w=400&h=300&fit=crop',
        'Lezzetli döner ve Türk mutfağı',
        1
    ]);
    
    $restaurantId = $pdo->lastInsertId();
    
    echo "✓ Usta Dönerci eklendi (ID: $restaurantId)\n";
    echo "  Kategori: Döner\n";
    echo "  Adres: Bağdat Cad. No:156, Kadıköy\n";
    echo "  Telefon: 0216 445 67 89\n";
    echo "  Puan: 4.6\n";
    echo "  Min. Sipariş: 85.00 ₺\n";
    echo "  Teslimat Süresi: 28 dk\n\n";
    
    echo "RestaurantID: $restaurantId (Menüleri eklerken bu ID'yi kullanacağız)\n\n";
    echo "=== İşlem Tamamlandı! ===\n";
    
} catch (PDOException $e) {
    echo "❌ HATA: " . $e->getMessage() . "\n";
    exit(1);
}
