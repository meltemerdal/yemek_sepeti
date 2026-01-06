<?php
require_once 'config.php';

header('Content-Type: text/html; charset=UTF-8');

try {
    // Mantı ürünlerini ekle
    $stmt = $pdo->prepare("
        INSERT INTO MenuItems (RestaurantID, Name, Description, Price, Category, ImageURL, IsAvailable)
        VALUES 
        (56, :name1, :desc1, 300.00, :cat, '/frontend/images/manti.jpg', 1),
        (56, :name2, :desc2, 500.00, :cat, '/frontend/images/manti.jpg', 1)
    ");
    
    $stmt->execute([
        'name1' => 'Mantı',
        'desc1' => 'Küçük hamur parçalarının kaynar su ile pişirilip üzerine yoğurt ve sos ilave edilerek servis edildiği bir yemektir.',
        'name2' => 'Mantı (1 kg.) (Pişirilmemiş)',
        'desc2' => 'Pişirilmemiş, 1 kg.',
        'cat' => 'Mantılar'
    ]);
    
    echo "<h2 style='color: green;'>✓ Menü ürünleri başarıyla eklendi!</h2>";
    
    // Kontrol et
    $stmt = $pdo->prepare("SELECT Name, Price, Category, Description FROM MenuItems WHERE RestaurantID = 56");
    $stmt->execute();
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<h3>Eklenen Ürünler:</h3>";
    echo "<table border='1' style='border-collapse: collapse; font-family: Arial;'>";
    echo "<tr><th>Ad</th><th>Fiyat</th><th>Kategori</th><th>Açıklama</th></tr>";
    
    foreach ($items as $item) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($item['Name']) . "</td>";
        echo "<td>" . $item['Price'] . " ₺</td>";
        echo "<td>" . htmlspecialchars($item['Category']) . "</td>";
        echo "<td>" . htmlspecialchars($item['Description']) . "</td>";
        echo "</tr>";
    }
    
    echo "</table>";
    
} catch (Exception $e) {
    echo "<h2 style='color: red;'>Hata: " . $e->getMessage() . "</h2>";
}
?>
