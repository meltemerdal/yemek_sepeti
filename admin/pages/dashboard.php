<?php
session_name('admin_session');
session_start();
require_once '../../backend/config.php';
require_once '../../backend/auth_helper.php';

requireAuth('Admin');

$pageTitle = 'Dashboard';
$activePage = 'dashboard';

// İstatistikler
try {
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM Users WHERE UserType = 'Customer'");
    $totalCustomers = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM Restaurants WHERE IsActive = 1");
    $totalRestaurants = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM Orders");
    $totalOrders = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    
    $stmt = $pdo->query("SELECT ISNULL(SUM(TotalAmount + DeliveryFee), 0) as total FROM Orders");
    $totalRevenue = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    
    // En çok sipariş alan restoranlar
    $stmt = $pdo->query("
        SELECT TOP 10 r.Name, COUNT(o.OrderID) as OrderCount, SUM(o.TotalAmount) as Revenue
        FROM Restaurants r
        LEFT JOIN Orders o ON r.RestaurantID = o.RestaurantID
        GROUP BY r.RestaurantID, r.Name
        ORDER BY OrderCount DESC
    ");
    $topRestaurants = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Son siparişler
    $stmt = $pdo->query("
        SELECT TOP 10 o.OrderID, o.OrderDate, o.TotalAmount, o.Status,
               r.Name as RestaurantName, u.FullName as CustomerName
        FROM Orders o
        JOIN Restaurants r ON o.RestaurantID = r.RestaurantID
        JOIN Users u ON o.UserID = u.UserID
        ORDER BY o.OrderDate DESC
    ");
    $recentOrders = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
} catch (PDOException $e) {
    $error = $e->getMessage();
}

include '../includes/header.php';
?>

<div class="page-header">
    <h1>Admin Dashboard</h1>
    <p>Sistem genel görünümü</p>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon dark">👥</div>
        <div class="stat-info">
            <h4>Toplam Müşteri</h4>
            <p><?= $totalCustomers ?></p>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon blue">🏪</div>
        <div class="stat-info">
            <h4>Aktif Restoran</h4>
            <p><?= $totalRestaurants ?></p>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon green">📦</div>
        <div class="stat-info">
            <h4>Toplam Sipariş</h4>
            <p><?= $totalOrders ?></p>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon orange">💰</div>
        <div class="stat-info">
            <h4>Toplam Gelir</h4>
            <p>₺<?= number_format($totalRevenue, 2) ?></p>
        </div>
    </div>
</div>

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
    <div class="card">
        <h3 style="margin-bottom: 20px;">En Çok Sipariş Alan Restoranlar</h3>
        <?php if (empty($topRestaurants)): ?>
            <p style="color: #666;">Henüz veri yok</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Restoran</th>
                        <th>Sipariş</th>
                        <th>Gelir</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($topRestaurants as $rest): ?>
                        <tr>
                            <td><?= htmlspecialchars($rest['Name']) ?></td>
                            <td><?= $rest['OrderCount'] ?></td>
                            <td>₺<?= number_format($rest['Revenue'] ?? 0, 2) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
    
    <div class="card">
        <h3 style="margin-bottom: 20px;">Son Siparişler</h3>
        <?php if (empty($recentOrders)): ?>
            <p style="color: #666;">Henüz sipariş yok</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Restoran</th>
                        <th>Müşteri</th>
                        <th>Tutar</th>
                        <th>Durum</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recentOrders as $order): ?>
                        <tr>
                            <td>#<?= $order['OrderID'] ?></td>
                            <td><?= htmlspecialchars($order['RestaurantName']) ?></td>
                            <td><?= htmlspecialchars($order['CustomerName']) ?></td>
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
        <?php endif; ?>
    </div>
</div>

<script>
// Otomatik yenileme - her 3 saniyede bir
setInterval(function() {
    location.reload();
}, 3000);
</script>

<?php include '../includes/footer.php'; ?>
