<?php
/**
 * Yemeksepeti - MSSQL Veritabanı Bağlantı Dosyası
 * Tarih: 24 Kasım 2025
 * 
 * Bu dosya MSSQL Server'a PHP üzerinden bağlantı sağlar
 */

// Session ayarları - uyarıları önlemek için kontrol
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.use_strict_mode', 1);
    ini_set('session.cookie_httponly', 1);
    ini_set('session.use_only_cookies', 1);
    ini_set('session.gc_maxlifetime', 7200); // 2 saat
    ini_set('session.cookie_lifetime', 7200); // 2 saat
    
    session_set_cookie_params([
        'lifetime' => 7200,
        'path' => '/',
        'secure' => false,
        'httponly' => true,
        'samesite' => 'Lax'
    ]);
    
    session_start();
}

// Veritabanı bağlantı bilgileri
$serverName = "localhost\\SQLEXPRESS"; // SQL Server Express
$database = "YemekSepetiDB";

// Windows Authentication kullan (sa yerine)
$connectionInfo = array(
    "Database" => $database,
    "CharacterSet" => "UTF-8"
);

// SQLSRV bağlantısı (eski API sayfaları için)
$conn = sqlsrv_connect($serverName, $connectionInfo);

// Bağlantı kontrolü
if ($conn === false) {
    throw new Exception("Veritabanı Bağlantı Hatası: " . print_r(sqlsrv_errors(), true));
}

// PDO bağlantısı (admin paneli için)
try {
    $pdo = new PDO("sqlsrv:Server=$serverName;Database=$database", null, null, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (PDOException $e) {
    throw new Exception("PDO Bağlantı Hatası: " . $e->getMessage());
}

/**
 * Stored Procedure çalıştırma fonksiyonu
 */
function executeSP($conn, $spName, $params = array()) {
    $sql = "EXEC $spName";
    
    // Parametreleri ekle
    if (!empty($params)) {
        $paramStr = implode(", ", array_map(function($key, $value) {
            return "@$key = ?";
        }, array_keys($params), $params));
        $sql .= " " . $paramStr;
    }
    
    $stmt = sqlsrv_query($conn, $sql, array_values($params));
    
    if ($stmt === false) {
        return array("success" => false, "error" => sqlsrv_errors());
    }
    
    return array("success" => true, "statement" => $stmt);
}

/**
 * SELECT sorgusu çalıştırma fonksiyonu
 */
function executeQuery($conn, $sql, $params = array()) {
    $stmt = sqlsrv_query($conn, $sql, $params);
    
    if ($stmt === false) {
        return array("success" => false, "error" => sqlsrv_errors());
    }
    
    $results = array();
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $results[] = $row;
    }
    
    sqlsrv_free_stmt($stmt);
    
    return array("success" => true, "data" => $results);
}

/**
 * HTML karakterlerini encode et (XSS koruması)
 */
function clean($data) {
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}

/**
 * Bağlantıyı kapat
 */
function closeConnection($conn) {
    sqlsrv_close($conn);
}

?>
