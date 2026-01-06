<?php
require_once 'config.php';

$stmt = $pdo->query("
    SELECT CategoryID, Name
    FROM Categories
    WHERE IsActive = 1
    ORDER BY Name
");

echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
