<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Restoran Paneli' ?> - Yemeksepeti</title>
    <link rel="stylesheet" href="/restaurant/assets/css/style.css">
</head>
<body>
    <div class="layout">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <h2>Yemeksepeti</h2>
                <p class="user-name"><?= htmlspecialchars($_SESSION['full_name']) ?></p>
            </div>
            
            <nav class="sidebar-nav">
                <a href="/restaurant/pages/dashboard.php" class="nav-item <?= $activePage === 'dashboard' ? 'active' : '' ?>">
                    <span class="icon">📊</span>
                    <span>Dashboard</span>
                </a>
                <a href="/restaurant/pages/info.php" class="nav-item <?= $activePage === 'info' ? 'active' : '' ?>">
                    <span class="icon">🏪</span>
                    <span>Restoran Bilgileri</span>
                </a>
                <a href="/restaurant/pages/menu.php" class="nav-item <?= $activePage === 'menu' ? 'active' : '' ?>">
                    <span class="icon">🍽️</span>
                    <span>Menü Yönetimi</span>
                </a>
                <a href="/restaurant/pages/orders.php" class="nav-item <?= $activePage === 'orders' ? 'active' : '' ?>">
                    <span class="icon">📦</span>
                    <span>Siparişler</span>
                </a>
                <a href="/restaurant/pages/reports.php" class="nav-item <?= $activePage === 'reports' ? 'active' : '' ?>">
                    <span class="icon">📈</span>
                    <span>Raporlar</span>
                </a>
                <a href="/backend/logout.php" class="nav-item logout">
                    <span class="icon">🚪</span>
                    <span>Çıkış Yap</span>
                </a>
            </nav>
        </aside>
        
        <!-- Main Content -->
        <main class="main-content">
            <div class="content-wrapper">
