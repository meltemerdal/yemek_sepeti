<?php
session_name('customer_session');
session_start();

require_once '../../backend/config.php';
require_once '../../backend/auth_helper.php';

requireAuth('Customer');

$pageTitle = 'Yardım Merkezi';
$activePage = 'help';

include '../includes/header.php';
?>

<div class="page-header">
    <h1>❓ Yardım Merkezi</h1>
</div>

<div class="card">
    <h3>Sık Sorulan Sorular</h3>
    
    <div style="margin-top: 20px;">
        <details style="margin-bottom: 15px;">
            <summary style="cursor: pointer; font-weight: 600; padding: 10px; background: #f5f5f5; border-radius: 5px;">Sipariş nasıl veririm?</summary>
            <p style="padding: 15px; color: #666;">Restoran seçin, menüden ürünleri sepete ekleyin ve ödeme adımlarını tamamlayın.</p>
        </details>
        
        <details style="margin-bottom: 15px;">
            <summary style="cursor: pointer; font-weight: 600; padding: 10px; background: #f5f5f5; border-radius: 5px;">Siparişimi nasıl iptal edebilirim?</summary>
            <p style="padding: 15px; color: #666;">Önceki Siparişlerim sayfasından siparişinizi iptal edebilirsiniz.</p>
        </details>
        
        <details style="margin-bottom: 15px;">
            <summary style="cursor: pointer; font-weight: 600; padding: 10px; background: #f5f5f5; border-radius: 5px;">Ödeme yöntemleri nelerdir?</summary>
            <p style="padding: 15px; color: #666;">Kredi kartı, banka kartı ve kapıda ödeme seçenekleri mevcuttur.</p>
        </details>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
