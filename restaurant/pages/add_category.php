
<?php
session_name('restaurant_session');
session_start();
require_once '../../backend/config.php';
require_once '../../backend/auth_helper.php';

requireAuth('RestaurantOwner');

$pageTitle = 'Menü Kategorisi Ekle';
$activePage = 'menu';

$success = '';
$error = '';

// Restoran ID'yi al ve Status kontrolü
$stmt = $pdo->prepare("SELECT RestaurantID, Status FROM Restaurants WHERE OwnerUserID = ?");
$stmt->execute([$_SESSION['restaurant_id']]);
$restaurant = $stmt->fetch(PDO::FETCH_ASSOC);

$status = (int)$restaurant['Status'];
if ($status !== 1) {
    header('Location: /restaurant/pages/menu.php');
    exit();
}

$restaurantId = $restaurant['RestaurantID'];

// Kategori ekleme
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_category'])) {
    $categoryName = trim($_POST['category_name'] ?? '');
    
    if (empty($categoryName)) {
        $error = 'Kategori adı boş olamaz!';
    } else {
        try {
            // Aynı isimde kategori var mı kontrol et
            $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM MenuCategories WHERE RestaurantID = ? AND CategoryName = ?");
            $stmt->execute([$restaurantId, $categoryName]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($result['total'] > 0) {
                $error = 'Bu kategori zaten mevcut!';
            } else {
                $stmt = $pdo->prepare("INSERT INTO MenuCategories (RestaurantID, CategoryName) VALUES (?, ?)");
                $stmt->execute([$restaurantId, $categoryName]);
                $success = 'Kategori başarıyla eklendi!';
            }
        } catch (PDOException $e) {
            $error = 'Kategori eklenemedi: ' . $e->getMessage();
        }
    }
}

// Mevcut kategorileri getir
try {
    $stmt = $pdo->prepare("SELECT * FROM MenuCategories WHERE RestaurantID = ? ORDER BY CategoryName");
    $stmt->execute([$restaurantId]);
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = $e->getMessage();
}

include '../includes/header.php';
?>

<div class="page-header">
    <h1>Menü Kategorisi Ekle</h1>
    <a href="/restaurant/pages/menu.php" class="btn btn-secondary">← Menü Yönetimine Dön</a>
</div>

<?php if ($success): ?>
    <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
<?php endif; ?>

<?php if ($error): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3>Yeni Kategori Ekle</h3>
            </div>
            <div class="card-body">
                <form method="POST">
                    <div class="form-group">
                        <label for="category_name">Kategori Adı <span style="color: red;">*</span></label>
                        <input type="text" 
                               id="category_name" 
                               name="category_name" 
                               class="form-control" 
                               placeholder="Örn: Çorbalar, Ana Yemekler, İçecekler" 
                               required>
                    </div>
                    
                    <button type="submit" name="add_category" class="btn btn-primary">
                        💾 Kaydet
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3>Mevcut Kategoriler</h3>
            </div>
            <div class="card-body">
                <?php if (empty($categories)): ?>
                    <p class="text-muted">Henüz kategori eklenmemiş.</p>
                <?php else: ?>
                    <ul class="list-group">
                        <?php foreach ($categories as $cat): ?>
                            <li class="list-group-item">
                                📁 <?= htmlspecialchars($cat['CategoryName']) ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<style>
.row {
    display: flex;
    gap: 20px;
    margin-top: 20px;
}

.col-md-6 {
    flex: 1;
}

.card {
    background: white;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.card-header {
    padding: 20px;
    border-bottom: 1px solid #e0e0e0;
}

.card-header h3 {
    margin: 0;
    font-size: 18px;
    color: #333;
}

.card-body {
    padding: 20px;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: 500;
    color: #333;
}

.form-control {
    width: 100%;
    padding: 10px 15px;
    border: 2px solid #e0e0e0;
    border-radius: 8px;
    font-size: 15px;
    transition: border-color 0.3s;
}

.form-control:focus {
    outline: none;
    border-color: #f5576c;
}

.list-group {
    list-style: none;
    padding: 0;
    margin: 0;
}

.list-group-item {
    padding: 12px 15px;
    border: 1px solid #e0e0e0;
    border-radius: 6px;
    margin-bottom: 8px;
}

.text-muted {
    color: #999;
    text-align: center;
    padding: 20px;
}

.btn {
    padding: 10px 20px;
    border: none;
    border-radius: 8px;
    font-size: 15px;
    cursor: pointer;
    transition: all 0.3s;
}

.btn-primary {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    color: white;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(245, 87, 108, 0.3);
}

.btn-secondary {
    background: #6c757d;
    color: white;
    text-decoration: none;
    display: inline-block;
}

.btn-secondary:hover {
    background: #5a6268;
}
</style>

<?php include '../includes/footer.php'; ?>
