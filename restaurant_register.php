<?php
require_once 'backend/config.php';
require_once 'backend/auth_helper.php';

startSession();

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $restaurantName = trim($_POST['restaurant_name'] ?? '');
    $ownerName = trim($_POST['owner_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $hashedPassword = md5($password);
    $phone = trim($_POST['phone'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $termsAccepted = isset($_POST['terms_accepted']);
    
    // Validasyon
    if (empty($restaurantName) || empty($ownerName) || empty($email) || empty($password) || empty($phone)) {
        $error = 'Lütfen tüm alanları doldurun!';
    } elseif (!$termsAccepted) {
        $error = 'Aydınlatma metnini onaylamanız gerekiyor!';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Geçerli bir e-posta adresi girin!';
    } else {
        try {
            // E-posta kontrolü
            $stmt = $pdo->prepare("SELECT UserID FROM Users WHERE Email = ?");
            $stmt->execute([$email]);
            if ($stmt->fetch()) {
                $error = 'Bu e-posta adresi zaten kullanılıyor!';
            } else {
                // Kullanıcı oluştur
                $stmt = $pdo->prepare("INSERT INTO Users (FullName, Email, Password, Phone, UserType, IsActive) VALUES (?, ?, ?, ?, 'RestaurantOwner', 1)");
                $stmt->execute([$ownerName, $email, $hashedPassword, $phone]);
                
                // Eklenen kullanıcının UserID'sini tekrar sorgulayarak al
                $stmt = $pdo->prepare("SELECT UserID FROM Users WHERE Email = ? AND UserType = 'RestaurantOwner'");
                $stmt->execute([$email]);
                $userRow = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if (!$userRow) {
                    throw new Exception('Kullanıcı kaydı oluşturulamadı!');
                }
                
                $userID = $userRow['UserID'];
                
                // Restoran oluştur (Status = 0: Beklemede)
                $stmt = $pdo->prepare("INSERT INTO Restaurants (Name, Category, Address, Phone, DeliveryTime, OwnerUserID, Status) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$restaurantName, $category, $address, $phone, 30, $userID, 0]);
                $restaurantID = $pdo->lastInsertId();
                
                if (!$restaurantID) {
                    throw new Exception('Restoran kaydı oluşturulamadı!');
                }
                
                $success = 'Kayıt başarılı! Başvurunuz incelendikten sonra giriş yapabileceksiniz. Yönlendiriliyorsunuz...';
                header('refresh:3;url=/restaurant_login.php');
            }
        } catch (PDOException $e) {
            $error = 'Kayıt hatası: ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restoran Kaydı - Yemeksepeti</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .register-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            width: 100%;
            max-width: 480px;
            padding: 40px;
        }
        
        .logo {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .logo h1 {
            color: #ff2d55;
            font-size: 32px;
            margin-bottom: 10px;
        }
        
        .logo p {
            color: #666;
            font-size: 16px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 500;
        }
        
        .form-group input {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 15px;
            transition: border-color 0.3s;
        }
        
        .form-group input:focus {
            outline: none;
            border-color: #f5576c;
        }
        
        .checkbox-group {
            margin-bottom: 25px;
            padding: 15px;
            background: #f9f9f9;
            border-radius: 10px;
            border: 2px solid #e0e0e0;
        }
        
        .checkbox-group label {
            display: flex;
            align-items: flex-start;
            cursor: pointer;
            font-size: 14px;
            color: #333;
        }
        
        .checkbox-group input[type="checkbox"] {
            width: auto;
            margin-right: 10px;
            margin-top: 3px;
            cursor: pointer;
            transform: scale(1.2);
        }
        
        .error-message {
            background: #fee;
            color: #c33;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
        }
        
        .success-message {
            background: #efe;
            color: #3c3;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
        }
        
        .btn-register {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s;
        }
        
        .btn-register:hover {
            transform: translateY(-2px);
        }
        
        .btn-register:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }
        
        .back-link {
            text-align: center;
            margin-top: 20px;
        }
        
        .back-link a {
            color: #f5576c;
            text-decoration: none;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="logo">
            <h1>Yemeksepeti</h1>
            <p>Restoran Kayıt Formu</p>
        </div>
        
        <?php if ($error): ?>
            <div class="error-message"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="success-message"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>
        
        <form method="POST" id="registerForm">
            <div class="form-group">
                <label>Restoran Adı <span style="color: red;">*</span></label>
                <input type="text" name="restaurant_name" required placeholder="Örn: Lezzet Restaurant" value="<?= htmlspecialchars($_POST['restaurant_name'] ?? '') ?>">
            </div>
            
            <div class="form-group">
                <label>Restoran Sahibinin Adı Soyadı <span style="color: red;">*</span></label>
                <input type="text" name="owner_name" required placeholder="Örn: Ahmet Yılmaz" value="<?= htmlspecialchars($_POST['owner_name'] ?? '') ?>">
            </div>
            
            <div class="form-group">
                <label>E-posta <span style="color: red;">*</span></label>
                <input type="email" name="email" required placeholder="restoran@email.com" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
            </div>
            
            <div class="form-group">
                <label>Şifre <span style="color: red;">*</span></label>
                <input type="password" name="password" required placeholder="••••••••" minlength="6">
            </div>
            
            <div class="form-group">
                <label>Telefon <span style="color: red;">*</span></label>
                <input type="tel" name="phone" required placeholder="0555 123 45 67" value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>">
            </div>
            
            <div class="form-group">
                <label>Restoran Adresi <span style="color: red;">*</span></label>
                <textarea name="address" rows="3" required placeholder="Mahalle, sokak, bina no, ilçe / il"><?= htmlspecialchars($_POST['address'] ?? '') ?></textarea>
            </div>
            
            <div class="form-group">
                <label>Restoran Kategorisi <span style="color: red;">*</span></label>
                <select name="category" required>
                    <option value="">Seçiniz</option>
                    <option value="Balık ve Deniz Ürünleri" <?= ($_POST['category'] ?? '') == 'Balık ve Deniz Ürünleri' ? 'selected' : '' ?>>Balık ve Deniz Ürünleri</option>
                    <option value="Burger" <?= ($_POST['category'] ?? '') == 'Burger' ? 'selected' : '' ?>>Burger</option>
                    <option value="Döner" <?= ($_POST['category'] ?? '') == 'Döner' ? 'selected' : '' ?>>Döner</option>
                    <option value="Dünya Mutfağı" <?= ($_POST['category'] ?? '') == 'Dünya Mutfağı' ? 'selected' : '' ?>>Dünya Mutfağı</option>
                    <option value="Ev Yemekleri" <?= ($_POST['category'] ?? '') == 'Ev Yemekleri' ? 'selected' : '' ?>>Ev Yemekleri</option>
                    <option value="Kahvaltı & Börek" <?= ($_POST['category'] ?? '') == 'Kahvaltı & Börek' ? 'selected' : '' ?>>Kahvaltı & Börek</option>
                    <option value="Kahve" <?= ($_POST['category'] ?? '') == 'Kahve' ? 'selected' : '' ?>>Kahve</option>
                    <option value="Pizza" <?= ($_POST['category'] ?? '') == 'Pizza' ? 'selected' : '' ?>>Pizza</option>
                    <option value="Çiğ Köfte" <?= ($_POST['category'] ?? '') == 'Çiğ Köfte' ? 'selected' : '' ?>>Çiğ Köfte</option>
                    <option value="Tatlı & Kahve" <?= ($_POST['category'] ?? '') == 'Tatlı & Kahve' ? 'selected' : '' ?>>Tatlı & Kahve</option>
                </select>
            </div>
            
            <div class="checkbox-group">
                <label>
                    <input type="checkbox" name="terms_accepted" id="terms_accepted" required>
                    <span><strong>Aydınlatma metnini okudum, onaylıyorum</strong> <span style="color: red;">*</span></span>
                </label>
            </div>
            
            <button type="submit" class="btn-register">Kayıt Ol</button>
        </form>
        
        <div class="back-link">
            <a href="/restaurant_login.php">← Giriş sayfasına dön</a>
        </div>
    </div>
    
    <script>
        // Checkbox kontrolü
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            const checkbox = document.getElementById('terms_accepted');
            if (!checkbox.checked) {
                e.preventDefault();
                alert('Lütfen aydınlatma metnini onaylayın!');
                return false;
            }
        });
    </script>
</body>
</html>
