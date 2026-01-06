
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
ob_start();
session_name('restaurant_session');
$error = '';
try {
    require_once 'backend/config.php';
    require_once 'backend/auth_helper.php';
    startSession();
} catch (Exception $ex) {
    $error = $ex->getMessage();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    try {
        // E-posta ile kullanıcıyı bul (Status filtresi yok)
        $stmt = $pdo->prepare("SELECT UserID, FullName, Email, Password, UserType FROM Users WHERE Email = ? AND UserType = 'RestaurantOwner'");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user) {
            // Şifre kontrolü
            if (md5($password) === $user['Password']) {
                // Restoranı bul (Status filtresi olmadan)
                $stmtRest = $pdo->prepare("SELECT RestaurantID, Status FROM Restaurants WHERE OwnerUserID = ?");
                $stmtRest->execute([$user['UserID']]);
                $restaurant = $stmtRest->fetch(PDO::FETCH_ASSOC);
                
                // Debug: Restoran bulunup bulunmadığını kontrol et
                if (!$restaurant) {
                    // Gerçekten hiç restoran yok mu kontrol et
                    $debugStmt = $pdo->prepare("SELECT COUNT(*) as total FROM Restaurants WHERE OwnerUserID = ?");
                    $debugStmt->execute([$user['UserID']]);
                    $debugResult = $debugStmt->fetch(PDO::FETCH_ASSOC);
                    $error = 'Bu hesaba ait bir restoran bulunamadı. (UserID: ' . $user['UserID'] . ', Restoran Sayısı: ' . $debugResult['total'] . ')';
                } else {
                    $status = (int)$restaurant['Status'];
                    
                    // Status değerine göre işlem yap (0=Beklemede, 1=Onaylandı, 2=Reddedildi, 3=Askıya Alındı)
                    if ($status === 0) {
                        $error = 'Başvurunuz başvuru aşamasında beklemede.';
                    } elseif ($status === 1) {
                        // Giriş başarılı
                        $_SESSION['restaurant_id'] = $user['UserID'];
                        $_SESSION['restaurant_full_name'] = $user['FullName'];
                        $_SESSION['restaurant_email'] = $user['Email'];
                        $_SESSION['restaurant_user_type'] = $user['UserType'];
                        $_SESSION['restaurant_role'] = 'restaurant';  // Restaurant owner için role

                        header('Location: /restaurant/pages/dashboard.php');
                        ob_end_flush(); // Çıktı tamponunu boşalt
                        exit();
                    } elseif ($status === 2) {
                        $error = 'Yetkiniz yoktur (başvurunuz reddedildi).';
                    } elseif ($status === 3) {
                        $error = 'Hesabınız askıya alınmıştır.';
                    } else {
                        $error = 'Restoran durumu belirlenemiyor. (Status: ' . $status . ', Tip: ' . gettype($restaurant['Status']) . ')';
                    }
                }
            } else {
                $error = 'E-posta veya şifre hatalı!';
            }
        } else {
            $error = 'E-posta veya şifre hatalı!';
        }
    } catch (PDOException $e) {
        $error = 'Giriş hatası: ' . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restoran Girişi - Yemeksepeti</title>
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
        
        .login-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            width: 100%;
            max-width: 420px;
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
        
        .error-message {
            background: #fee;
            color: #c33;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
        }
        
        .btn-login {
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
        
        .btn-login:hover {
            transform: translateY(-2px);
        }
        
        .other-logins {
            margin-top: 30px;
            padding-top: 30px;
            border-top: 1px solid #e0e0e0;
            text-align: center;
        }
        
        .other-logins p {
            color: #666;
            margin-bottom: 15px;
            font-size: 14px;
        }
        
        .other-logins a {
            display: inline-block;
            margin: 0 10px;
            color: #f5576c;
            text-decoration: none;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="logo">
            <h1>Yemeksepeti</h1>
            <p>Restoran Sahibi Girişi</p>
        </div>
        
        <?php if ($error): ?>
            <div class="error-message"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        
        <form method="POST" action="/restaurant_login.php">
            <div class="form-group">
                <label>E-posta</label>
                <input type="email" name="email" required placeholder="restoran@email.com">
            </div>
            
            <div class="form-group">
                <label>Şifre</label>
                <input type="password" name="password" required placeholder="••••••••">
            </div>
            
            <button type="submit" class="btn-login">Giriş Yap</button>
        </form>
        
        <div style="text-align: center; margin-top: 20px;">
            <p style="color: #666; font-size: 14px;">Hesabınız yok mu?</p>
            <a href="/restaurant_register.php" style="color: #f5576c; text-decoration: none; font-weight: 600;">→ Restoran Kaydı Oluştur</a>
        </div>
        
        <div class="other-logins">
            <p>Farklı bir hesap türü mü arıyorsunuz?</p>
            <a href="/customer_login.php">Müşteri Girişi</a> |
            <a href="/admin_login.php">Yönetici Girişi</a>
        </div>
    </div>
</body>
</html>
