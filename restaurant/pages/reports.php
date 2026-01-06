<?php
session_name('restaurant_session');
session_start();

require_once '../../backend/config.php';
require_once '../../backend/auth_helper.php';

requireAuth('RestaurantOwner');

// 1️⃣ Restoranın RestaurantID'sini al
$stmt = $pdo->prepare("
    SELECT RestaurantID 
    FROM Restaurants 
    WHERE OwnerUserID = ?
");
$stmt->execute([$_SESSION['restaurant_id']]);
$restaurant = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$restaurant) {
    echo 'Restoran bulunamadı';
    exit;
}

$restaurantId = $restaurant['RestaurantID'];

$pageTitle = 'Raporlar';
$activePage = 'reports';

$restaurantId = $restaurant['RestaurantID'];

// Günlük rapor
$stmt = $pdo->prepare("
    SELECT COUNT(*) as OrderCount, ISNULL(SUM(TotalAmount), 0) as Revenue
    FROM Orders
    WHERE RestaurantID = ? AND CAST(OrderDate AS DATE) = CAST(GETDATE() AS DATE)
");
$stmt->execute([$restaurantId]);
$dailyReport = $stmt->fetch(PDO::FETCH_ASSOC);

// Aylık rapor
$stmt = $pdo->prepare("
    SELECT COUNT(*) as OrderCount, ISNULL(SUM(TotalAmount), 0) as Revenue
    FROM Orders
    WHERE RestaurantID = ? AND MONTH(OrderDate) = MONTH(GETDATE()) AND YEAR(OrderDate) = YEAR(GETDATE())
");
$stmt->execute([$restaurantId]);
$monthlyReport = $stmt->fetch(PDO::FETCH_ASSOC);

// En çok satılan ürünler
$stmt = $pdo->prepare("
    SELECT TOP 10 mi.Name, SUM(od.Quantity) as TotalSold, SUM(od.Subtotal) as TotalRevenue
    FROM OrderDetails od
    JOIN MenuItems mi ON od.MenuItemID = mi.MenuItemID
    JOIN Orders o ON od.OrderID = o.OrderID
    WHERE o.RestaurantID = ?
    GROUP BY mi.Name
    ORDER BY TotalSold DESC
");
$stmt->execute([$restaurantId]);
$topProducts = $stmt->fetchAll(PDO::FETCH_ASSOC);

include '../includes/header.php';
?>

<div class="page-header">
    <h1>Raporlar</h1>
    <p>Satış raporlarınızı inceleyin</p>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon orange">📅</div>
        <div class="stat-info">
            <h4>Bugünkü Sipariş</h4>
            <p><?= $dailyReport['OrderCount'] ?></p>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon pink">💰</div>
        <div class="stat-info">
            <h4>Bugünkü Gelir</h4>
            <p>₺<?= number_format($dailyReport['Revenue'], 2) ?></p>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon blue">📆</div>
        <div class="stat-info">
            <h4>Aylık Sipariş</h4>
            <p><?= $monthlyReport['OrderCount'] ?></p>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon green">💵</div>
        <div class="stat-info">
            <h4>Aylık Gelir</h4>
            <p>₺<?= number_format($monthlyReport['Revenue'], 2) ?></p>
        </div>
    </div>
</div>

<div class="card">
    <h3 style="margin-bottom: 24px;">En Çok Satılan Ürünler</h3>
    
    <?php if (empty($topProducts)): ?>
        <div class="empty-state">
            <div class="icon">📊</div>
            <h3>Henüz veri yok</h3>
            <p>Siparişler gelmeye başladığında istatistikler burada görünecek</p>
        </div>
    <?php else: ?>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Ürün Adı</th>
                        <th>Satış Adedi</th>
                        <th>Toplam Gelir</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($topProducts as $product): ?>
                        <tr>
                            <td><strong><?= htmlspecialchars($product['Name']) ?></strong></td>
                            <td><?= $product['TotalSold'] ?> adet</td>
                            <td>₺<?= number_format($product['TotalRevenue'], 2) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>
