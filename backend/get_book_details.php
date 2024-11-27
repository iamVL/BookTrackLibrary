<?php
// get_book_details.php

session_start();
include 'db_connect.php';

if (!isset($_GET['book_id'])) {
    echo json_encode(['error' => 'Book ID not provided']);
    exit();
}

$book_id = intval($_GET['book_id']);

$stmt = $pdo->prepare("SELECT * FROM Books WHERE book_id = :book_id");
$stmt->execute(['book_id' => $book_id]);
$book = $stmt->fetch(PDO::FETCH_ASSOC);

if ($book) {
    echo json_encode($book);
} else {
    echo json_encode(['error' => 'Book not found']);
}
?>
