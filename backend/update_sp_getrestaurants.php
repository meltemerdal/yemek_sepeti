<?php
require_once 'config.php';

try {
    // SQL dosyasını oku ve çalıştır
    $sql = file_get_contents(__DIR__ . '/database/11_update_sp_getrestaurants.sql');
    
    // GO ile ayrılmış komutları böl
    $commands = array_filter(array_map('trim', explode('GO', $sql)));
    
    foreach ($commands as $command) {
        if (!empty($command)) {
            $pdo->exec($command);
        }
    }
    
    echo "✓ SP_GetRestaurants prosedürü güncellendi.\n";
    echo "✓ Artık sadece onaylı (approved) restoranlar listeleniyor.\n";
    
} catch (PDOException $e) {
    echo "Hata: " . $e->getMessage() . "\n";
}
