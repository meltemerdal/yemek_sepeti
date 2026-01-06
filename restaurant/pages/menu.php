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

$pageTitle = 'Menü Yönetimi';
$activePage = 'menu';

$success = '';
$error = '';

// Başarı mesajını kontrol et
if (isset($_GET['success']) && $_GET['success'] == '1') {
    $success = 'Ürün başarıyla eklendi!';
}

$restaurantId = $restaurant['RestaurantID'];

// Kategorileri getir
try {
    $stmt = $pdo->prepare("SELECT * FROM MenuCategories WHERE RestaurantID = ? ORDER BY CategoryName");
    $stmt->execute([$restaurantId]);
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $categories = [];
}

// Ürün silme işlemi kaldırıldı (artık AJAX ile backend/delete_menu.php kullanılıyor)

// Menü ürünlerini getir
try {
    $stmt = $pdo->prepare("
        SELECT 
            mi.MenuItemID,
            mi.Name,
            mi.Description,
            mi.Price,
            mi.IsAvailable,
            mi.Stock,
            mi.ImageURL,
            mc.CategoryName
        FROM MenuItems mi
        LEFT JOIN MenuCategories mc ON mi.CategoryID = mc.CategoryID
        WHERE mi.RestaurantID = ? AND mi.IsVisible = 1
        ORDER BY mi.MenuItemID DESC
    ");
    $stmt->execute([$restaurantId]);
    $menuItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = $e->getMessage();
}

include '../includes/header.php';
?>

<?php if (isset($_GET['success'])): ?>
<div class="alert alert-success">Menü başarıyla eklendi</div>
<?php endif; ?>

<div class="page-header">
    <h1>Menü Yönetimi</h1>
    <p>Ürünlerinizi ekleyin ve yönetin</p>
</div>

<?php if ($error): ?>
    <div class="card" style="background: #f8d7da; color: #721c24; margin-bottom: 20px;">
        <?= htmlspecialchars($error) ?>
    </div>
<?php endif; ?>

<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <h3>Yeni Ürün Ekle</h3>
        <a href="category_manage.php" class="btn btn-info">Kategori Yönetimi</a>
    </div>
    <?php if (count($categories) === 0): ?>
        <div style="background: #fff3cd; color: #856404; padding: 16px; border-radius: 8px; margin-bottom: 20px;">
            <strong>⚠ Uyarı:</strong> Menü ekleyebilmek için önce <a href="category_manage.php" style="color: #533f03; text-decoration: underline;">kategori oluşturmalısınız</a>.
        </div>
    <?php else: ?>
    <form method="POST" action="add_menu.php" enctype="multipart/form-data">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div class="form-group">
                <label>Ürün Adı</label>
                <input type="text" name="menu_name" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Kategori</label>
                <select name="category_id" class="form-control" required>
                    <option value="">Kategori Seçin</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?php echo $cat['CategoryID']; ?>"><?php echo htmlspecialchars($cat['CategoryName']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label>Açıklama</label>
            <textarea name="menu_description" class="form-control" rows="2" required></textarea>
        </div>
        <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 20px;">
            <div class="form-group">
                <label>Fiyat (₺)</label>
                <input type="number" step="0.01" name="menu_price" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Ürün Görseli</label>
                <input type="file" name="menu_image" class="form-control" accept="image/*">
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Ürün Ekle</button>
    </form>
    <?php endif; ?>
</div>

<div class="card">
    <h3 style="margin-bottom: 24px;">Mevcut Ürünler</h3>
    
    <?php if (empty($menuItems)): ?>
        <div class="empty-state">
            <div class="icon">🍽️</div>
            <h3>Henüz ürün eklemediniz</h3>
            <p>Yukarıdaki formu kullanarak ilk ürününüzü ekleyin</p>
        </div>
    <?php else: ?>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Görsel</th>
                        <th>Ürün Adı</th>
                        <th>Kategori</th>
                        <th>Açıklama</th>
                        <th>Fiyat</th>
                        <th>Durum</th>
                        <th>İşlemler</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($menuItems as $item): ?>
                        <tr>
                            <td>
                                <?php if (!empty($item['ImageURL'])): ?>
                                    <img src="/<?= htmlspecialchars($item['ImageURL']) ?>" alt="<?= htmlspecialchars($item['Name']) ?>" style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px;">
                                <?php else: ?>
                                    <div style="width: 60px; height: 60px; background: #f0f0f0; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #999;">📷</div>
                                <?php endif; ?>
                            </td>
                            <td><strong><?= htmlspecialchars($item['Name']) ?></strong></td>
                            <td><?= htmlspecialchars($item['CategoryName'] ?? '-') ?></td>
                            <td style="max-width: 300px;"><?= htmlspecialchars($item['Description'] ?? '-') ?></td>
                            <td>₺<?= number_format($item['Price'], 2) ?></td>
                            <td>
                                <span class="badge <?= $item['IsAvailable'] ? 'badge-success' : 'badge-danger' ?>">
                                    <?= $item['IsAvailable'] ? 'Mevcut' : 'Tükendi' ?>
                                </span>
                            </td>
                            <td>
                                <?php if ($item['IsAvailable']): ?>
                                    <button onclick="markUnavailable(<?= $item['MenuItemID'] ?>)" class="btn btn-warning btn-sm">Tükendi Yap</button>
                                <?php endif; ?>
                                <button onclick="deleteMenu(<?= $item['MenuItemID'] ?>)" class="btn btn-danger btn-sm">Sil</button>
                                <button onclick='openUpdateModal(<?= json_encode($item, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>)' class="btn btn-info btn-sm">Güncelle</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<script>
function showCustomConfirm(message, onConfirm) {
    const modal = document.createElement('div');
    modal.className = 'custom-modal';
    modal.innerHTML = `
        <div class="custom-modal-content">
            <p>${message}</p>
            <button id="customConfirmOk">Tamam</button>
            <button id="customConfirmCancel">İptal</button>
        </div>
    `;
    document.body.appendChild(modal);
    document.getElementById('customConfirmOk').onclick = () => {
        document.body.removeChild(modal);
        onConfirm(true);
    };
    document.getElementById('customConfirmCancel').onclick = () => {
        document.body.removeChild(modal);
        onConfirm(false);
    };
}

function showNotification(message) {
    const notif = document.createElement('div');
    notif.className = 'custom-notification';
    notif.textContent = message;
    document.body.appendChild(notif);
    setTimeout(() => {
        notif.remove();
    }, 2000);
}

function markUnavailable(menuId) {
    showCustomConfirm('Bu ürünü tükendi olarak işaretlemek istediğinize emin misiniz?', function(result) {
        if (!result) return;
        fetch('/backend/mark_unavailable.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `menu_id=${menuId}`
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                showNotification('İşlem başarısız');
            }
        })
        .catch(err => {
            console.error(err);
            showNotification('Sunucu hatası');
        });
    });
}

function deleteMenu(menuId) {
    showCustomConfirm('Bu ürünü silmek istediğinize emin misiniz?', function(result) {
        if (!result) return;
        fetch('/backend/delete_menu.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `menu_id=${menuId}`
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                showNotification('Silme başarısız');
            }
        })
        .catch(err => {
            console.error(err);
            showNotification('Sunucu hatası');
        });
    });
}

// Güncelleme Modalı
function openUpdateModal(item) {
    // Modalı oluştur
    let modal = document.getElementById('updateModal');
    if (!modal) {
        modal = document.createElement('div');
        modal.id = 'updateModal';
        modal.style.position = 'fixed';
        modal.style.top = '0';
        modal.style.left = '0';
        modal.style.width = '100vw';
        modal.style.height = '100vh';
        modal.style.background = 'rgba(0,0,0,0.4)';
        modal.style.display = 'flex';
        modal.style.alignItems = 'center';
        modal.style.justifyContent = 'center';
        modal.style.zIndex = '9999';
        modal.innerHTML = `
            <div style="background: #fff; padding: 32px; border-radius: 12px; min-width: 350px; max-width: 95vw; position: relative;">
                <h3>Ürünü Güncelle</h3>
                <form id="updateMenuForm" enctype="multipart/form-data">
                    <input type="hidden" name="menu_id" value="${item.MenuItemID}">
                    <div class="form-group">
                        <label>Ürün Adı</label>
                        <input type="text" name="menu_name" class="form-control" value="${item.Name}" required>
                    </div>
                    <div class="form-group">
                        <label>Kategori</label>
                        <select name="category_id" class="form-control" required>
                            ${window.menuCategoriesOptionsHtml || ''}
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Açıklama</label>
                        <textarea name="menu_description" class="form-control" rows="2" required>${item.Description || ''}</textarea>
                    </div>
                    <div class="form-group">
                        <label>Fiyat (₺)</label>
                        <input type="number" step="0.01" name="menu_price" class="form-control" value="${item.Price}" required>
                    </div>
                    <div class="form-group">
                        <label>Ürün Görseli (değiştirmek için seçin)</label>
                        <input type="file" name="menu_image" class="form-control" accept="image/*">
                    </div>
                    <button type="submit" class="btn btn-primary">Kaydet</button>
                    <button type="button" class="btn btn-secondary" onclick="closeUpdateModal()">İptal</button>
                </form>
                <button onclick="closeUpdateModal()" style="position:absolute;top:8px;right:12px;font-size:20px;background:none;border:none;">&times;</button>
            </div>
        `;
        document.body.appendChild(modal);
    } else {
        modal.style.display = 'flex';
    }
    // Kategori seçeneklerini doldur
    let select = modal.querySelector('select[name="category_id"]');
    if (select && window.menuCategoriesOptionsHtml) {
        select.innerHTML = window.menuCategoriesOptionsHtml;
        select.value = item.CategoryID || '';
    }
    // Form submit
    modal.querySelector('#updateMenuForm').onsubmit = function(e) {
        e.preventDefault();
        let formData = new FormData(this);
        fetch('update_menu.php', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                closeUpdateModal();
                location.reload();
            } else {
                showNotification('Güncelleme başarısız: ' + (data.message || ''));
            }
        })
        .catch(err => {
            showNotification('Sunucu hatası');
        });
    };
}
function closeUpdateModal() {
    let modal = document.getElementById('updateModal');
    if (modal) modal.style.display = 'none';
}

// Kategori seçeneklerini JS'e aktar
window.menuCategoriesOptionsHtml = `<?php foreach ($categories as $cat): ?><option value="<?= $cat['CategoryID'] ?>"><?= htmlspecialchars($cat['CategoryName']) ?></option><?php endforeach; ?>`;
</script>

<?php include '../includes/footer.php'; ?>
