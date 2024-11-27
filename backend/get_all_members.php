<?php
// get_all_members.php

session_start();
include 'db_connect.php';

// Ensure the user is logged in as a librarian
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'librarian') {
    header('Location: ../index.php');
    exit();
}

$stmt = $pdo->prepare("SELECT member_id, username, full_name, email, phone, address, is_active FROM LibraryMembers");
$stmt->execute();
$members = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($members);
?>
