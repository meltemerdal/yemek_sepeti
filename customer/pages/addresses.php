<?php
session_name('customer_session');
session_start();

require_once '../../backend/config.php';
require_once '../../backend/auth_helper.php';

requireAuth('Customer');

$pageTitle = 'Adreslerim';
$activePage = 'addresses';

$success = '';
$error = '';

// Adres silme
if (isset($_GET['delete'])) {
    try {
        $stmt = $pdo->prepare("DELETE FROM Addresses WHERE AddressID = ? AND UserID = ?");
        $stmt->execute([$_GET['delete'], $_SESSION['user_id']]);
        $success = 'Adres silindi!';
    } catch (PDOException $e) {
        $error = 'Silme hatası: ' . $e->getMessage();
    }
}

// Adres ekleme
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $addressText = $_POST['address_text'] ?? '';
    $district = $_POST['district'] ?? '';
    $city = $_POST['city'] ?? '';
    
    try {
        $stmt = $pdo->prepare("INSERT INTO Addresses (UserID, Title, AddressText, District, City) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$_SESSION['user_id'], $title, $addressText, $district, $city]);
        $success = 'Adres eklendi!';
    } catch (PDOException $e) {
        $error = 'Ekleme hatası: ' . $e->getMessage();
    }
}

// Adresleri getir
try {
    $stmt = $pdo->prepare("SELECT * FROM Addresses WHERE UserID = ? ORDER BY IsDefault DESC, CreatedDate DESC");
    $stmt->execute([$_SESSION['user_id']]);
    $addresses = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = $e->getMessage();
}

include '../includes/header.php';
?>

<div class="page-header">
    <h1>Adreslerim</h1>
    <p>Teslimat adreslerinizi yönetin</p>
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
    <h3 style="margin-bottom: 24px;">Yeni Adres Ekle</h3>
    
    <form method="POST">
        <div class="form-group">
            <label>Adres Başlığı</label>
            <input type="text" name="title" class="form-control" placeholder="Ev, İş vb." required>
        </div>
        
        <div class="form-group">
            <label>Adres</label>
            <textarea name="address_text" class="form-control" rows="3" required></textarea>
        </div>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
            <div class="form-group">
                <label>İlçe</label>
                <input type="text" name="district" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label>Şehir</label>
                <input type="text" name="city" class="form-control" required>
            </div>
        </div>
        
        <button type="submit" class="btn btn-primary">Adres Ekle</button>
    </form>
</div>

<div class="card">
    <h3 style="margin-bottom: 24px;">Kayıtlı Adreslerim</h3>
    
    <?php if (empty($addresses)): ?>
        <div class="empty-state">
            <div class="icon">📍</div>
            <h3>Henüz adres eklemediniz</h3>
            <p>Sipariş verebilmek için bir adres ekleyin</p>
        </div>
    <?php else: ?>
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 16px;">
            <?php foreach ($addresses as $address): ?>
                <div style="border: 2px solid #e0e0e0; border-radius: 12px; padding: 20px; position: relative;">
                    <?php if ($address['IsDefault']): ?>
                        <span class="badge badge-success" style="position: absolute; top: 12px; right: 12px;">Varsayılan</span>
                    <?php endif; ?>
                    
                    <h4 style="font-size: 18px; margin-bottom: 8px;"><?= htmlspecialchars($address['Title']) ?></h4>
                    <p style="color: #666; font-size: 14px; margin-bottom: 8px;"><?= htmlspecialchars($address['AddressText']) ?></p>
                    <p style="color: #999; font-size: 13px; margin-bottom: 16px;"><?= htmlspecialchars($address['District']) ?>, <?= htmlspecialchars($address['City']) ?></p>
                    
                    <a href="?delete=<?= $address['AddressID'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Bu adresi silmek istediğinize emin misiniz?')">Sil</a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>
