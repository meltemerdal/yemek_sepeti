<?php
require 'backend/config.php';
$pdo->exec("UPDATE Restaurants SET ImageURL = 'ustadonerci.jpg' WHERE RestaurantID = 38");
echo "Güncellendi: ustadonerci.jpg\n";
