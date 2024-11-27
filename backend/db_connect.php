<?php
// db_connect.php

$host = 'localhost';
$db = 'booktrack_library';
$user = 'sathwin';    
$password = 'sathwin'; 

try {
    $dsn = "pgsql:host=$host;port=5432;dbname=$db;";
    // Use options to handle errors and exceptions
    $pdo = new PDO($dsn, $user, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch (PDOException $e) {
    // Handle connection errors
    echo 'Connection failed: ' . $e->getMessage();
    exit();
}
?>
