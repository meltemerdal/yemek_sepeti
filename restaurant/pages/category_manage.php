<?php
session_name('restaurant_session');
session_start();

require_once '../../backend/config.php';
require_once '../../backend/auth_helper.php';
requireAuth('RestaurantOwner');

// Restoran ID
$stmt = $pdo->prepare("SELECT RestaurantID FROM Restaurants WHERE OwnerUserID = ?");
$stmt->execute([$_SESSION['restaurant_id']]);
$restaurant = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$restaurant) {
    echo 'Restoran bulunamadı';
    exit;
}
$restaurantId = $restaurant['RestaurantID'];

// Kategori ekleme
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_category'])) {
    $catName = trim($_POST['category_name'] ?? '');
    if ($catName !== '') {
        $stmt = $pdo->prepare("INSERT INTO MenuCategories (RestaurantID, CategoryName) VALUES (?, ?)");
        $stmt->execute([$restaurantId, $catName]);
        header('Location: category_manage.php?success=1');
        exit;
    }
}

// Kategori silme
if (isset($_GET['delete'])) {
    $catId = intval($_GET['delete']);
    $stmt = $pdo->prepare("DELETE FROM MenuCategories WHERE CategoryID = ? AND RestaurantID = ?");
    $stmt->execute([$catId, $restaurantId]);
    header('Location: category_manage.php?deleted=1');
    exit;
}

// Kategori güncelleme
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_category'])) {
    $catId = intval($_POST['edit_category']);
    $catName = trim($_POST['category_name'] ?? '');
    if ($catName !== '') {
        $stmt = $pdo->prepare("UPDATE MenuCategories SET CategoryName = ? WHERE CategoryID = ? AND RestaurantID = ?");
        $stmt->execute([$catName, $catId, $restaurantId]);
        header('Location: category_manage.php?updated=1');
        exit;
    }
}

// Kategorileri getir
$stmt = $pdo->prepare("SELECT * FROM MenuCategories WHERE RestaurantID = ? ORDER BY CategoryName");
$stmt->execute([$restaurantId]);
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

include '../includes/header.php';
?>
<div class="page-header">
    <h1>Kategori Yönetimi</h1>
    <p>Menü kategorilerinizi ekleyin, silin veya güncelleyin.</p>
</div>

<?php if (isset($_GET['success'])): ?>
    <div class="alert alert-success">Kategori başarıyla eklendi.</div>
<?php elseif (isset($_GET['deleted'])): ?>
    <div class="alert alert-success">Kategori silindi.</div>
<?php elseif (isset($_GET['updated'])): ?>
    <div class="alert alert-success">Kategori güncellendi.</div>
<?php endif; ?>

<div class="card">
    <h3>Yeni Kategori Ekle</h3>
    <form method="POST">
        <input type="text" name="category_name" class="form-control" placeholder="Kategori Adı" required style="width: 250px; display: inline-block;">
        <button type="submit" name="add_category" class="btn btn-primary">Ekle</button>
    </form>
</div>

<div class="card" style="margin-top: 32px;">
    <h3>Mevcut Kategoriler</h3>
    <table style="width:100%;margin-top:12px;">
        <thead>
            <tr>
                <th>Kategori Adı</th>
                <th>İşlemler</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($categories as $cat): ?>
            <tr>
                <td>
                    <form method="POST" style="display:inline-block;">
                        <input type="hidden" name="edit_category" value="<?= $cat['CategoryID'] ?>">
                        <input type="text" name="category_name" value="<?= htmlspecialchars($cat['CategoryName']) ?>" class="form-control" style="width:180px;display:inline-block;">
                        <button type="submit" class="btn btn-info btn-sm">Güncelle</button>
                    </form>
                </td>
                <td>
                    <a href="?delete=<?= $cat['CategoryID'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Kategoriyi silmek istediğinize emin misiniz?')">Sil</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include '../includes/footer.php'; ?>
