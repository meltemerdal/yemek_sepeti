<?php
/**
 * Sarıyer Börekçisi Görsel Güncelleme
 */

require_once 'config.php';

// Sarıyer Börekçisi'nin görselini güncelle (ID: 59)
$sql = "UPDATE Restaurants SET ImageURL = 'sariyerborekcisi.jpg' WHERE RestaurantID = 59";
$result = sqlsrv_query($conn, $sql);

if ($result) {
    echo "✅ Sarıyer Börekçisi görseli başarıyla güncellendi!<br>";
    
    // Kontrol et
    $checkSql = "SELECT RestaurantID, Name, ImageURL FROM Restaurants WHERE RestaurantID = 59";
    $checkResult = sqlsrv_query($conn, $checkSql);
    
    if ($checkResult) {
        $row = sqlsrv_fetch_array($checkResult, SQLSRV_FETCH_ASSOC);
        echo "<br>Güncel bilgiler:<br>";
        echo "ID: " . $row['RestaurantID'] . "<br>";
        echo "İsim: " . $row['Name'] . "<br>";
        echo "Görsel: " . $row['ImageURL'] . "<br>";
    }
} else {
    echo "❌ Güncelleme hatası: " . print_r(sqlsrv_errors(), true);
}

sqlsrv_close($conn);
?>
