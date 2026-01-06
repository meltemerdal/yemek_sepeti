<?php
require_once 'config.php';

try {
    $categories = [
        'Gözlemeler',
        'Sandviçler',
        'Börekler',
        'Fırın Ürünleri',
        'Salatalar',
        'İçecekler'
    ];
    
    $stmt = $pdo->prepare("INSERT INTO Categories (Name) VALUES (?)");
    
    $added = 0;
    foreach ($categories as $category) {
        // Önce var mı kontrol et
        $check = $pdo->prepare("SELECT COUNT(*) FROM Categories WHERE Name = ?");
        $check->execute([$category]);
        
        if ($check->fetchColumn() == 0) {
            $stmt->execute([$category]);
            echo "✅ '{$category}' eklendi\n";
            $added++;
        } else {
            echo "⚠️  '{$category}' zaten mevcut\n";
        }
    }
    
    echo "\n📊 Toplam {$added} kategori eklendi\n\n";
    
    // Tüm kategorileri göster
    echo "=== TÜM KATEGORİLER ===\n";
    $all = $pdo->query("SELECT CategoryID, Name FROM Categories ORDER BY CategoryID");
    while ($row = $all->fetch(PDO::FETCH_ASSOC)) {
        echo "ID: {$row['CategoryID']} - {$row['Name']}\n";
    }
    
} catch (PDOException $e) {
    echo "❌ Hata: " . $e->getMessage();
}
?>
