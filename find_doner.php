<?php
require 'backend/config.php';
$stmt = $pdo->query("SELECT RestaurantID, Name, Category, Rating, DeliveryTime, MinOrderAmount, Address, Phone 
                     FROM Restaurants 
                     WHERE Name LIKE '%döner%' OR Name LIKE '%Döner%' OR Category LIKE '%döner%' OR Category LIKE '%Döner%'");
echo "Döner Restoranları:\n\n";
while($row = $stmt->fetch()) {
    echo "ID: " . $row['RestaurantID'] . "\n";
    echo "İsim: " . $row['Name'] . "\n";
    echo "Kategori: " . $row['Category'] . "\n";
    echo "Puan: " . $row['Rating'] . "\n";
    echo "Teslimat: " . $row['DeliveryTime'] . " dk\n";
    echo "Min. Sipariş: " . $row['MinOrderAmount'] . " ₺\n";
    echo "Adres: " . $row['Address'] . "\n";
    echo "Telefon: " . $row['Phone'] . "\n";
    echo "-------------------\n";
}
