<?php
session_name('admin_session');
session_start();
require_once '../../backend/config.php';
require_once '../../backend/auth_helper.php';

requireAuth('Admin');

$pageTitle = 'Tüm Siparişler';
$activePage = 'orders';

// Tüm siparişleri getir
try {
    $stmt = $pdo->query("
        SELECT o.OrderID, o.OrderDate, o.TotalAmount, o.DeliveryFee, o.Status, o.PaymentMethod,
               r.Name as RestaurantName, u.FullName as CustomerName, u.Phone as CustomerPhone
        FROM Orders o
        JOIN Restaurants r ON o.RestaurantID = r.RestaurantID
        JOIN Users u ON o.UserID = u.UserID
        ORDER BY o.OrderDate DESC
    ");
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = $e->getMessage();
}

include '../includes/header.php';
?>

<div class="page-header">
    <h1>Tüm Siparişler</h1>
    <p>Sistemdeki tüm siparişleri görüntüleyin</p>
    <button onclick="manualRefresh()" style="padding: 10px 20px; background: #28a745; color: white; border: none; border-radius: 5px; cursor: pointer;">🔄 Yeni Siparişleri Kontrol Et</button>
</div>

<div class="card">
    <?php if (empty($orders)): ?>
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
                        <th>Restoran</th>
                        <th>Müşteri</th>
                        <th>Telefon</th>
                        <th>Tarih</th>
                        <th>Tutar</th>
                        <th>Teslimat</th>
                        <th>Toplam</th>
                        <th>Ödeme</th>
                        <th>Durum</th>
                    </tr>
                </thead>
                <tbody id="orders-tbody">
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td><strong>#<?= $order['OrderID'] ?></strong></td>
                            <td><?= htmlspecialchars($order['RestaurantName']) ?></td>
                            <td><?= htmlspecialchars($order['CustomerName']) ?></td>
                            <td><?= htmlspecialchars($order['CustomerPhone']) ?></td>
                            <td><?= date('d.m.Y H:i', strtotime($order['OrderDate'])) ?></td>
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
                                <select class="status-select" data-order-id="<?= $order['OrderID'] ?>" onchange="updateOrderStatus(this)">
                                    <option value="onaylandi" <?= $order['Status'] === 'onaylandi' ? 'selected' : '' ?>>Onaylandı</option>
                                    <option value="hazirlaniyor" <?= $order['Status'] === 'hazirlaniyor' ? 'selected' : '' ?>>Hazırlanıyor</option>
                                    <option value="yola_cikti" <?= $order['Status'] === 'yola_cikti' ? 'selected' : '' ?>>Yola Çıktı</option>
                                    <option value="teslim_edildi" <?= $order['Status'] === 'teslim_edildi' ? 'selected' : '' ?>>Teslim Edildi</option>
                                    <option value="teslim_edilemedi" <?= $order['Status'] === 'teslim_edilemedi' ? 'selected' : '' ?>>Teslim Edilemedi</option>
                                </select>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<script>
function updateOrderStatus(selectElement) {
    const orderId = selectElement.dataset.orderId;
    const newStatus = selectElement.value;
    const oldValue = selectElement.getAttribute('data-current-value');
    
    selectElement.disabled = true;
    
    fetch('../../backend/update_order_status.php', {
        method: 'POST',
        credentials: 'same-origin',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `order_id=${orderId}&status=${newStatus}`
    })
    .then(response => response.json())
    .then(data => {
        if (data && data.success) {
            showNotification('Sipariş durumu güncellendi', 'success');
            // Yeni değeri kaydet
            selectElement.setAttribute('data-current-value', newStatus);
            // Badge'i güncelle
            updateStatusBadge(selectElement, newStatus);
        } else {
            alert('Güncelleme başarısız: ' + (data.reason || 'Bilinmeyen hata'));
            // Eski değere geri dön
            selectElement.value = oldValue;
        }
        selectElement.disabled = false;
    })
    .catch(error => {
        alert('Bir hata oluştu: ' + error);
        // Eski değere geri dön
        selectElement.value = oldValue;
        selectElement.disabled = false;
    });
}

function showNotification(message, type) {
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.textContent = message;
    notification.style.cssText = 'position:fixed;top:20px;right:20px;padding:15px 25px;background:#28a745;color:white;border-radius:5px;z-index:9999;box-shadow:0 2px 10px rgba(0,0,0,0.2);';
    document.body.appendChild(notification);
    setTimeout(() => notification.remove(), 3000);
}

function updateStatusBadge(selectElement, status) {
    // Badge'i bul (select'in öncesindeki span)
    const row = selectElement.closest('tr');
    const badge = row.querySelector('.badge');
    
    if (!badge) return;
    
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
}

// Sayfa yüklendiğinde tüm select'lere mevcut değeri kaydet
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.status-select').forEach(function(select) {
        select.setAttribute('data-current-value', select.value);
    });
});

function manualRefresh() {
    refreshAllOrders();
}

// Tüm siparişleri yenile (Profesyonel Çözüm)
function refreshAllOrders() {
    fetch('../../backend/orders.php?action=list_all')
        .then(response => response.json())
        .then(data => {
            if (data.success && data.orders) {
                const tbody = document.getElementById('orders-tbody');
                
                // Tüm tabloyu yeniden oluştur
                tbody.innerHTML = '';
                
                data.orders.forEach(order => {
                    const row = createOrderRow(order);
                    tbody.appendChild(row);
                });
                
                // Select'lere mevcut değerleri kaydet
                document.querySelectorAll('.status-select').forEach(function(select) {
                    select.setAttribute('data-current-value', select.value);
                });
                
                console.log('Siparişler güncellendi:', data.orders.length);
            }
        })
        .catch(error => {
            console.error('Sipariş yenileme hatası:', error);
        });
}

function createOrderRow(order) {
    const tr = document.createElement('tr');
    
    // Durum badge ve text belirleme
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
    
    const orderDate = new Date(order.OrderDate);
    const formattedDate = orderDate.toLocaleDateString('tr-TR', {day: '2-digit', month: '2-digit', year: 'numeric'}) + ' ' + 
                          orderDate.toLocaleTimeString('tr-TR', {hour: '2-digit', minute: '2-digit'});
    
    tr.innerHTML = `
        <td><strong>#${order.OrderID}</strong></td>
        <td>${order.RestaurantName}</td>
        <td>${order.CustomerName}</td>
        <td>${order.CustomerPhone}</td>
        <td>${formattedDate}</td>
        <td>₺${parseFloat(order.TotalAmount).toFixed(2)}</td>
        <td>₺${parseFloat(order.DeliveryFee).toFixed(2)}</td>
        <td><strong>₺${(parseFloat(order.TotalAmount) + parseFloat(order.DeliveryFee)).toFixed(2)}</strong></td>
        <td>${order.PaymentMethod}</td>
        <td>
            <span class="badge ${badgeClass}">${statusText}</span>
            <select class="status-select" data-order-id="${order.OrderID}" data-current-value="${order.Status}" onchange="updateOrderStatus(this)">
                <option value="onaylandi" ${order.Status === 'onaylandi' ? 'selected' : ''}>Onaylandı</option>
                <option value="hazirlaniyor" ${order.Status === 'hazirlaniyor' ? 'selected' : ''}>Hazırlanıyor</option>
                <option value="yola_cikti" ${order.Status === 'yola_cikti' ? 'selected' : ''}>Yola Çıktı</option>
                <option value="teslim_edildi" ${order.Status === 'teslim_edildi' ? 'selected' : ''}>Teslim Edildi</option>
                <option value="teslim_edilemedi" ${order.Status === 'teslim_edilemedi' ? 'selected' : ''}>Teslim Edilemedi</option>
            </select>
        </td>
    `;
    
    return tr;
}

// Otomatik sipariş yenileme - her 5 saniyede bir (Profesyonel Çözüm)
setInterval(function() {
    refreshAllOrders();
}, 5000);
</script>

<?php include '../includes/footer.php'; ?>
