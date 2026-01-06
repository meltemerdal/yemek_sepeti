<?php
session_name('customer_session');
session_start();

require_once '../../backend/config.php';
require_once '../../backend/auth_helper.php';

requireAuth('Customer');

$pageTitle = 'Ana Sayfa';
$activePage = 'dashboard';

// İstatistikleri al
try {
    // Toplam sipariş sayısı
    $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM Orders WHERE UserID = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $totalOrders = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    
    // Toplam harcama
    $stmt = $pdo->prepare("SELECT ISNULL(SUM(TotalAmount + DeliveryFee), 0) as total FROM Orders WHERE UserID = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $totalSpent = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    
    // Son siparişler
    $stmt = $pdo->prepare("
        SELECT o.OrderID, o.OrderDate, o.TotalAmount, o.Status, r.Name as RestaurantName
        FROM Orders o
        JOIN Restaurants r ON o.RestaurantID = r.RestaurantID
        WHERE o.UserID = ?
        ORDER BY o.OrderDate DESC
        OFFSET 0 ROWS FETCH NEXT 5 ROWS ONLY
    ");
    $stmt->execute([$_SESSION['user_id']]);
    $recentOrders = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
} catch (PDOException $e) {
    $error = $e->getMessage();
}

include '../includes/header.php';
?>

<div class="page-header">
    <h1>Hoş Geldiniz, <?= htmlspecialchars($_SESSION['full_name']) ?>!</h1>
    <p>Müşteri panelinize hoş geldiniz</p>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon purple">📦</div>
        <div class="stat-info">
            <h4>Toplam Sipariş</h4>
            <p><?= $totalOrders ?></p>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon orange">💰</div>
        <div class="stat-info">
            <h4>Toplam Harcama</h4>
            <p>₺<?= number_format($totalSpent, 2) ?></p>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon green">📍</div>
        <div class="stat-info">
            <h4>Kayıtlı Adres</h4>
            <p><?php
                $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM Addresses WHERE UserID = ?");
                $stmt->execute([$_SESSION['user_id']]);
                echo $stmt->fetch(PDO::FETCH_ASSOC)['total'];
            ?></p>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3>Son Siparişlerim</h3>
        <a href="/customer/pages/orders.php" class="btn btn-primary btn-sm">Tümünü Gör</a>
    </div>
    
    <?php if (empty($recentOrders)): ?>
        <div class="empty-state">
            <div class="icon">🍽️</div>
            <h3>Henüz sipariş vermediniz</h3>
            <p>Lezzetli yemekleri keşfetmek için restoran listesine göz atın!</p>
            <a href="/frontend/index.html" class="btn btn-primary">Restoran Ara</a>
        </div>
    <?php else: ?>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Sipariş No</th>
                        <th>Restoran</th>
                        <th>Tarih</th>
                        <th>Tutar</th>
                        <th>Durum</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recentOrders as $order): ?>
                        <tr data-order-id="<?= $order['OrderID'] ?>">
                            <td>#<?= $order['OrderID'] ?></td>
                            <td><?= htmlspecialchars($order['RestaurantName']) ?></td>
                            <td><?= date('d.m.Y H:i', strtotime($order['OrderDate'])) ?></td>
                            <td>₺<?= number_format($order['TotalAmount'], 2) ?></td>
                            <td>
                                <?php
                                $statusText = '';
                                $badgeClass = 'badge-info';
                                switch($order['Status']) {
                                    case 'onaylandi':
                                        $statusText = 'Onaylandı';
                                        $badgeClass = 'badge-secondary';
                                        break;
                                    case 'hazirlaniyor':
                                        $statusText = 'Hazırlanıyor';
                                        $badgeClass = 'badge-warning';
                                        break;
                                    case 'yola_cikti':
                                        $statusText = 'Yolda';
                                        $badgeClass = 'badge-info';
                                        break;
                                    case 'teslim_edildi':
                                        $statusText = 'Teslim Edildi';
                                        $badgeClass = 'badge-success';
                                        break;
                                    case 'teslim_edilemedi':
                                        $statusText = 'Teslim Edilemedi';
                                        $badgeClass = 'badge-danger';
                                        break;
                                    default:
                                        $statusText = $order['Status'];
                                        break;
                                }
                                ?>
                                <span class="badge <?= $badgeClass ?>"><?= $statusText ?></span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<script>
// Sipariş durumlarını otomatik güncelle
function refreshOrderStatuses() {
    console.log('[Dashboard] Sipariş durumları kontrol ediliyor...');
    fetch('../../backend/get_user_orders.php', {
        method: 'GET',
        credentials: 'same-origin'
    })
    .then(response => response.json())
    .then(data => {
        console.log('[Dashboard] Gelen veri:', data);
        if (data.success && data.orders) {
            console.log('[Dashboard] Toplam sipariş:', data.orders.length);
            data.orders.forEach(order => {
                console.log('[Dashboard] Sipariş #' + order.OrderID + ' durumu:', order.Status);
                updateOrderStatusBadge(order.OrderID, order.Status);
            });
        } else {
            console.log('[Dashboard] Veri alınamadı veya sipariş yok');
        }
    })
    .catch(error => {
        console.error('[Dashboard] Sipariş güncelleme hatası:', error);
    });
}

function updateOrderStatusBadge(orderId, status) {
    console.log('[Dashboard] Badge güncelleniyor: Sipariş #' + orderId + ' -> ' + status);
    // Tablodaki ilgili satırı bul (data-order-id ile)
    const row = document.querySelector('tr[data-order-id="' + orderId + '"]');
    
    if (row) {
        console.log('[Dashboard] Satır bulundu: Sipariş #' + orderId);
        
        const badge = row.querySelector('.badge');
        if (badge) {
            console.log('[Dashboard] Badge bulundu, eski değer: ' + badge.textContent);
            
            // Tüm badge sınıflarını kaldır
            badge.className = 'badge';
            
            // Yeni durum için badge sınıfı ve metin ekle
            let statusText = '';
            let badgeClass = 'badge-info';
                    
                    switch(status) {
                        case 'onaylandi':
                            statusText = 'Onaylandı';
                            badgeClass = 'badge-secondary';
                            break;
                        case 'hazirlaniyor':
                            statusText = 'Hazırlanıyor';
                            badgeClass = 'badge-warning';
                            break;
                        case 'yola_cikti':
                            statusText = 'Yolda';
                            badgeClass = 'badge-info';
                            break;
                        case 'teslim_edildi':
                            statusText = 'Teslim Edildi';
                            badgeClass = 'badge-success';
                            break;
                        case 'teslim_edilemedi':
                            statusText = 'Teslim Edilemedi';
                            badgeClass = 'badge-danger';
                            break;
                        default:
                            statusText = status;
                            break;
                    }
                    
                    badge.className = `badge ${badgeClass}`;
                    badge.textContent = statusText;
                    console.log('[Dashboard] Badge güncellendi: ' + statusText + ' (' + badgeClass + ')');
                } else {
                    console.log('[Dashboard] Badge bulunamadı!');
                }
    } else {
        console.log('[Dashboard] Sipariş #' + orderId + ' için satır bulunamadı');
    }
}

// Sayfa yüklendiğinde hemen kontrol et
refreshOrderStatuses();

// Her 3 saniyede bir sipariş durumlarını kontrol et
setInterval(refreshOrderStatuses, 3000);
</script>

<?php include '../includes/footer.php'; ?>
