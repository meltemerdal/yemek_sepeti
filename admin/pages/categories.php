<?php
session_name('admin_session');
session_start();

require_once '../../backend/config.php';
require_once '../../backend/auth_helper.php';

requireAuth('Admin');

$pageTitle = 'Kategori Yönetimi';
$activePage = 'categories';

$success = '';
$error = '';

// Kategori silme
if (isset($_GET['action']) && $_GET['action'] === 'delete') {
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
        die("Yetkisiz");
    }

    $id = intval($_GET['id']);

    try {
        $stmt = $pdo->prepare("DELETE FROM Categories WHERE CategoryID = ?");
        $stmt->execute([$id]);

        header("Location: categories.php?deleted=1");
        exit;
    } catch (PDOException $e) {
        die("SQL Hatası: " . $e->getMessage());
    }
}

// Silme başarı mesajı
if (isset($_GET['deleted']) && $_GET['deleted'] == 1) {
    $success = 'Kategori başarıyla silindi!';
}

// Kategori ekleme
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_category'])) {
    $name = $_POST['name'] ?? '';
    
    try {
        $stmt = $pdo->prepare("INSERT INTO Categories (Name, IsActive) VALUES (?, 1)");
        $stmt->execute([$name]);
        $success = 'Kategori eklendi!';
    } catch (PDOException $e) {
        $error = 'Ekleme hatası: ' . $e->getMessage();
    }
}

// Kategori durumu değiştirme
if (isset($_GET['toggle']) && isset($_GET['id'])) {
    try {
        $stmt = $pdo->prepare("UPDATE Categories SET IsActive = CASE WHEN IsActive = 1 THEN 0 ELSE 1 END WHERE CategoryID = ?");
        $stmt->execute([$_GET['id']]);
        $success = 'Kategori durumu güncellendi!';
    } catch (PDOException $e) {
        $error = 'Hata: ' . $e->getMessage();
    }
}

// Kategorileri getir
try {
    $stmt = $pdo->query("SELECT * FROM Categories ORDER BY Name");
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = $e->getMessage();
}

include '../includes/header.php';
?>

<div class="page-header">
    <h1>Kategori Yönetimi</h1>
    <p>Mutfak türlerini yönetin</p>
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
    <h3 style="margin-bottom: 24px;">Yeni Kategori Ekle</h3>
    
    <form method="POST" style="display: flex; gap: 12px; align-items: end;">
        <div class="form-group" style="flex: 1; margin-bottom: 0;">
            <label>Kategori Adı</label>
            <input type="text" name="name" class="form-control" placeholder="Örn: Pizza, Burger, Kahve" required>
        </div>
        <button type="submit" name="add_category" class="btn btn-primary">Ekle</button>
    </form>
</div>

<div class="card">
    <h3 style="margin-bottom: 24px;">Mevcut Kategoriler</h3>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 16px;">
        <?php foreach ($categories as $category): ?>
            <div style="border: 2px solid #e0e0e0; border-radius: 12px; padding: 20px; text-align: center;">
                <h4 style="font-size: 18px; margin-bottom: 12px;"><?= htmlspecialchars($category['Name']) ?></h4>
                <span class="badge <?= $category['IsActive'] ? 'badge-success' : 'badge-dark' ?>" style="margin-bottom: 12px;">
                    <?= $category['IsActive'] ? 'Aktif' : 'Pasif' ?>
                </span>
                <br>
                <a href="?toggle=1&id=<?= $category['CategoryID'] ?>" class="btn btn-warning btn-sm" style="margin-top: 8px;">
                    <?= $category['IsActive'] ? 'Pasif Yap' : 'Aktif Yap' ?>
                </a>
                <a href="categories.php?action=delete&id=<?= $category['CategoryID'] ?>"
                   onclick="return confirm('Bu kategori silinsin mi?')"
                   class="btn btn-danger btn-sm"
                   style="margin-top: 8px;">
                   Sil
                </a>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
