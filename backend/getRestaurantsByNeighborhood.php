<?php
header('Content-Type: application/json; charset=utf-8');

// Hata ayıklama için (geliştirme aşamasında aç)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// config.php'yi dahil et (bağlantı ve fonksiyonlar için)
require_once __DIR__ . '/config.php';

// POST ile gelen JSON'u al
$input = json_decode(file_get_contents('php://input'), true);
$neighborhood = isset($input['neighborhood']) ? $input['neighborhood'] : '';
if (!$neighborhood) {
    echo json_encode([]);
    exit;
}

// MSSQL'de LIKE sorgusu için % işaretiyle arama
$sql = "SELECT RestaurantID, Name, Category, Address, Phone, Rating, MinOrderAmount, DeliveryTime, Description FROM Restaurants WHERE Address LIKE ? AND IsActive = 1";
$params = array('%' . $neighborhood . '%');
$result = executeQuery($conn, $sql, $params);

if ($result['success']) {
    echo json_encode($result['data'], JSON_UNESCAPED_UNICODE);
} else {
    echo json_encode([]);
}
