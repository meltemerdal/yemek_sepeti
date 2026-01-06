<?php
// Ana Sayfa - Yönlendirmeler
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yemek Sepeti - Hoş Geldiniz</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .container {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            max-width: 500px;
            width: 90%;
        }
        h1 {
            color: #333;
            text-align: center;
            margin-bottom: 30px;
        }
        .links {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        .link-btn {
            display: block;
            padding: 15px;
            background: #667eea;
            color: white;
            text-decoration: none;
            text-align: center;
            border-radius: 5px;
            transition: background 0.3s;
        }
        .link-btn:hover {
            background: #5568d3;
        }
        .link-btn.customer {
            background: #48bb78;
        }
        .link-btn.customer:hover {
            background: #38a169;
        }
        .link-btn.restaurant {
            background: #ed8936;
        }
        .link-btn.restaurant:hover {
            background: #dd6b20;
        }
        .link-btn.admin {
            background: #e53e3e;
        }
        .link-btn.admin:hover {
            background: #c53030;
        }
        .link-btn.frontend {
            background: #9f7aea;
        }
        .link-btn.frontend:hover {
            background: #805ad5;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🍕 Yemek Sepeti</h1>
        <div class="links">
            <a href="frontend/index.html" class="link-btn frontend">Ana Sayfa (Frontend)</a>
            <a href="customer_login.php" class="link-btn customer">Müşteri Girişi</a>
            <a href="restaurant_login.php" class="link-btn restaurant">Restoran Girişi</a>
            <a href="admin_login.php" class="link-btn admin">Admin Girişi</a>
        </div>
    </div>
</body>
</html>
