<?php
session_name('customer_session');
session_start();

require_once '../../backend/config.php';
require_once '../../backend/auth_helper.php';

requireAuth('Customer');

$pageTitle = 'Profilim';
$activePage = 'profile';

$success = '';
$error = '';

// Kullanıcı bilgilerini al
try {
    $stmt = $pdo->prepare("SELECT FullName, Email, Phone FROM Users WHERE UserID = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = $e->getMessage();
}

// Form gönderimi
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullName = $_POST['full_name'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $newPassword = $_POST['new_password'] ?? '';
    
    try {
        if (!empty($newPassword)) {
            $stmt = $pdo->prepare("UPDATE Users SET FullName = ?, Phone = ?, Password = ? WHERE UserID = ?");
            $stmt->execute([$fullName, $phone, $newPassword, $_SESSION['user_id']]);
        } else {
            $stmt = $pdo->prepare("UPDATE Users SET FullName = ?, Phone = ? WHERE UserID = ?");
            $stmt->execute([$fullName, $phone, $_SESSION['user_id']]);
        }
        
        $_SESSION['full_name'] = $fullName;
        $success = 'Profil bilgileriniz güncellendi!';
        $user['FullName'] = $fullName;
        $user['Phone'] = $phone;
    } catch (PDOException $e) {
        $error = 'Güncelleme hatası: ' . $e->getMessage();
    }
}

include '../includes/header.php';
?>

<div class="page-header">
    <h1>Profilim</h1>
    <p>Hesap bilgilerinizi yönetin</p>
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
    <h3 style="margin-bottom: 24px;">Bilgilerimi Güncelle</h3>
    
    <form method="POST">
        <div class="form-group">
            <label>Ad Soyad</label>
            <input type="text" name="full_name" class="form-control" value="<?= htmlspecialchars($user['FullName']) ?>" required>
        </div>
        
        <div class="form-group">
            <label>E-posta</label>
            <input type="email" class="form-control" value="<?= htmlspecialchars($user['Email']) ?>" disabled>
            <small style="color: #666; font-size: 13px;">E-posta adresi değiştirilemez</small>
        </div>
        
        <div class="form-group">
            <label>Telefon</label>
            <input type="tel" name="phone" class="form-control" value="<?= htmlspecialchars($user['Phone']) ?>" required>
        </div>
        
        <div class="form-group">
            <label>Yeni Şifre (opsiyonel)</label>
            <input type="password" name="new_password" class="form-control" placeholder="Boş bırakırsanız şifreniz değişmez">
        </div>
        
        <button type="submit" class="btn btn-primary">Güncelle</button>
    </form>
</div>

<?php include '../includes/footer.php'; ?>
