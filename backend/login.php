<?php
// backend/login.php

session_start();
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // Attempt to authenticate as Library Member
    $stmt = $pdo->prepare("SELECT * FROM LibraryMembers WHERE username = :username AND is_active = TRUE");
    $stmt->execute(['username' => $username]);
    $member = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($member && $password == $member['password']) {
        $_SESSION['user_type'] = 'member';
        $_SESSION['user_id'] = $member['member_id'];
        header('Location: ../member_dashboard.php');
        exit();
    }

    // Attempt to authenticate as Librarian
    $stmt = $pdo->prepare("SELECT * FROM Librarians WHERE username = :username AND is_active = TRUE");
    $stmt->execute(['username' => $username]);
    $librarian = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($librarian && $password == $librarian['password']) {
        $_SESSION['user_type'] = 'librarian';
        $_SESSION['user_id'] = $librarian['librarian_id'];
        header('Location: ../librarian_dashboard.php');
        exit();
    }

    // Invalid credentials
    $_SESSION['error'] = 'Invalid username or password.';
    header('Location: ../index.php');
    exit();
}
?>
