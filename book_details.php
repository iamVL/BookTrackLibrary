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
    <link rel="stylesheet" href="styles.css">
    <script src="script.js" defer></script>
</head>
<body>
    <div class="container">
        <h1>Book Details</h1>
        <div id="bookDetails"></div>
        <button onclick="goBack()">Back to Dashboard</button>
    </div>
</body>
</html>
