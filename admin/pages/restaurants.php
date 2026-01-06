<?php
session_name('admin_session');
session_start();

require_once '../../backend/config.php';

if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'Admin') {
    header("Location: /admin_login.php");
    exit;
}
require_once '../../backend/auth_helper.php';

requireAuth('Admin');

$pageTitle = 'Restoran Yönetimi';
$activePage = 'restaurants';

$success = '';
$error = '';

// Restoran ekleme
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_restaurant'])) {
    $name = $_POST['name'] ?? '';
    $category = $_POST['category'] ?? '';
    $address = $_POST['address'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $ownerEmail = $_POST['owner_email'] ?? '';
    $minOrderAmount = $_POST['min_order_amount'] ?? 0;
    $deliveryTime = $_POST['delivery_time'] ?? 30;
    
    try {
        // Restoran sahibi var mı kontrol et
        $stmt = $pdo->prepare("SELECT UserID FROM Users WHERE Email = ? AND UserType = 'RestaurantOwner'");
        $stmt->execute([$ownerEmail]);
        $owner = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($owner) {
            $stmt = $pdo->prepare("INSERT INTO Restaurants (Name, Category, Address, Phone, MinOrderAmount, DeliveryTime, OwnerUserID, IsActive) VALUES (?, ?, ?, ?, ?, ?, ?, 1)");
            $stmt->execute([$name, $category, $address, $phone, $minOrderAmount, $deliveryTime, $owner['UserID']]);
            $success = 'Restoran eklendi!';
        } else {
            $error = 'Belirtilen e-posta ile restoran sahibi bulunamadı!';
        }
    } catch (PDOException $e) {
        $error = 'Ekleme hatası: ' . $e->getMessage();
    }
}

// Restoran durumu değiştirme
if (isset($_GET['toggle']) && isset($_GET['id'])) {
    try {
        $stmt = $pdo->prepare("UPDATE Restaurants SET IsActive = CASE WHEN IsActive = 1 THEN 0 ELSE 1 END WHERE RestaurantID = ?");
        $stmt->execute([$_GET['id']]);
        $success = 'Restoran durumu güncellendi!';
    } catch (PDOException $e) {
        $error = 'Hata: ' . $e->getMessage();
    }
}

// Başarı/hata mesajları (delete işleminden dönenler)
if (isset($_GET['success']) && $_GET['success'] === 'deleted') {
    $success = 'Restoran ve ilgili tüm veriler başarıyla silindi!';
}
if (isset($_GET['error'])) {
    $error = 'Silme hatası: ' . htmlspecialchars($_GET['error']);
}

// Restoranları getir
try {
    $stmt = $pdo->query("
        SELECT r.*, u.FullName as OwnerName, u.Email as OwnerEmail
        FROM Restaurants r
        LEFT JOIN Users u ON r.OwnerUserID = u.UserID
        ORDER BY r.CreatedDate DESC
    ");
    $restaurants = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = $e->getMessage();
}

include '../includes/header.php';
?>

<div class="page-header">
    <h1>Restoran Yönetimi</h1>
    <p>Restoranları ekleyin ve yönetin</p>
</div>

<?php if ($success): ?>
    <div class="card" style="background: #d4edda; color: #155724; margin-bottom: 20px;">
        <?= htmlspecialchars($success) ?>
    </div>
<?php endif; ?>

<?php if ($error): ?>
    <div class="card" style="background: #f8d7da; color: #721c24; margin-bottom: 20px;">
        <?= htmlspecialchars($error) ?>
    </div>
<?php endif; ?>

<div class="card">
    <h3 style="margin-bottom: 24px;">Yeni Restoran Ekle</h3>
    
    <form method="POST">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div class="form-group">
                <label>Restoran Adı</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label>Kategori</label>
                <input type="text" name="category" class="form-control" required>
            </div>
        </div>
        
        <div class="form-group">
            <label>Adres</label>
            <textarea name="address" class="form-control" rows="2" required></textarea>
        </div>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px;">
            <div class="form-group">
                <label>Telefon</label>
                <input type="tel" name="phone" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label>Min. Sipariş (₺)</label>
                <input type="number" step="0.01" name="min_order_amount" class="form-control" value="0" required>
            </div>
            
            <div class="form-group">
                <label>Teslimat Süresi (dk)</label>
                <input type="number" name="delivery_time" class="form-control" value="30" required>
            </div>
        </div>
        
        <div class="form-group">
            <label>Restoran Sahibi E-posta</label>
            <input type="email" name="owner_email" class="form-control" placeholder="Mevcut bir RestaurantOwner hesabının e-postası" required>
        </div>
        
        <button type="submit" name="add_restaurant" class="btn btn-primary">Restoran Ekle</button>
    </form>
</div>

<div class="card">
    <h3 style="margin-bottom: 24px;">Mevcut Restoranlar</h3>
    
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Restoran Adı</th>
                    <th>Kategori</th>
                    <th>Sahibi</th>
                    <th>Telefon</th>
                    <th>Min. Sipariş</th>
                    <th>Durum</th>
                    <th>İşlem</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($restaurants as $rest): ?>
                    <tr>
                        <td><?= $rest['RestaurantID'] ?></td>
                        <td><strong><?= htmlspecialchars($rest['Name']) ?></strong></td>
                        <td><?= htmlspecialchars($rest['Category']) ?></td>
                        <td><?= htmlspecialchars($rest['OwnerName'] ?? 'Atanmamış') ?></td>
                        <td><?= htmlspecialchars($rest['Phone']) ?></td>
                        <td>₺<?= number_format($rest['MinOrderAmount'], 2) ?></td>
                        <td>
                            <span class="badge <?= $rest['IsActive'] ? 'badge-success' : 'badge-dark' ?>">
                                <?= $rest['IsActive'] ? 'Aktif' : 'Pasif' ?>
                            </span>
                        </td>
                        <td>
                            <a href="?toggle=1&id=<?= $rest['RestaurantID'] ?>" class="btn btn-warning btn-sm">
                                <?= $rest['IsActive'] ? 'Pasif Yap' : 'Aktif Yap' ?>
                            </a>
                            <button onclick="deleteRestaurant(<?= $rest['RestaurantID'] ?>)" class="btn btn-danger">
                                Sil
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<style>
    .custom-modal-bg {
        position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(0,0,0,0.25);
        z-index: 9999;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .custom-modal {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 4px 24px rgba(0,0,0,0.18);
        padding: 32px 28px;
        min-width: 320px;
        max-width: 90vw;
        text-align: center;
        font-size: 18px;
        color: #292929;
    }
    .custom-modal .modal-actions {
        margin-top: 24px;
        display: flex;
        gap: 16px;
        justify-content: center;
    }
    .custom-modal .modal-btn {
        padding: 8px 24px;
        border-radius: 8px;
        border: none;
        font-size: 16px;
        cursor: pointer;
        font-weight: 600;
    }
    .custom-modal .modal-btn.confirm { background: #0057d9; color: #fff; }
    .custom-modal .modal-btn.cancel { background: #f0f0f0; color: #292929; }
    .custom-modal .modal-btn.ok { background: #ff2d55; color: #fff; }
</style>

<script>
function showModal(message, type = 'confirm', callback = null) {
    // type: 'confirm' (Tamam/İptal), 'ok' (Tamam)
    const bg = document.createElement('div');
    bg.className = 'custom-modal-bg';
    const modal = document.createElement('div');
    modal.className = 'custom-modal';
    modal.innerHTML = `<div>${message}</div>`;
    const actions = document.createElement('div');
    actions.className = 'modal-actions';
    if (type === 'confirm') {
        const btnOk = document.createElement('button');
        btnOk.textContent = 'Tamam';
        btnOk.className = 'modal-btn confirm';
        btnOk.onclick = () => { document.body.removeChild(bg); if (callback) callback(true); };
        const btnCancel = document.createElement('button');
        btnCancel.textContent = 'İptal';
        btnCancel.className = 'modal-btn cancel';
        btnCancel.onclick = () => { document.body.removeChild(bg); if (callback) callback(false); };
        actions.appendChild(btnOk);
        actions.appendChild(btnCancel);
    } else {
        const btnOk = document.createElement('button');
        btnOk.textContent = 'Tamam';
        btnOk.className = 'modal-btn ok';
        btnOk.onclick = () => { document.body.removeChild(bg); if (callback) callback(true); };
        actions.appendChild(btnOk);
    }
    modal.appendChild(actions);
    bg.appendChild(modal);
    document.body.appendChild(bg);
}

function deleteRestaurant(id) {
    showModal("Bu restoran ve hesabı silinecek. Emin misin?", 'confirm', function(confirmed) {
        if (!confirmed) return;
        fetch('/backend/delete_restaurant.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: 'restaurant_id=' + id
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                showModal('Restoran silindi', 'ok', function(){ location.reload(); });
            } else {
                showModal('Hata: ' + data.error, 'ok');
            }
        });
    });
}
</script>

<?php include '../includes/footer.php'; ?>
