<?php
// Auth helper fonksiyonları

// Session başlat
function startSession() {
    if (session_status() == PHP_SESSION_NONE) {
        // Session ayarlarını başlatmadan önce yap
        ini_set('session.gc_maxlifetime', 7200); // 2 saat
        ini_set('session.cookie_lifetime', 7200); // 2 saat
        session_set_cookie_params(7200);
        
        session_start();
    }
}

// Giriş kontrolü

function checkAuth($userType = null) {
    startSession();
    // Panel tipine göre doğru session anahtarını kontrol et
    if ($userType === 'Admin') {
        if (!isset($_SESSION['admin_id'])) return false;
        if ($userType && (!isset($_SESSION['admin_user_type']) || $_SESSION['admin_user_type'] !== $userType)) return false;
    } elseif ($userType === 'RestaurantOwner' || $userType === 'Restaurant') {
        if (!isset($_SESSION['restaurant_id'])) return false;
        if ($userType && (!isset($_SESSION['restaurant_user_type']) || $_SESSION['restaurant_user_type'] !== 'RestaurantOwner')) return false;
    } else { // Customer
        if (!isset($_SESSION['customer_id'])) return false;
        if ($userType && (!isset($_SESSION['customer_user_type']) || $_SESSION['customer_user_type'] !== 'Customer')) return false;
    }
    return true;
}

// Kullanıcı çıkışı
function logout() {
    // Panel bazlı session_name ayarı
    $uri = $_SERVER['REQUEST_URI'] ?? '';
    if (strpos($uri, 'admin') !== false) {
        session_name('admin_session');
    } elseif (strpos($uri, 'restaurant') !== false) {
        session_name('restaurant_session');
    } else {
        session_name('customer_session');
    }
    startSession();
    session_unset();
    session_destroy();
}

// Redirect fonksiyonu
function redirect($url) {
    header("Location: " . $url);
    exit();
}

// Yetkisiz erişim kontrolü
function requireAuth($userType = null) {
    if (!checkAuth($userType)) {
        // Kullanıcı tipine göre yönlendir
        if ($userType === 'Admin') {
            header('Location: /admin_login.php');
        } elseif ($userType === 'RestaurantOwner' || $userType === 'Restaurant') {
            header('Location: /restaurant_login.php');
        } else {
            header('Location: /customer_login.php');
        }
        exit();
    }
}

// Kullanıcı bilgilerini al
function getUserInfo() {
    startSession();
    return [
        'user_id' => $_SESSION['user_id'] ?? null,
        'full_name' => $_SESSION['full_name'] ?? null,
        'email' => $_SESSION['email'] ?? null,
        'user_type' => $_SESSION['user_type'] ?? null
    ];
}
?>
