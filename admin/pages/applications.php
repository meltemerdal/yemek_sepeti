<?php
session_name('admin_session');
session_start();

require_once '../../backend/config.php';
require_once '../../backend/auth_helper.php';

requireAuth('Admin');

$pageTitle = 'Restoran Başvuruları';
$activePage = 'applications';

$success = '';
$error = '';

// Durum güncelleme işlemi
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $restaurantId = $_POST['restaurant_id'] ?? 0;
    $action = $_POST['action'];
    
    try {
        if ($action === 'approve') {
            $stmt = $pdo->prepare("UPDATE Restaurants SET Status = 1 WHERE RestaurantID = ?");
            $stmt->execute([$restaurantId]);
            $success = 'Restoran başvurusu onaylandı!';
        } elseif ($action === 'reject') {
            $stmt = $pdo->prepare("UPDATE Restaurants SET Status = 2 WHERE RestaurantID = ?");
            $stmt->execute([$restaurantId]);
            $success = 'Restoran başvurusu reddedildi!';
        } elseif ($action === 'suspend') {
            $stmt = $pdo->prepare("UPDATE Restaurants SET Status = 3 WHERE RestaurantID = ?");
            $stmt->execute([$restaurantId]);
            $success = 'Restoran askıya alındı!';
        }
    } catch (PDOException $e) {
        $error = 'İşlem hatası: ' . $e->getMessage();
    }
}

// Pending durumundaki restoranları getir
try {
    $stmt = $pdo->query("
        SELECT r.RestaurantID, r.Name, r.Category, r.Phone, r.Address, r.CreatedDate, r.Status,
               u.Email, u.FullName as OwnerName
        FROM Restaurants r
        LEFT JOIN Users u ON r.OwnerUserID = u.UserID
        WHERE r.Status IN (0, 2)
        ORDER BY r.CreatedDate DESC
    ");
    $applications = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = $e->getMessage();
}

include '../includes/header.php';
?>

<div class="page-header">
    <h1>Restoran Başvuruları</h1>
    <p>Bekleyen ve reddedilen restoran başvurularını yönetin</p>
</div>

<?php if ($success): ?>
    <div class="alert alert-success"><?= $success ?></div>
<?php endif; ?>

<?php if ($error): ?>
    <div class="alert alert-danger"><?= $error ?></div>
<?php endif; ?>

<div class="card">
    <?php if (empty($applications)): ?>
        <div class="empty-state">
            <div class="icon">✅</div>
            <h3>Bekleyen başvuru yok</h3>
            <p>Yeni restoran başvuruları buradan görüntülenecek</p>
        </div>
    <?php else: ?>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Restoran Adı</th>
                        <th>Kategori</th>
                        <th>Sahip</th>
                        <th>E-posta</th>
                        <th>Telefon</th>
                        <th>Kayıt Tarihi</th>
                        <th>Durum</th>
                        <th>İşlemler</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($applications as $app): ?>
                        <tr>
                            <td><strong>#<?= $app['RestaurantID'] ?></strong></td>
                            <td><?= htmlspecialchars($app['Name']) ?></td>
                            <td><?= htmlspecialchars($app['Category']) ?></td>
                            <td><?= htmlspecialchars($app['OwnerName'] ?? 'Belirtilmemiş') ?></td>
                            <td><?= htmlspecialchars($app['Email'] ?? 'Belirtilmemiş') ?></td>
                            <td><?= htmlspecialchars($app['Phone']) ?></td>
                            <td><?= date('d.m.Y H:i', strtotime($app['CreatedDate'])) ?></td>
                            <td>
                                <?php if ($app['Status'] == 0): ?>
                                    <span class="badge badge-warning">Bekliyor</span>
                                <?php elseif ($app['Status'] == 2): ?>
                                    <span class="badge badge-danger">Reddedildi</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($app['Status'] == 0): ?>
                                    <form method="POST" style="display: inline;">
                                        <input type="hidden" name="restaurant_id" value="<?= $app['RestaurantID'] ?>">
                                        <button type="submit" name="action" value="approve" class="btn btn-sm btn-success" onclick="return confirm('Bu restoranı onaylamak istediğinizden emin misiniz?')">
                                            ✓ Onayla
                                        </button>
                                        <button type="submit" name="action" value="reject" class="btn btn-sm btn-danger" onclick="return confirm('Bu restoranı reddetmek istediğinizden emin misiniz?')">
                                            ✗ Reddet
                                        </button>
                                        <button type="submit" name="action" value="suspend" class="btn btn-sm btn-warning" onclick="return confirm('Bu restoranı askıya almak istediğinizden emin misiniz?')">
                                            ⊘ Askıya Al
                                        </button>
                                    </form>
                                <?php elseif ($app['Status'] == 2): ?>
                                    <form method="POST" style="display: inline;">
                                        <input type="hidden" name="restaurant_id" value="<?= $app['RestaurantID'] ?>">
                                        <button type="submit" name="action" value="approve" class="btn btn-sm btn-success" onclick="return confirm('Bu restoranı onaylamak istediğinizden emin misiniz?')">
                                            ✓ Onayla
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<style>
.btn {
    padding: 8px 15px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 14px;
    transition: all 0.3s ease;
}

.btn-sm {
    padding: 5px 12px;
    font-size: 13px;
}

.btn-success {
    background: #28a745;
    color: white;
}

.btn-success:hover {
    background: #218838;
}

.btn-danger {
    background: #dc3545;
    color: white;
}

.btn-danger:hover {
    background: #c82333;
}

.alert {
    padding: 15px 20px;
    margin-bottom: 20px;
    border-radius: 5px;
    font-weight: 500;
}

.alert-success {
    background: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.alert-danger {
    background: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}
</style>

<?php include '../includes/footer.php'; ?>
