<?php
session_name('admin_session');
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../../backend/config.php';

if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'Admin') {
    header("Location: /admin_login.php");
    exit;
}
require_once '../../backend/auth_helper.php';

requireAuth('Admin');

$pageTitle = 'Kullanıcı Yönetimi';
$activePage = 'users';

$success = '';
$error = '';

// Silme başarı mesajı
if (isset($_GET['success']) && $_GET['success'] == 1) {
    $success = 'Kullanıcı başarıyla silindi!';
}

// Kullanıcı silme
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'Admin') {
        die('YETKİSİZ');
    }

    $userId = intval($_GET['id']);

    // Admin kendini silemesin
    if ($userId == $_SESSION['user_id']) {
        die("Kendinizi silemezsiniz.");
    }

    try {
        $stmt = $pdo->prepare("DELETE FROM Users WHERE UserID = ?");
        $stmt->execute([$userId]);

        header("Location: users.php?success=1");
        exit;

    } catch (PDOException $e) {
        $error = 'Hata: ' . $e->getMessage();
    }
}

// Kullanıcı pasif etme
if (isset($_GET['action']) && $_GET['action'] === 'toggle' && isset($_GET['id'])) {
    $userId = (int) $_GET['id'];
    
    try {
        $stmt = $pdo->prepare("UPDATE Users SET IsActive = CASE WHEN IsActive = 1 THEN 0 ELSE 1 END WHERE UserID = ?");
        $stmt->execute([$userId]);
        $success = 'Kullanıcı durumu güncellendi!';
    } catch (PDOException $e) {
        $error = 'Hata: ' . $e->getMessage();
    }
}

// Kullanıcıları getir
try {
    $stmt = $pdo->query("SELECT UserID, FullName, Email, Phone, UserType, IsActive, CreatedDate FROM Users ORDER BY CreatedDate DESC");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = $e->getMessage();
}

include '../includes/header.php';
?>

<div class="page-header">
    <h1>Kullanıcı Yönetimi</h1>
    <p>Tüm kullanıcıları yönetin</p>
</div>

<?php if ($success): ?>
    <div class="card" style="background: #d4edda; color: #155724; margin-bottom: 20px;">
        <?= htmlspecialchars($success) ?>
    </div>
<?php endif; ?>

<div class="card">
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Ad Soyad</th>
                    <th>E-posta</th>
                    <th>Telefon</th>
                    <th>Rol</th>
                    <th>Kayıt Tarihi</th>
                    <th>Durum</th>
                    <th>İşlemler</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= $user['UserID'] ?></td>
                        <td><?= htmlspecialchars($user['FullName']) ?></td>
                        <td><?= htmlspecialchars($user['Email']) ?></td>
                        <td><?= htmlspecialchars($user['Phone']) ?></td>
                        <td>
                            <?php
                            $roleText = [
                                'Customer' => 'Müşteri',
                                'RestaurantOwner' => 'Restoran Sahibi',
                                'Admin' => 'Yönetici'
                            ];
                            $badgeClass = $user['UserType'] === 'Admin' ? 'badge-danger' : ($user['UserType'] === 'RestaurantOwner' ? 'badge-warning' : 'badge-info');
                            ?>
                            <span class="badge <?= $badgeClass ?>"><?= $roleText[$user['UserType']] ?? $user['UserType'] ?></span>
                        </td>
                        <td><?= date('d.m.Y', strtotime($user['CreatedDate'])) ?></td>
                        <td>
                            <span class="badge <?= $user['IsActive'] ? 'badge-success' : 'badge-dark' ?>">
                                <?= $user['IsActive'] ? 'Aktif' : 'Pasif' ?>
                            </span>
                        </td>
                        <td>
                            <?php if ($user['UserType'] !== 'Admin'): ?>
                                <a href="?action=toggle&id=<?= $user['UserID'] ?>" class="btn btn-warning btn-sm">
                                    <?= $user['IsActive'] ? 'Pasif Yap' : 'Aktif Yap' ?>
                                </a>
                                <button class="btn btn-danger btn-sm" onclick="confirmDeleteUser(<?= $user['UserID'] ?>)">Sil</button>
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

                            function confirmDeleteUser(userId) {
                                showModal('Bu kullanıcıyı silmek istediğinize emin misiniz?', 'confirm', function(confirmed) {
                                    if (!confirmed) return;
                                    window.location.href = '?action=delete&id=' + userId;
                                });
                            }
                            </script>
                            <?php else: ?>
                                <span style="color: #999;">Korumalı</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
