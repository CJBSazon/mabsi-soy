<?php
$host = 'localhost';
$dbname = 'food_delivery';
$user = 'root'; // Update if necessary
$pass = ''; // Update if necessary

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
