<?php
require_once 'config.php';

header('Content-Type: text/html; charset=utf-8');
echo "<h2>Müşteri Hesapları (Giriş Bilgileri)</h2>";

$stmt = $pdo->query("
    SELECT u.UserID, u.Email, u.Password, u.FullName, COUNT(o.OrderID) as OrderCount
    FROM Users u
    LEFT JOIN Orders o ON u.UserID = o.UserID
    WHERE u.UserType = 'Customer'
    GROUP BY u.UserID, u.Email, u.Password, u.FullName
    ORDER BY OrderCount DESC
");

$customers = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "<table border='1' cellpadding='10'>";
echo "<tr><th>User ID</th><th>Email</th><th>Şifre</th><th>Ad Soyad</th><th>Sipariş Sayısı</th></tr>";

foreach ($customers as $customer) {
    echo "<tr>";
    echo "<td>" . $customer['UserID'] . "</td>";
    echo "<td><strong>" . htmlspecialchars($customer['Email']) . "</strong></td>";
    echo "<td><strong>" . htmlspecialchars($customer['Password']) . "</strong></td>";
    echo "<td>" . htmlspecialchars($customer['FullName']) . "</td>";
    echo "<td>" . $customer['OrderCount'] . "</td>";
    echo "</tr>";
}

echo "</table>";
echo "<p>Toplam: <strong>" . count($customers) . "</strong> müşteri</p>";
echo "<hr>";
echo "<p style='color: green;'>✅ Bu bilgilerle müşteri paneline giriş yapabilirsiniz: <a href='../customer_login.php'>customer_login.php</a></p>";
?>
