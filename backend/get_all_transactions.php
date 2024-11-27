<?php
// get_all_transactions.php

session_start();
include 'db_connect.php';

// Ensure the user is logged in as a librarian
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'librarian') {
    header('Location: ../index.php');
    exit();
}

// Fetch all pending transactions (where return_date is NULL)
$stmt = $pdo->prepare("
    SELECT T.transaction_id, T.member_id, LM.full_name, T.borrow_date, T.due_date, T.return_date, T.fine,
        ARRAY_AGG(TB.book_id) AS book_ids,
        ARRAY_AGG(B.title) AS book_titles
    FROM Transactions T
    JOIN LibraryMembers LM ON T.member_id = LM.member_id
    JOIN TransactionBooks TB ON T.transaction_id = TB.transaction_id
    JOIN Books B ON TB.book_id = B.book_id
    WHERE T.return_date IS NULL
    GROUP BY T.transaction_id, LM.full_name
    ORDER BY T.borrow_date DESC
");
$stmt->execute();
$transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($transactions);
?>
