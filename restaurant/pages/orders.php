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

$pageTitle = 'Siparişler';
$activePage = 'orders';

$success = '';
$error = '';

// Geçerli statüler
$validStatuses = [
    'onaylandi',
    'hazirlaniyor',
    'yola_cikti',
    'teslim_edildi',
    'teslim_edilemedi'
];

// Sipariş durumu güncelleme
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $orderId = $_POST['order_id'] ?? 0;
    $status = $_POST['status'] ?? '';
    
    if (!in_array($status, $validStatuses)) {
        $error = 'Geçersiz durum';
    } else {
        try {
            // 1. Yetki kontrolü - Bu sipariş bu restorana ait mi?
            $check = $pdo->prepare("
                SELECT OrderID
                FROM Orders
                WHERE OrderID = ? AND RestaurantID = ?
            ");
            $check->execute([$orderId, $restaurantId]);
            
            if (!$check->fetch()) {
                $error = 'Yetkisiz işlem';
            } else {
                // 2. Güncelleme (TEK NOKTA)
                $stmt = $pdo->prepare("
                    UPDATE Orders
                    SET Status = ?
                    WHERE OrderID = ?
                ");
                $stmt->execute([$status, $orderId]);
                $success = 'Sipariş durumu güncellendi!';
                
                // Sayfayı yenile
                header("Location: orders.php");
                exit;
            }
        } catch (PDOException $e) {
            $error = 'Güncelleme hatası: ' . $e->getMessage();
        }
    }
}

// Siparişleri getir
try {
    $stmt = $pdo->prepare("
        SELECT 
            o.OrderID,
            o.OrderDate,
            o.TotalAmount,
            o.DeliveryFee,
            o.Status,
            o.PaymentMethod,
            o.Note,
            u.FullName AS CustomerName,
            u.Phone,
            a.AddressText
        FROM Orders o
        JOIN Users u ON u.UserID = o.UserID
        LEFT JOIN Addresses a ON o.AddressID = a.AddressID
        WHERE o.RestaurantID = ?
        ORDER BY o.OrderDate DESC
    ");
    $stmt->execute([$restaurantId]);
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = $e->getMessage();
}

include '../includes/header.php';
?>

<div class="page-header">
    <h1>Siparişler</h1>
    <p>Gelen siparişleri yönetin</p>
</div>

<?php if ($success): ?>
    <div class="card" style="background: #d4edda; color: #155724; margin-bottom: 20px;">
        <?= htmlspecialchars($success) ?>
    </div>
<?php endif; ?>

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
                        <th>Müşteri</th>
                        <th>Telefon</th>
                        <th>Adres</th>
                        <th>Tarih</th>
                        <th>Tutar</th>
                        <th>Not</th>
                        <th>Durum</th>
                        <th>İşlem</th>
                    </tr>
                </thead>
                <tbody id="orders-tbody">
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td><strong>#<?= $order['OrderID'] ?></strong></td>
                            <td><?= htmlspecialchars($order['CustomerName']) ?></td>
                            <td><?= htmlspecialchars($order['Phone']) ?></td>
                            <td style="max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                <?= htmlspecialchars($order['AddressText'] ?? 'N/A') ?>
                            </td>
                            <td><?= date('d.m.Y H:i', strtotime($order['OrderDate'])) ?></td>
                            <td>₺<?= number_format($order['TotalAmount'] + $order['DeliveryFee'], 2) ?></td>
                            <td><?= htmlspecialchars($order['Note'] ?? '-') ?></td>
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
            // Sayfayı yenile - Admin paneli güncel durumu görsün
            setTimeout(() => {
                location.reload();
            }, 500);
        } else {
            alert('Güncelleme başarısız: ' + (data.reason || 'Bilinmeyen hata'));
            // Eski değere geri dön
            selectElement.value = oldValue;
            selectElement.disabled = false;
        }
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
    
    // Her 3 saniyede bir siparişleri güncelle
    setInterval(refreshOrders, 3000);
});

// Mevcut sipariş ID'lerini takip et
let knownOrderIds = new Set([<?php echo implode(',', array_column($orders, 'OrderID')); ?>]);
let lastOrderCount = <?= count($orders) ?>;

function refreshOrders() {
    const restaurantId = <?= $restaurantId ?>;
    
    fetch(`../../backend/orders.php?action=list_restaurant&restaurant_id=${restaurantId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success && data.orders) {
                // Yeni sipariş kontrolü
                if (data.orders.length > lastOrderCount) {
                    // Yeni siparişleri bul
                    const newOrders = data.orders.filter(order => !knownOrderIds.has(order.OrderID));
                    
                    if (newOrders.length > 0) {
                        // Yeni siparişleri tabloya ekle
                        newOrders.forEach(order => {
                            addNewOrderToTable(order);
                            knownOrderIds.add(order.OrderID);
                        });
                        
                        // Bildirim göster
                        showNotification(`${newOrders.length} yeni sipariş geldi! 🔔`, 'success');
                        
                        // Ses çal (isteğe bağlı)
                        playNotificationSound();
                    }
                    
                    lastOrderCount = data.orders.length;
                }
                
                // Mevcut siparişlerin durumlarını güncelle
                data.orders.forEach(order => {
                    const select = document.querySelector(`.status-select[data-order-id="${order.OrderID}"]`);
                    if (select && select.value !== order.Status) {
                        select.value = order.Status;
                        select.setAttribute('data-current-value', order.Status);
                        updateStatusBadge(select, order.Status);
                    }
                });
            }
        })
        .catch(error => console.error('Sipariş yenileme hatası:', error));
}

function addNewOrderToTable(order) {
    const tbody = document.getElementById('orders-tbody');
    if (!tbody) return;
    
    // Yeni satır oluştur
    const row = document.createElement('tr');
    row.style.animation = 'fadeIn 0.5s';
    row.style.backgroundColor = '#fffacd';
    
    // Durum badge'i
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
            break;
    }
    
    // Tarih formatla
    const orderDate = new Date(order.OrderDate);
    const formattedDate = orderDate.toLocaleString('tr-TR', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
    
    row.innerHTML = `
        <td><strong>#${order.OrderID}</strong></td>
        <td>${escapeHtml(order.FullName)}</td>
        <td>${escapeHtml(order.Phone)}</td>
        <td style="max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
            ${escapeHtml(order.AddressText || 'N/A')}
        </td>
        <td>${formattedDate}</td>
        <td>₺${(parseFloat(order.TotalAmount) + parseFloat(order.DeliveryFee)).toFixed(2)}</td>
        <td>${escapeHtml(order.Note || '-')}</td>
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
    
    // Tablonun başına ekle
    tbody.insertBefore(row, tbody.firstChild);
    
    // 3 saniye sonra arka plan rengini normal yap
    setTimeout(() => {
        row.style.backgroundColor = '';
    }, 3000);
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function playNotificationSound() {
    // Basit bir beep sesi için Audio API kullan
    try {
        const audioContext = new (window.AudioContext || window.webkitAudioContext)();
        const oscillator = audioContext.createOscillator();
        const gainNode = audioContext.createGain();
        
        oscillator.connect(gainNode);
        gainNode.connect(audioContext.destination);
        
        oscillator.frequency.value = 800;
        oscillator.type = 'sine';
        
        gainNode.gain.value = 0.3;
        
        oscillator.start(audioContext.currentTime);
        oscillator.stop(audioContext.currentTime + 0.2);
    } catch (e) {
        console.log('Ses çalınamadı:', e);
    }
}
</script>

<?php include '../includes/footer.php'; ?>
