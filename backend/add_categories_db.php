<?php
require_once 'config.php';

try {
    $connectionInfo = array(
        "Database" => $database,
        "CharacterSet" => "UTF-8"
    );
    
    $conn = sqlsrv_connect($serverName, $connectionInfo);
    
    if ($conn === false) {
        die("Bağlantı hatası: " . print_r(sqlsrv_errors(), true));
    }
    
    $categories = ['Gözlemeler', 'Sandviçler', 'Börekler', 'Fırın Ürünleri', 'Salatalar', 'İçecekler'];
    
    echo "<h2>Kategori Ekleme Sonuçları</h2>\n";
    
    foreach ($categories as $category) {
        // Önce kategori var mı kontrol et
        $checkQuery = "SELECT CategoryID FROM Categories WHERE Name = ?";
        $checkStmt = sqlsrv_query($conn, $checkQuery, array($category));
        
        if ($checkStmt === false) {
            echo "❌ Hata (kontrol): " . print_r(sqlsrv_errors(), true) . "\n";
            continue;
        }
        
        $exists = sqlsrv_fetch_array($checkStmt, SQLSRV_FETCH_ASSOC);
        
        if ($exists) {
            echo "ℹ️ '{$category}' kategorisi zaten mevcut. (ID: {$exists['CategoryID']})\n";
        } else {
            // Kategori yoksa ekle
            $insertQuery = "INSERT INTO Categories (Name) VALUES (?)";
            $insertStmt = sqlsrv_query($conn, $insertQuery, array($category));
            
            if ($insertStmt === false) {
                echo "❌ Hata (ekleme): " . print_r(sqlsrv_errors(), true) . "\n";
            } else {
                // Son eklenen ID'yi al
                $idQuery = "SELECT SCOPE_IDENTITY() as CategoryID";
                $idStmt = sqlsrv_query($conn, $idQuery);
                $idResult = sqlsrv_fetch_array($idStmt, SQLSRV_FETCH_ASSOC);
                echo "✅ '{$category}' kategorisi eklendi! (ID: {$idResult['CategoryID']})\n";
            }
        }
    }
    
    echo "\n<h3>Tüm Kategoriler:</h3>\n";
    $allQuery = "SELECT CategoryID, Name FROM Categories ORDER BY Name";
    $allStmt = sqlsrv_query($conn, $allQuery);
    
    if ($allStmt === false) {
        echo "❌ Hata: " . print_r(sqlsrv_errors(), true) . "\n";
    } else {
        while ($cat = sqlsrv_fetch_array($allStmt, SQLSRV_FETCH_ASSOC)) {
            echo "ID: {$cat['CategoryID']} - {$cat['Name']}\n";
        }
    }
    
    sqlsrv_close($conn);
    
} catch (Exception $e) {
    echo "❌ Hata: " . $e->getMessage() . "\n";
}
?>
