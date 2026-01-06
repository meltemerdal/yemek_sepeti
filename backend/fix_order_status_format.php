<?php
require_once 'config.php';

echo "=== Eski formattaki durumları güncelliyorum ===\n\n";

// Eski formatları yeni formata çevir
$updates = [
    'Hazırlanıyor' => 'hazirlaniyor',
    'Onaylandı' => 'onaylandi',
    'Yola Çıktı' => 'yola_cikti',
    'Yolda' => 'yola_cikti',
    'Teslim Edildi' => 'teslim_edildi',
    'Teslim Edilemedi' => 'teslim_edilemedi'
];

$totalUpdated = 0;

foreach ($updates as $oldStatus => $newStatus) {
    $stmt = $pdo->prepare("UPDATE Orders SET Status = ? WHERE Status = ?");
    $stmt->execute([$newStatus, $oldStatus]);
    $count = $stmt->rowCount();
    
    if ($count > 0) {
        echo "'{$oldStatus}' -> '{$newStatus}': {$count} sipariş güncellendi\n";
        $totalUpdated += $count;
    }
}

echo "\nToplam {$totalUpdated} sipariş güncellendi!\n";
