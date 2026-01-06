<?php
/**
 * Kullanıcıya özel sipariş numarası migration
 * Tarih: 8 Aralık 2025
 */

require_once 'config.php';

echo "=== Sipariş Numarası Migration Başlıyor ===\n\n";

try {
    // 1. UserOrderNumber kolonu ekle
    echo "1. UserOrderNumber kolonu ekleniyor...\n";
    $checkColumn = $pdo->query("SELECT * FROM INFORMATION_SCHEMA.COLUMNS 
                                WHERE TABLE_NAME = 'Orders' AND COLUMN_NAME = 'UserOrderNumber'");
    
    if ($checkColumn->rowCount() == 0) {
        $pdo->exec("ALTER TABLE Orders ADD UserOrderNumber INT");
        echo "   ✓ UserOrderNumber kolonu eklendi\n\n";
    } else {
        echo "   ✓ UserOrderNumber kolonu zaten mevcut\n\n";
    }
    
    // 2. Mevcut siparişler için UserOrderNumber değerlerini güncelle
    echo "2. Mevcut siparişler güncelleniyor...\n";
    $sql = "
    WITH OrderedOrders AS (
        SELECT 
            OrderID,
            UserID,
            ROW_NUMBER() OVER (PARTITION BY UserID ORDER BY OrderDate, OrderID) AS RowNum
        FROM Orders
    )
    UPDATE Orders
    SET UserOrderNumber = oo.RowNum
    FROM Orders o
    INNER JOIN OrderedOrders oo ON o.OrderID = oo.OrderID
    ";
    
    $pdo->exec($sql);
    echo "   ✓ Mevcut siparişler güncellendi\n\n";
    
    // 3. Trigger oluştur
    echo "3. Otomatik sipariş numarası trigger oluşturuluyor...\n";
    
    // Önce eski trigger'ı sil
    $pdo->exec("IF OBJECT_ID('trg_SetUserOrderNumber', 'TR') IS NOT NULL DROP TRIGGER trg_SetUserOrderNumber");
    
    // Yeni trigger oluştur
    $triggerSql = "
    CREATE TRIGGER trg_SetUserOrderNumber
    ON Orders
    AFTER INSERT
    AS
    BEGIN
        SET NOCOUNT ON;
        
        UPDATE o
        SET UserOrderNumber = (
            SELECT COUNT(*) 
            FROM Orders 
            WHERE UserID = i.UserID 
            AND OrderID <= i.OrderID
        )
        FROM Orders o
        INNER JOIN inserted i ON o.OrderID = i.OrderID;
    END
    ";
    
    $pdo->exec($triggerSql);
    echo "   ✓ Trigger başarıyla oluşturuldu\n\n";
    
    // 4. Sonuçları kontrol et
    echo "4. Kullanıcıların sipariş sayıları:\n";
    $result = $pdo->query("
        SELECT 
            u.FullName,
            u.Email,
            COUNT(o.OrderID) as SiparişSayısı,
            MAX(o.UserOrderNumber) as SonSiparişNo
        FROM Users u
        LEFT JOIN Orders o ON u.UserID = o.UserID
        GROUP BY u.UserID, u.FullName, u.Email
        HAVING COUNT(o.OrderID) > 0
        ORDER BY u.FullName
    ");
    
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        echo sprintf("   %-30s %-30s Sipariş: %2d (Son: #%d)\n", 
                     $row['FullName'], 
                     $row['Email'], 
                     $row['SiparişSayısı'],
                     $row['SonSiparişNo'] ?? 0);
    }
    
    echo "\n=== Migration Başarıyla Tamamlandı! ===\n";
    echo "\nArtık her kullanıcının sipariş numarası 1'den başlayacak.\n";
    echo "Yeni sipariş verildiğinde otomatik olarak kullanıcının sıradaki numarası atanacak.\n";
    
} catch (PDOException $e) {
    echo "❌ HATA: " . $e->getMessage() . "\n";
    exit(1);
}
