<?php
// book_details.php

session_start();
if (!isset($_SESSION['user_type'])) {
    header('Location: index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Book Details - BookTrack Library</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <!-- Custom CSS -->
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <!-- Main Content -->
    <div class="container mt-5">
        <div id="bookDetails" class="animate__animated animate__fadeIn"></div>
        <button class="btn btn-secondary mt-3" onclick="goBack()"><i class="fas fa-arrow-left"></i> Back to Dashboard</button>
    </div>

    <!-- Include JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js" defer></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" defer></script>
    <!-- Custom JavaScript -->
    <script src="script.js" defer></script>
</body>
</html>
