<?php
session_name('customer_session');
session_start();
/**
 * Favoriler Sayfası
 * Kullanıcının favori restoranlarını gösterir
 */

// Kullanıcı kontrolü
if (!isset($_SESSION['user_id'])) {
    header('Location: /customer_login.php');
    exit;
}

$pageTitle = "Favorilerim";
$activePage = "favorites";
include '../includes/header.php';
?>

<style>
.favorites-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}

.page-header {
    margin-bottom: 30px;
}

.page-header h1 {
    font-size: 28px;
    color: #292929;
    margin-bottom: 8px;
}

.page-header p {
    color: #697488;
    font-size: 14px;
}

.empty-state {
    text-align: center;
    padding: 60px 20px;
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
}

.empty-icon {
    font-size: 64px;
    margin-bottom: 20px;
}

.empty-state h2 {
    font-size: 20px;
    color: #292929;
    margin-bottom: 12px;
}

.empty-state p {
    color: #697488;
    margin-bottom: 24px;
}

.restaurants-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 20px;
    margin-top: 20px;
}

.restaurant-card {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    transition: transform 0.2s, box-shadow 0.2s;
    cursor: pointer;
    position: relative;
}

.restaurant-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 4px 16px rgba(0,0,0,0.12);
}

.restaurant-image {
    width: 100%;
    height: 180px;
    object-fit: cover;
}

.restaurant-info {
    padding: 16px;
}

.restaurant-name {
    font-size: 18px;
    font-weight: 600;
    color: #292929;
    margin-bottom: 8px;
}

.restaurant-category {
    color: #697488;
    font-size: 14px;
    margin-bottom: 12px;
}

.restaurant-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 12px;
    border-top: 1px solid #f0f0f0;
}

.rating {
    display: flex;
    align-items: center;
    gap: 4px;
    color: #ffc700;
    font-weight: 600;
}

.delivery-time {
    color: #697488;
    font-size: 14px;
}

.remove-favorite {
    position: absolute;
    top: 12px;
    right: 12px;
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: white;
    border: none;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    transition: all 0.2s;
    z-index: 2;
}

.remove-favorite:hover {
    background: #fff0f3;
    transform: scale(1.1);
}

.remove-favorite svg {
    width: 20px;
    height: 20px;
    fill: #ff2d55;
}

.btn-primary {
    display: inline-block;
    padding: 12px 24px;
    background: #ff2d55;
    color: white;
    text-decoration: none;
    border-radius: 8px;
    font-weight: 600;
    transition: background 0.2s;
}

.btn-primary:hover {
    background: #e6294d;
}

.loading {
    text-align: center;
    padding: 40px;
    color: #697488;
}
</style>

<div class="favorites-container">
    <div class="page-header">
        <h1>❤️ Favorilerim</h1>
        <p>Beğendiğiniz restoranlar</p>
    </div>

    <div id="favoritesContent">
        <div class="loading">Favoriler yükleniyor...</div>
    </div>
</div>

<script>
let favorites = [];
let restaurants = [];

// Sayfa yüklendiğinde favorileri getir
document.addEventListener('DOMContentLoaded', function() {
    loadFavorites();
});

async function loadFavorites() {
    try {
        // Veritabanından favori restaurant ID'lerini al
        const favResponse = await fetch('/backend/get_favorites.php');
        const favData = await favResponse.json();
        
        if (!favData.success) {
            console.error('Favori yükleme hatası:', favData.error);
            showEmptyState();
            return;
        }
        
        favorites = favData.favorites.map(f => parseInt(f.RestaurantID));
        
        console.log('Favoriler:', favorites);
        
        if (favorites.length === 0) {
            showEmptyState();
            return;
        }
        
        // Tüm restoranları getir
        const restaurantsResponse = await fetch('/backend/restaurants.php?action=list');
        const allRestaurants = await restaurantsResponse.json();
        
        console.log('Tüm restoranlar:', allRestaurants.length);
        
        // Sadece favorilerde olanları filtrele
        restaurants = allRestaurants.filter(r => favorites.includes(parseInt(r.RestaurantID)));
        
        console.log('Filtrelenmiş restoranlar:', restaurants);
        
        displayFavorites();
    } catch (error) {
        console.error('Error loading favorites:', error);
        document.getElementById('favoritesContent').innerHTML = 
            '<div class="empty-state"><p>Favoriler yüklenirken hata oluştu.</p></div>';
    }
}

function showEmptyState() {
    document.getElementById('favoritesContent').innerHTML = `
        <div class="empty-state">
            <div class="empty-icon">💔</div>
            <h2>Henüz favori restoranınız yok</h2>
            <p>Beğendiğiniz restoranları favorilere ekleyerek kolayca ulaşabilirsiniz</p>
            <a href="/frontend/index.html" class="btn-primary">Restoran Keşfet</a>
        </div>
    `;
}

function displayFavorites() {
    let html = '<div class="restaurants-grid">';
    
    restaurants.forEach(restaurant => {
        // Önce ImageURL varsa onu kullan
        let imageUrl = (restaurant.ImageURL && restaurant.ImageURL.trim() !== '') ? restaurant.ImageURL.trim() : '';
        if (imageUrl) {
            // Eğer yol başında / yoksa otomatik olarak /frontend/images/ ekle
            if (!imageUrl.startsWith('/')) {
                imageUrl = '/frontend/images/' + imageUrl;
            }
        } else {
            // Fallback: anahtar kelimeye göre görsel
            imageUrl = '/frontend/images/yemek.jpg';
            const name = restaurant.Name.toLowerCase();
            if (name.includes('musqa') || (name.includes('burger') && name.includes('mng'))) {
                imageUrl = '/frontend/images/burger.jpg';
            } else if (name.includes('usta') && name.includes('dönerci')) {
                imageUrl = '/frontend/images/ustadonerci.jpg';
            } else if (name.includes('domino')) {
                imageUrl = '/frontend/images/dominospizza.jpg';
            } else if (name.includes('pizza')) {
                imageUrl = '/frontend/images/pizza.jpg';
            } else if (name.includes('kebap') || name.includes('döner')) {
                imageUrl = '/frontend/images/kebap.jpg';
            } else if (name.includes('sushi') || name.includes('balık')) {
                imageUrl = '/frontend/images/sushi.jpg';
            } else if (name.includes('kahve') || name.includes('coffee')) {
                imageUrl = '/frontend/images/kahve.jpg';
            } else if (name.includes('tatlı') || name.includes('jamal')) {
                imageUrl = '/frontend/images/tatli.jpg';
            }
        }
        html += `
            <div class="restaurant-card" onclick="goToRestaurant(${restaurant.RestaurantID})">
                <button class="remove-favorite" onclick="event.stopPropagation(); removeFavorite(${restaurant.RestaurantID})">
                    <svg viewBox="0 0 24 24">
                        <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                    </svg>
                </button>
                <img src="${imageUrl}" alt="${restaurant.Name}" class="restaurant-image" onerror="this.src='/frontend/images/yemek.jpg'">
                <div class="restaurant-info">
                    <div class="restaurant-name">${restaurant.Name}</div>
                    <div class="restaurant-category">📍 ${restaurant.Category}</div>
                    <div class="restaurant-meta">
                        <span class="rating">
                            ⭐ ${restaurant.Rating || '4.5'}
                        </span>
                        <span class="delivery-time">
                            🕐 ${restaurant.DeliveryTime || '30'} dk
                        </span>
                    </div>
                </div>
            </div>
        `;
    });
    
    html += '</div>';
    document.getElementById('favoritesContent').innerHTML = html;
}

async function removeFavorite(restaurantId) {
    try {
        const response = await fetch('/backend/toggle_favorite.php', {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `restaurant_id=${restaurantId}`
        });
        
        const data = await response.json();
        
        if (data.success) {
            // Listeyi yeniden yükle
            loadFavorites();
            
            // Bildirim göster
            showNotification('💔 Favorilerden çıkarıldı');
        } else {
            showNotification('İşlem başarısız: ' + (data.error || 'Bilinmeyen hata'));
        }
    } catch (error) {
        console.error('Favori çıkarma hatası:', error);
        showNotification('Bir hata oluştu. Lütfen tekrar deneyin.');
    function showNotification(message) {
        const notif = document.createElement('div');
        notif.className = 'custom-notification';
        notif.textContent = message;
        document.body.appendChild(notif);
        setTimeout(() => {
            notif.remove();
        }, 2000);
    }
    }
}

function goToRestaurant(restaurantId) {
    window.location.href = `/frontend/restaurant.html?id=${restaurantId}`;
}

function showNotification(message) {
    // Basit bir bildirim sistemi
    const notification = document.createElement('div');
    notification.textContent = message;
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: #292929;
        color: white;
        padding: 16px 24px;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.3);
        z-index: 10000;
        animation: slideIn 0.3s ease-out;
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.animation = 'slideOut 0.3s ease-out';
        setTimeout(() => notification.remove(), 300);
    }, 2000);
}
</script>

<style>
@keyframes slideIn {
    from {
        transform: translateX(400px);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

@keyframes slideOut {
    from {
        transform: translateX(0);
        opacity: 1;
    }
    to {
        transform: translateX(400px);
        opacity: 0;
    }
}
</style>

<?php include '../includes/footer.php'; ?>
