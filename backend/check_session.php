<?php
session_name('customer_session');
require_once 'config.php';
require_once 'auth_helper.php';

header('Content-Type: application/json');

startSession();

if (isset($_SESSION['user_id'])) {
    echo json_encode([
        'loggedIn' => true,
        'user' => [
            'UserID' => $_SESSION['user_id'],
            'FullName' => $_SESSION['full_name'] ?? '',
            'Email' => $_SESSION['email'] ?? '',
            'UserType' => $_SESSION['user_type'] ?? 'Customer'
        ]
    ]);
} else {
    echo json_encode(['loggedIn' => false]);
}
