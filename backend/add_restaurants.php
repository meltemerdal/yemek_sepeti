<?php
require_once 'config.php';

$restaurants = array(
    array(1, 'Konoha Sushi & Chinese', 1, 150, '25-35 dk', 4.5, 'frontend/images/konoha.jpg'),
    array(2, 'Asaka Sushi & Chinese', 1, 180, '30-40 dk', 4.6, 'frontend/images/asaka.jpg'),
    array(3, 'Souper', 1, 120, '25-35 dk', 4.3, 'frontend/images/souper.jpg'),
    array(4, 'I Love Fish', 1, 200, '35-45 dk', 4.7, 'frontend/images/ilovefish.jpg'),
    array(5, 'Noodle Saka Chef', 1, 110, '20-30 dk', 4.4, 'frontend/images/noodlesakachef.jpg'),
    array(6, 'Balıkçı Hasan Usta', 1, 250, '35-50 dk', 4.5, 'frontend/images/balikcihasanusta.jpg')
);

echo "<h2>Restoranlar Ekleniyor...</h2>";

foreach ($restaurants as $restaurant) {
    $sql = "INSERT INTO Restaurants (RestaurantID, Name, CuisineID, MinPrice, DeliveryTime, Rating, ImageURL) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = sqlsrv_query($conn, $sql, $restaurant);
    
    if ($stmt === false) {
        echo "<p style='color: red;'>❌ Hata: " . $restaurant[1] . " eklenemedi!</p>";
        echo "<pre>" . print_r(sqlsrv_errors(), true) . "</pre>";
    } else {
        echo "<p style='color: green;'>✅ Başarılı: " . $restaurant[1] . " eklendi!</p>";
        sqlsrv_free_stmt($stmt);
    }
}

closeConnection($conn);
echo "<h3>İşlem tamamlandı!</h3>";
echo "<p><a href='../frontend/index.html'>Ana sayfaya dön</a></p>";
?>
