<?php
// get_all_books.php

session_start();
include 'db_connect.php';

// Ensure the user is logged in as a librarian
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'librarian') {
    header('Location: ../index.php');
    exit();
}

$stmt = $pdo->prepare("SELECT * FROM Books");
$stmt->execute();
$books = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($books);
?>
