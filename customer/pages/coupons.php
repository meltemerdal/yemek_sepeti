<?php
session_name('customer_session');
session_start();

require_once '../../backend/config.php';
require_once '../../backend/auth_helper.php';

requireAuth('Customer');

$pageTitle = 'Kuponlarım';
$activePage = 'coupons';

include '../includes/header.php';
?>

<div class="page-header">
    <h1>🎟️ Kuponlarım</h1>
</div>

<div class="card">
    <p style="color: #666; text-align: center; padding: 40px;">Henüz kuponunuz bulunmuyor.</p>
</div>

<?php include '../includes/footer.php'; ?>
