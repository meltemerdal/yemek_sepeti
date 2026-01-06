<?php
require 'backend/config.php';

echo "=== Usta Dönerci Bilgileri Güncelleniyor ===\n\n";

try {
    $sql = "UPDATE Restaurants 
            SET Address = 'Bağdat Cad. No:156, Kadıköy',
                Phone = '0216 445 67 89',
                Rating = 4.6,
                MinOrderAmount = 85.00,
                DeliveryTime = 28,
                ImageURL = 'https://images.unsplash.com/photo-1599487488170-d11ec9c172f0?w=400&h=300&fit=crop',
                Description = 'Lezzetli döner ve Türk mutfağı',
                IsOpen = 1
            WHERE RestaurantID = 38";
    
    $pdo->exec($sql);
    
    echo "✓ Usta Dönerci bilgileri güncellendi!\n\n";
    
    // Güncel bilgileri göster
    $stmt = $pdo->query("SELECT * FROM Restaurants WHERE RestaurantID = 38");
    $restaurant = $stmt->fetch();
    
    echo "Güncel Bilgiler:\n";
    echo "ID: " . $restaurant['RestaurantID'] . "\n";
    echo "İsim: " . $restaurant['Name'] . "\n";
    echo "Kategori: " . $restaurant['Category'] . "\n";
    echo "Adres: " . $restaurant['Address'] . "\n";
    echo "Telefon: " . $restaurant['Phone'] . "\n";
    echo "Puan: " . $restaurant['Rating'] . "\n";
    echo "Min. Sipariş: " . $restaurant['MinOrderAmount'] . " ₺\n";
    echo "Teslimat: " . $restaurant['DeliveryTime'] . " dk\n";
    echo "Açıklama: " . $restaurant['Description'] . "\n";
    echo "Görsel: " . $restaurant['ImageURL'] . "\n";
    
} catch (PDOException $e) {
    echo "❌ HATA: " . $e->getMessage() . "\n";
}
