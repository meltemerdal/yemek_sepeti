<?php
session_name('customer_session');
session_start();

require_once '../../backend/config.php';
require_once '../../backend/auth_helper.php';

requireAuth('Customer');

$pageTitle = 'Cüzdan';
$activePage = 'wallet';

include '../includes/header.php';
?>

<div class="page-header">
    <h1>💰 Cüzdanım</h1>
</div>

<div class="card">
    <div style="text-align: center; padding: 40px;">
        <h2 style="color: #d71f4b; font-size: 48px; margin-bottom: 10px;">₺0,00</h2>
        <p style="color: #666;">Mevcut Bakiye</p>
    </div>
</div>

<div class="card" style="margin-top: 20px;">
    <h3>İşlem Geçmişi</h3>
    <p style="color: #666; text-align: center; padding: 40px;">Henüz işlem bulunmuyor.</p>
</div>

<?php include '../includes/footer.php'; ?>
