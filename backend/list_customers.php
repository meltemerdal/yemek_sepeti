<?php
require_once 'config.php';

echo "=== Müşteri Hesapları ===\n\n";

$stmt = $pdo->query("
    SELECT u.UserID, u.Email, u.FullName, u.UserType, COUNT(o.OrderID) as OrderCount
    FROM Users u
    LEFT JOIN Orders o ON u.UserID = o.UserID
    WHERE u.UserType = 'Customer'
    GROUP BY u.UserID, u.Email, u.FullName, u.UserType
    ORDER BY OrderCount DESC
");

$customers = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($customers as $customer) {
    echo "User ID: " . $customer['UserID'] . "\n";
    echo "Email: " . $customer['Email'] . "\n";
    echo "Ad: " . $customer['FullName'] . "\n";
    echo "Sipariş Sayısı: " . $customer['OrderCount'] . "\n";
    echo "---\n";
}

echo "\nToplam: " . count($customers) . " müşteri\n";
