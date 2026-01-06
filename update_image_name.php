<?php
require 'backend/config.php';

$sql = "UPDATE Restaurants SET ImageURL = 'usta-donerci-logo.jpg' WHERE RestaurantID = 38";
$pdo->exec($sql);

echo "Görsel güncellendi: usta-donerci-logo.jpg\n";
