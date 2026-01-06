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

$pageTitle = 'Restoran Bilgileri';
$activePage = 'info';

$success = '';
$error = '';

// Restoran bilgilerini al
try {
    $stmt = $pdo->prepare("SELECT * FROM Restaurants WHERE OwnerUserID = ?");
    $stmt->execute([$_SESSION['restaurant_id']]);
    $restaurant = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = $e->getMessage();
}

// Form gönderimi
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $category = $_POST['category'] ?? '';
    $address = $_POST['address'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $description = $_POST['description'] ?? '';
    $minOrderAmount = $_POST['min_order_amount'] ?? 0;
    $deliveryTime = $_POST['delivery_time'] ?? 0;
    $isOpen = isset($_POST['is_open']) ? 1 : 0;
    
    try {
        $stmt = $pdo->prepare("UPDATE Restaurants SET Name = ?, Category = ?, Address = ?, Phone = ?, Description = ?, MinOrderAmount = ?, DeliveryTime = ?, IsOpen = ? WHERE RestaurantID = ?");
        $stmt->execute([$name, $category, $address, $phone, $description, $minOrderAmount, $deliveryTime, $isOpen, $restaurant['RestaurantID']]);
        
        $success = 'Restoran bilgileri güncellendi!';
        $restaurant = array_merge($restaurant, [
            'Name' => $name,
            'Category' => $category,
            'Address' => $address,
            'Phone' => $phone,
            'Description' => $description,
            'MinOrderAmount' => $minOrderAmount,
            'DeliveryTime' => $deliveryTime,
            'IsOpen' => $isOpen
        ]);
    } catch (PDOException $e) {
        $error = 'Güncelleme hatası: ' . $e->getMessage();
    }
}

include '../includes/header.php';
?>

<div class="page-header">
    <h1>Restoran Bilgileri</h1>
    <p>Restoranınızın bilgilerini yönetin</p>
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
    <form method="POST">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div class="form-group">
                <label>Restoran Adı</label>
                <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($restaurant['Name']) ?>" required>
            </div>
            
            <div class="form-group">
                <label>Kategori</label>
                <input type="text" name="category" class="form-control" value="<?= htmlspecialchars($restaurant['Category']) ?>" required>
            </div>
        </div>
        
        <div class="form-group">
            <label>Açıklama</label>
            <textarea name="description" class="form-control" rows="3"><?= htmlspecialchars($restaurant['Description'] ?? '') ?></textarea>
        </div>
        
        <div class="form-group">
            <label>Adres</label>
            <textarea name="address" class="form-control" rows="2" required><?= htmlspecialchars($restaurant['Address']) ?></textarea>
        </div>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px;">
            <div class="form-group">
                <label>Telefon</label>
                <input type="tel" name="phone" class="form-control" value="<?= htmlspecialchars($restaurant['Phone']) ?>" required>
            </div>
            
            <div class="form-group">
                <label>Min. Sipariş Tutarı (₺)</label>
                <input type="number" step="0.01" name="min_order_amount" class="form-control" value="<?= $restaurant['MinOrderAmount'] ?>" required>
            </div>
            
            <div class="form-group">
                <label>Teslimat Süresi (dk)</label>
                <input type="number" name="delivery_time" class="form-control" value="<?= $restaurant['DeliveryTime'] ?>" required>
            </div>
        </div>
        
        <div class="form-group">
            <label style="display: flex; align-items: center; gap: 12px; cursor: pointer;">
                <input type="checkbox" name="is_open" <?= $restaurant['IsOpen'] ? 'checked' : '' ?>>
                <span>Restoran Açık</span>
            </label>
        </div>
        
        <button type="submit" class="btn btn-primary">Güncelle</button>
    </form>
</div>

<?php include '../includes/footer.php'; ?>
