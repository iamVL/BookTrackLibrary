<?php
// member_dashboard.php

session_start();
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'member') {
    header('Location: index.php');
    exit();
}

// Fetch member's full name from the database
include 'backend/db_connect.php';
$member_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT full_name FROM LibraryMembers WHERE member_id = :member_id");
$stmt->execute(['member_id' => $member_id]);
$member = $stmt->fetch(PDO::FETCH_ASSOC);
$full_name = $member ? $member['full_name'] : 'Member';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Member Dashboard - BookTrack Library</title>
    <link rel="stylesheet" href="styles.css">
    <script src="script.js" defer></script>
</head>
<body>
    <div class="container">
        <h1>Welcome, <?php echo htmlspecialchars($full_name); ?></h1>
        <button onclick="logout()">Logout</button>

        <h2>Search Books</h2>
        <input type="text" id="searchInput" placeholder="Search by title, author, genre, or ISBN">
        <select id="searchBy">
            <option value="title">Title</option>
            <option value="author">Author</option>
            <option value="genre">Genre</option>
            <option value="isbn">ISBN</option>
        </select>
        <button onclick="searchBooks()">Search</button>

        <div id="searchResults"></div>

        <h2>Your Current Loans</h2>
        <div id="currentLoans"></div>

        <h2>Your Borrowing History</h2>
        <div id="borrowingHistory"></div>

        <h2>Your Wishlist</h2>
        <button onclick="viewWishlist()">View Wishlist</button>
        <div id="wishlist"></div>

        <h2>Notifications</h2>
        <div id="notifications"></div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', () => {
        loadNotifications();
        loadCurrentLoans();
        loadBorrowingHistory();
    });
    </script>
</body>
</html>
