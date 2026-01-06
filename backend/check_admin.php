<?php
require_once 'config.php';

try {
    echo "=== ADMIN KULLANICI KONTROLÜ ===\n\n";
    
    $stmt = $pdo->query("SELECT UserID, Email, FullName, UserType FROM Users WHERE UserType = 'Admin'");
    $admins = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (count($admins) > 0) {
        foreach ($admins as $admin) {
            echo "User ID: " . $admin['UserID'] . "\n";
            echo "Email: " . $admin['Email'] . "\n";
            echo "Ad: " . $admin['FullName'] . "\n";
            echo "Tip: " . $admin['UserType'] . "\n\n";
        }
    } else {
        echo "Admin kullanıcı bulunamadı!\n";
    }
    
} catch (PDOException $e) {
    echo "Hata: " . $e->getMessage() . "\n";
}
