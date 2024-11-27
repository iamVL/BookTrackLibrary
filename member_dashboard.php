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
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <!-- SweetAlert CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="#">BookTrack Library</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"         aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <!-- Navigation items -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <span class="navbar-text text-white">Welcome, <?php echo htmlspecialchars($full_name); ?></span>
                </li>
                <li class="nav-item">
                    <button class="btn btn-outline-light ml-3" onclick="logout()">Logout</button>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mt-4">
        <!-- Search Books Section -->
        <h2 class="mb-4">Search Books</h2>
        <div class="form-inline mb-3">
            <input type="text" id="searchInput" class="form-control mr-2" placeholder="Search books...">
            <select id="searchBy" class="form-control mr-2">
                <option value="title">Title</option>
                <option value="author">Author</option>
                <option value="genre">Genre</option>
                <option value="isbn">ISBN</option>
            </select>
            <button class="btn btn-primary" onclick="searchBooks()"><i class="fas fa-search"></i> Search</button>
        </div>
        <div id="searchResults" class="animate__animated animate__fadeIn"></div>

        <!-- Current Loans Section -->
        <h2 class="mt-5 mb-4">Your Current Loans</h2>
        <div id="currentLoans" class="animate__animated animate__fadeIn"></div>

        <!-- Borrowing History Section -->
        <h2 class="mt-5 mb-4">Your Borrowing History</h2>
        <div id="borrowingHistory" class="animate__animated animate__fadeIn"></div>

        <!-- Wishlist Section -->
        <h2 class="mt-5 mb-4">Your Wishlist</h2>
        <button class="btn btn-info mb-3" onclick="viewWishlist()"><i class="fas fa-heart"></i> View Wishlist</button>
        <div id="wishlist" class="animate__animated animate__fadeIn"></div>

        <!-- Notifications Section -->
        <h2 class="mt-5 mb-4">Notifications</h2>
        <div id="notifications" class="animate__animated animate__fadeIn"></div>
    </div>

    <!-- Include JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" defer></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js" defer></script>
    <!-- Custom JavaScript -->
    <script src="script.js" defer></script>
</body>
</html>
