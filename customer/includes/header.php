<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Müşteri Paneli' ?> - Yemeksepeti</title>
    <link rel="stylesheet" href="/customer/assets/css/style.css">
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
                <a href="/customer/pages/dashboard.php" class="nav-item <?= $activePage === 'dashboard' ? 'active' : '' ?>">
                    <span class="icon">🏠</span>
                    <span>Ana Sayfa</span>
                </a>
                <a href="/customer/pages/profile.php" class="nav-item <?= $activePage === 'profile' ? 'active' : '' ?>">
                    <span class="icon">👤</span>
                    <span>Profilim</span>
                </a>
                <a href="/customer/pages/addresses.php" class="nav-item <?= $activePage === 'addresses' ? 'active' : '' ?>">
                    <span class="icon">📍</span>
                    <span>Adreslerim</span>
                </a>
                <a href="/customer/pages/orders.php" class="nav-item <?= $activePage === 'orders' ? 'active' : '' ?>">
                    <span class="icon">📦</span>
                    <span>Siparişlerim</span>
                </a>
                <a href="/customer/pages/favorites.php" class="nav-item <?= $activePage === 'favorites' ? 'active' : '' ?>">
                    <span class="icon">❤️</span>
                    <span>Favorilerim</span>
                </a>
                <a href="/frontend/index.html" class="nav-item">
                    <span class="icon">🍽️</span>
                    <span>Restoran Ara</span>
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
