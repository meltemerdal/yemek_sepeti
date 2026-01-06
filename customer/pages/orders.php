<?php
session_name('customer_session');
session_start();

require_once '../../backend/config.php';
require_once '../../backend/auth_helper.php';

requireAuth('Customer');

$pageTitle = 'Siparişlerim';
$activePage = 'orders';

// Siparişleri getir
try {
    $stmt = $pdo->prepare("
        SELECT o.OrderID, o.OrderDate, o.TotalAmount, o.DeliveryFee, o.Status, o.PaymentMethod,
               r.Name as RestaurantName, a.AddressText
        FROM Orders o
        JOIN Restaurants r ON o.RestaurantID = r.RestaurantID
        LEFT JOIN Addresses a ON o.AddressID = a.AddressID
        WHERE o.UserID = ?
        ORDER BY o.OrderDate DESC
    ");
    $stmt->execute([$_SESSION['user_id']]);
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = $e->getMessage();
}

include '../includes/header.php';
?>

<div class="page-header">
    <h1>Siparişlerim</h1>
    <p>Tüm sipariş geçmişinizi görüntüleyin</p>
</div>

<div class="card">
    <?php if (empty($orders)): ?>
        <div class="empty-state">
            <div class="icon">📦</div>
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
                        <th>Adres</th>
                        <th>Tutar</th>
                        <th>Teslimat</th>
                        <th>Toplam</th>
                        <th>Ödeme</th>
                        <th>Durum</th>
                    </tr>
                </thead>
                <tbody id="orders-tbody">
                    <?php foreach ($orders as $order): ?>
                        <tr data-order-id="<?= $order['OrderID'] ?>">
                            <td><strong>#<?= $order['OrderID'] ?></strong></td>
                            <td><?= htmlspecialchars($order['RestaurantName']) ?></td>
                            <td><?= date('d.m.Y H:i', strtotime($order['OrderDate'])) ?></td>
                            <td style="max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                <?= htmlspecialchars($order['AddressText'] ?? 'N/A') ?>
                            </td>
                            <td>₺<?= number_format($order['TotalAmount'], 2) ?></td>
                            <td>₺<?= number_format($order['DeliveryFee'], 2) ?></td>
                            <td><strong>₺<?= number_format($order['TotalAmount'] + $order['DeliveryFee'], 2) ?></strong></td>
                            <td><?= htmlspecialchars($order['PaymentMethod']) ?></td>
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
// Profesyonel Auto-Refresh - Tüm tabloyu yenile
function refreshAllOrders() {
    const userId = <?= $_SESSION['user_id'] ?>;
    
    fetch(`../../backend/orders.php?action=history&user_id=${userId}`)
        .then(response => response.json())
        .then(orders => {
            if (Array.isArray(orders) && orders.length > 0) {
                const tbody = document.getElementById('orders-tbody');
                if (!tbody) return;
                
                // Tüm tabloyu yeniden oluştur
                tbody.innerHTML = '';
                
                orders.forEach(order => {
                    const row = createOrderRow(order);
                    tbody.appendChild(row);
                });
                
                console.log('Siparişler güncellendi:', orders.length);
            }
        })
        .catch(error => {
            console.error('Sipariş yenileme hatası:', error);
        });
}

function createOrderRow(order) {
    const tr = document.createElement('tr');
    tr.setAttribute('data-order-id', order.OrderID);
    
    // Durum badge belirleme
    let statusText = '';
    let badgeClass = 'badge-info';
    switch(order.Status) {
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
            statusText = order.Status;
    }
    
    // Tarih formatla
    const orderDate = new Date(order.OrderDate);
    const formattedDate = orderDate.toLocaleDateString('tr-TR', {day: '2-digit', month: '2-digit', year: 'numeric'}) + ' ' + 
                          orderDate.toLocaleTimeString('tr-TR', {hour: '2-digit', minute: '2-digit'});
    
    tr.innerHTML = `
        <td><strong>#${order.OrderID}</strong></td>
        <td>${escapeHtml(order.RestaurantName)}</td>
        <td>${formattedDate}</td>
        <td style="max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
            ${escapeHtml(order.AddressText || 'N/A')}
        </td>
        <td>₺${parseFloat(order.TotalAmount).toFixed(2)}</td>
        <td>₺${parseFloat(order.DeliveryFee).toFixed(2)}</td>
        <td><strong>₺${(parseFloat(order.TotalAmount) + parseFloat(order.DeliveryFee)).toFixed(2)}</strong></td>
        <td>${escapeHtml(order.PaymentMethod)}</td>
        <td>
            <span class="badge ${badgeClass}">${statusText}</span>
        </td>
    `;
    
    return tr;
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// Her 5 saniyede bir otomatik yenileme (Profesyonel Çözüm)
setInterval(refreshAllOrders, 5000);
</script>

<?php include '../includes/footer.php'; ?>
