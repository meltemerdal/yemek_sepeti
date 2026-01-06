<?php

ob_start();
session_name('admin_session');
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
        $stmt = $pdo->prepare("SELECT UserID, FullName, Email, Password, UserType FROM Users WHERE Email = ? AND UserType = 'Admin' AND IsActive = 1");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user && md5($password) === $user['Password']) {
            $_SESSION['admin_id'] = $user['UserID'];
            $_SESSION['admin_full_name'] = $user['FullName'];
            $_SESSION['admin_email'] = $user['Email'];
            $_SESSION['admin_user_type'] = $user['UserType'];
            $_SESSION['admin_role'] = 'admin'; // 🔴 delete_restaurant.php için ZORUNLU
            $_SESSION['role'] = 'admin'; // Silme işlemi için gerekli anahtar
            // Tüm admin paneliyle uyumlu anahtarlar
            $_SESSION['user_id'] = $user['UserID'];
            $_SESSION['full_name'] = $user['FullName'];
            $_SESSION['email'] = $user['Email'];
            $_SESSION['user_type'] = $user['UserType'];
            
            header('Location: /admin/pages/dashboard.php');
            ob_end_flush();
            exit();
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
    <title>Yönetici Girişi - Yemeksepeti</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: linear-gradient(135deg, #0f2027 0%, #203a43 50%, #2c5364 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .login-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
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
            font-weight: 600;
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
            border-color: #2c5364;
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
            background: linear-gradient(135deg, #0f2027 0%, #2c5364 100%);
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
            color: #2c5364;
            text-decoration: none;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="logo">
            <h1>Yemeksepeti</h1>
            <p>Sistem Yöneticisi Girişi</p>
        </div>
        
        <?php if ($error): ?>
            <div class="error-message"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label>E-posta</label>
                <input type="email" name="email" required placeholder="admin@yemeksepeti.com">
            </div>
            
            <div class="form-group">
                <label>Şifre</label>
                <input type="password" name="password" required placeholder="••••••••">
            </div>
            
            <button type="submit" class="btn-login">Giriş Yap</button>
        </form>
        
        <div class="other-logins">
            <p>Farklı bir hesap türü mü arıyorsunuz?</p>
            <a href="/customer_login.php">Müşteri Girişi</a> |
            <a href="/restaurant_login.php">Restoran Girişi</a>
        </div>
    </div>
</body>
</html>
