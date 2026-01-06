<?php
require_once 'config.php';

try {
    // SQL dosyasını oku ve çalıştır
    $sql = file_get_contents(__DIR__ . '/database/10_add_restaurant_status.sql');
    
    // GO ile ayrılmış komutları böl
    $commands = array_filter(array_map('trim', explode('GO', $sql)));
    
    foreach ($commands as $command) {
        if (!empty($command)) {
            $pdo->exec($command);
        }
    }
    
    echo "✓ Restaurants tablosuna Status sütunu eklendi.\n";
    echo "✓ Mevcut restoranlar 'approved' olarak işaretlendi.\n";
    
} catch (PDOException $e) {
    echo "Hata: " . $e->getMessage() . "\n";
}
