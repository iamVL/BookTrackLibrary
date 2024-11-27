<?php
$host = 'localhost';
$db = 'booktrack_library';
$user = 'your_postgres_username';
$password = 'your_postgres_password';

$dsn = "pgsql:host=$host;port=5432;dbname=$db;";

try {
    $pdo = new PDO($dsn, $user, $password, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    echo 'Connected successfully';
} catch (PDOException $e) {
    die('Connection failed: ' . $e->getMessage());
}
?>
