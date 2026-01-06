<?php
ob_start();
session_name('customer_session');
$error = '';
try {
    require_once 'backend/config.php';
    require_once 'backend/auth_helper.php';
    startSession();
} catch (Exception $ex) {
    $error = $ex->getMessage();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // ...
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    $query = "SELECT UserID, FullName, Email, Password FROM Users WHERE Email = ? AND IsActive = 1";
    $params = array($email);
    $stmt = sqlsrv_query($conn, $query, $params);
    
    if ($stmt === false) {
        $error = 'Veritabanı hatası: ' . print_r(sqlsrv_errors(), true);
    } else {
        $user = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
        
        if ($user && md5($password) === $user['Password']) {
            // Hem eski hem yeni anahtarlar yazılsın
            $_SESSION['customer_id'] = $user['UserID'];
            $_SESSION['customer_full_name'] = $user['FullName'];
            $_SESSION['customer_email'] = $user['Email'];
            $_SESSION['customer_user_type'] = 'Customer';
            // Tüm müşteri paneliyle uyumlu anahtarlar
            $_SESSION['user_id'] = $user['UserID'];
            $_SESSION['full_name'] = $user['FullName'];
            $_SESSION['email'] = $user['Email'];
            $_SESSION['user_type'] = 'Customer';
            
            // JavaScript ile localStorage'a kaydet
            $userJson = json_encode([
                'UserID' => $user['UserID'],
                'FullName' => $user['FullName'],
                'Email' => $user['Email'],
                'UserType' => 'Customer'
            ]);
            echo "<script>
                localStorage.setItem('user', '" . addslashes($userJson) . "');
                window.location.href = 'customer/pages/dashboard.php';
            </script>";
            ob_end_flush();
            exit();
        } else {
            $error = 'Email veya şifre hatalı';
        }
        
        sqlsrv_free_stmt($stmt);
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Müşteri Girişi - Yemeksepeti</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
            border-color: #667eea;
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
        
        .links {
            text-align: center;
            margin-top: 20px;
        }
        
        .links a {
            color: #667eea;
            text-decoration: none;
            font-size: 14px;
        }
        
        .links a:hover {
            text-decoration: underline;
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
            color: #667eea;
            text-decoration: none;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="logo">
            <h1>Yemeksepeti</h1>
            <p>Müşteri Girişi</p>
        </div>
        
        <?php if ($error): ?>
            <div class="error-message"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label>E-posta</label>
                <input type="email" name="email" required placeholder="ornek@email.com">
            </div>
            
            <div class="form-group">
                <label>Şifre</label>
                <input type="password" name="password" required placeholder="••••••••">
            </div>
            
            <button type="submit" class="btn-login">Giriş Yap</button>
        </form>
        
        <div class="links">
            <a href="/frontend/register.html">Hesabınız yok mu? Kayıt olun</a>
        </div>
        
        <div class="other-logins">
            <p>Farklı bir hesap türü mü arıyorsunuz?</p>
            <a href="/restaurant_login.php">Restoran Girişi</a> |
            <a href="/admin_login.php">Yönetici Girişi</a>
        </div>
    </div>
</body>
</html>
