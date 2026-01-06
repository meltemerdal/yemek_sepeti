<?php
/**
 * Sarıyer Börekçisi Ekleme/Güncelleme
 */

require_once 'config.php';

// Sarıyer Börekçisi'ni ekle veya güncelle
$checkSql = "SELECT RestaurantID FROM Restaurants WHERE RestaurantID = 59";
$checkStmt = sqlsrv_query($conn, $checkSql);

$exists = false;
if ($checkStmt) {
    if (sqlsrv_fetch_array($checkStmt, SQLSRV_FETCH_ASSOC)) {
        $exists = true;
    }
    sqlsrv_free_stmt($checkStmt);
}

if ($exists) {
    // Güncelle
    $sql = "UPDATE Restaurants SET 
            Name = N'Sarıyer Börekçisi',
            Category = N'Kahvaltı & Börek',
            Address = N'Sarıyer, Rumeli Hisarı Cad. No: 45, İstanbul',
            ImageURL = 'sariyerborekcisi.jpg',
            Rating = 4.60,
            DeliveryTime = 25,
            MinOrderAmount = 45.00,
            IsOpen = 1,
            IsActive = 1,
            Description = N'Restoran Teslimatlı'
            WHERE RestaurantID = 59";
    
    $result = sqlsrv_query($conn, $sql);
    
    if ($result) {
        echo "✅ Sarıyer Börekçisi başarıyla güncellendi!<br><br>";
    } else {
        echo "❌ Güncelleme hatası: " . print_r(sqlsrv_errors(), true);
    }
} else {
    // Ekle
    $sql = "SET IDENTITY_INSERT Restaurants ON;
            INSERT INTO Restaurants (RestaurantID, Name, Category, Address, ImageURL, Rating, DeliveryTime, MinOrderAmount, IsOpen, IsActive, Description)
            VALUES (59, N'Sarıyer Börekçisi', N'Kahvaltı & Börek', N'Sarıyer, Rumeli Hisarı Cad. No: 45, İstanbul', 
                    'sariyerborekcisi.jpg', 4.60, 25, 45.00, 1, 1, N'Restoran Teslimatlı');
            SET IDENTITY_INSERT Restaurants OFF;";
    
    $result = sqlsrv_query($conn, $sql);
    
    if ($result) {
        echo "✅ Sarıyer Börekçisi başarıyla eklendi!<br><br>";
    } else {
        echo "❌ Ekleme hatası: " . print_r(sqlsrv_errors(), true);
    }
}

// Kontrol et
echo "<h3>Güncel Bilgiler:</h3>";
$verifySql = "SELECT * FROM Restaurants WHERE RestaurantID = 59";
$verifyStmt = sqlsrv_query($conn, $verifySql);

if ($verifyStmt) {
    $row = sqlsrv_fetch_array($verifyStmt, SQLSRV_FETCH_ASSOC);
    if ($row) {
        echo "<table border='1' cellpadding='5'>";
        foreach ($row as $key => $value) {
            echo "<tr><td><strong>$key</strong></td><td>$value</td></tr>";
        }
        echo "</table>";
    }
    sqlsrv_free_stmt($verifyStmt);
}

sqlsrv_close($conn);
?>
