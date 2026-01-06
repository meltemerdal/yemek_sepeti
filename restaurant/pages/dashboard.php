<?php
session_name('restaurant_session');
session_start();
if (!isset($_SESSION['restaurant_id'])) {
    header('Location: /restaurant_login.php');
    exit();
}
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

$pageTitle = 'Dashboard';
$activePage = 'dashboard';

// İstatistikler
$stmt = $pdo->prepare("SELECT COUNT(*) as total FROM Orders WHERE RestaurantID = ?");
$stmt->execute([$restaurantId]);
$totalOrders = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

$stmt = $pdo->prepare("SELECT ISNULL(SUM(TotalAmount), 0) as total FROM Orders WHERE RestaurantID = ?");
$stmt->execute([$restaurantId]);
$totalRevenue = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

$stmt = $pdo->prepare("SELECT COUNT(*) as total FROM MenuItems WHERE RestaurantID = ?");
$stmt->execute([$restaurantId]);
$totalMenuItems = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

$stmt = $pdo->prepare("SELECT COUNT(*) as total FROM Orders WHERE RestaurantID = ? AND Status = 'Hazırlanıyor'");
$stmt->execute([$restaurantId]);
$activeOrders = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

// Son siparişler
$stmt = $pdo->prepare("
    SELECT o.OrderID, o.OrderDate, o.TotalAmount, o.Status, u.FullName
    FROM Orders o
    JOIN Users u ON o.UserID = u.UserID
    WHERE o.RestaurantID = ?
    ORDER BY o.OrderDate DESC
    OFFSET 0 ROWS FETCH NEXT 10 ROWS ONLY
");
$stmt->execute([$restaurantId]);
$recentOrders = $stmt->fetchAll(PDO::FETCH_ASSOC);

include '../includes/header.php';
?>

<div class="page-header">
    <h1>Dashboard</h1>
    <p>Restoranınızın genel görünümü</p>
</div>

<!-- Adres filtreleme inputu ve temizle butonu (GENEL RESTORAN FİLTRESİ İÇİN) -->


<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon pink">📦</div>
        <div class="stat-info">
            <h4>Toplam Sipariş</h4>
            <p><?= $totalOrders ?></p>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon blue">💰</div>
        <div class="stat-info">
            <h4>Toplam Gelir</h4>
            <p>₺<?= number_format($totalRevenue, 2) ?></p>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon green">🍽️</div>
        <div class="stat-info">
            <h4>Menü Ürünleri</h4>
            <p><?= $totalMenuItems ?></p>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon orange">⏳</div>
        <div class="stat-info">
            <h4>Aktif Sipariş</h4>
            <p><?= $activeOrders ?></p>
        </div>
    </div>
</div>

<!-- ÖRNEK RESTORAN LİSTESİ (Filtreleme için) -->


<div class="card">
    <div class="card-header">
        <h3>Son Siparişler</h3>
        <a href="/restaurant/pages/orders.php" class="btn btn-primary btn-sm">Tümünü Gör</a>
    </div>
    
    <?php if (empty($recentOrders)): ?>
        <div class="empty-state">
            <div class="icon">📦</div>
            <h3>Henüz sipariş yok</h3>
            <p>Siparişler gelmeye başladığında burada görünecek</p>
        </div>
    <?php else: ?>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Sipariş No</th>
                        <th>Müşteri</th>
                        <th>Tarih</th>
                        <th>Tutar</th>
                        <th>Durum</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recentOrders as $order): ?>
                        <tr>
                            <td>#<?= $order['OrderID'] ?></td>
                            <td><?= htmlspecialchars($order['FullName']) ?></td>
                            <td><?= date('d.m.Y H:i', strtotime($order['OrderDate'])) ?></td>
                            <td>₺<?= number_format($order['TotalAmount'], 2) ?></td>
                            <td>
                                <?php
                                $badgeClass = 'badge-info';
                                if ($order['Status'] === 'Teslim Edildi') $badgeClass = 'badge-success';
                                elseif ($order['Status'] === 'Hazırlanıyor') $badgeClass = 'badge-warning';
                                ?>
                                <span class="badge <?= $badgeClass ?>"><?= htmlspecialchars($order['Status']) ?></span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<script>

// GENEL RESTORAN FİLTRESİ İÇİN: Adres filtreleme ve temizleme fonksiyonu
// Not: Aşağıdaki kodu, restoranlarınızı gösterdiğiniz tablo veya listeye göre özelleştirin.
// Örneğin, her restoranı bir <div class="restaurant-item">...</div> ile gösteriyorsanız, querySelectorAll('.restaurant-item') kullanın.

document.getElementById('addressFilter').addEventListener('input', function() {
    var filter = this.value.toLowerCase();
    // ÖRNEK: .restaurant-item class'ına sahip elemanlarda filtre uygula
    var items = document.querySelectorAll('.restaurant-item');
    items.forEach(function(item) {
        var text = item.innerText.toLowerCase();
        if (text.includes(filter)) {
            item.style.display = '';
        } else {
            item.style.display = 'none';
        }
    });
});

document.getElementById('clearAddressFilter').addEventListener('click', function() {
    document.getElementById('addressFilter').value = '';
    var items = document.querySelectorAll('.restaurant-item');
    items.forEach(function(item) {
        item.style.display = '';
    });
});

// Otomatik yenileme - her 3 saniyede bir
setInterval(function() {
    location.reload();
}, 3000);
</script>

<?php include '../includes/footer.php'; ?>
