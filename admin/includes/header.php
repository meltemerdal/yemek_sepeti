<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Admin Paneli' ?> - Yemeksepeti</title>
    <link rel="stylesheet" href="/admin/assets/css/style.css">
</head>
<body>
    <div class="layout">
        <aside class="sidebar">
            <div class="sidebar-header">
                <h2>Yemeksepeti</h2>
                <p class="user-name">Admin Panel</p>
            </div>
            
            <nav class="sidebar-nav">
                <a href="/admin/pages/dashboard.php" class="nav-item <?= $activePage === 'dashboard' ? 'active' : '' ?>">
                    <span class="icon">📊</span>
                    <span>Dashboard</span>
                </a>
                <a href="/admin/pages/users.php" class="nav-item <?= $activePage === 'users' ? 'active' : '' ?>">
                    <span class="icon">👥</span>
                    <span>Kullanıcılar</span>
                </a>
                <a href="/admin/pages/restaurants.php" class="nav-item <?= $activePage === 'restaurants' ? 'active' : '' ?>">
                    <span class="icon">🏪</span>
                    <span>Restoranlar</span>
                </a>
                <a href="/admin/pages/categories.php" class="nav-item <?= $activePage === 'categories' ? 'active' : '' ?>">
                    <span class="icon">📋</span>
                    <span>Kategoriler</span>
                </a>
                <a href="/admin/pages/orders.php" class="nav-item <?= $activePage === 'orders' ? 'active' : '' ?>">
                    <span class="icon">📦</span>
                    <span>Tüm Siparişler</span>
                </a>
                <a href="/admin/pages/applications.php" class="nav-item <?= $activePage === 'applications' ? 'active' : '' ?>">
                    <span class="icon">📝</span>
                    <span>Restoran Başvuruları</span>
                </a>
                <a href="/backend/logout.php" class="nav-item logout">
                    <span class="icon">🚪</span>
                    <span>Çıkış Yap</span>
                </a>
            </nav>
        </aside>
        
        <main class="main-content">
            <div class="content-wrapper">
