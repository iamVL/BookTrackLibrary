<?php
// librarian_dashboard.php

session_start();
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'librarian') {
    header('Location: index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Librarian Dashboard - BookTrack Library</title>
    <link rel="stylesheet" href="styles.css">
    <script src="librarian.js" defer></script>
</head>
<body>
    <div class="container">
        <h1>Welcome, Librarian</h1>
        <button onclick="logout()">Logout</button>

        <h2>Manage Books</h2>
        <button onclick="showAddBookForm()">Add New Book</button>
        <div id="addBookForm" style="display:none;">
            <h3>Add Book</h3>
            <form id="addBookFormElement">
                <input type="text" id="title" placeholder="Title" required><br>
                <input type="text" id="author" placeholder="Author" required><br>
                <input type="text" id="genre" placeholder="Genre"><br>
                <input type="text" id="isbn" placeholder="ISBN" required><br>
                <input type="number" id="total_copies" placeholder="Total Copies" required><br>
                <button type="submit">Add Book</button>
            </form>
        </div>

        <div id="bookManagement"></div>

        <h2>Manage Members</h2>
        <button onclick="showAddMemberForm()">Add New Member</button>
        <div id="addMemberForm" style="display:none;">
            <h3>Add Member</h3>
            <form id="addMemberFormElement">
                <input type="text" id="member_username" placeholder="Username" required><br>
                <input type="password" id="member_password" placeholder="Password" required><br>
                <input type="text" id="member_full_name" placeholder="Full Name" required><br>
                <input type="email" id="member_email" placeholder="Email" required><br>
                <input type="text" id="member_phone" placeholder="Phone"><br>
                <textarea id="member_address" placeholder="Address"></textarea><br>
                <button type="submit">Add Member</button>
            </form>
        </div>

        <div id="memberManagement"></div>

        <h2>Current Transactions</h2>
        <div id="transactionManagement"></div>

        <h2>Borrow Books</h2>
        <form id="borrowBooksForm">
            <input type="number" id="borrow_member_id" placeholder="Member ID" required><br>
            <input type="text" id="borrow_book_ids" placeholder="Book IDs (comma-separated)" required><br>
            <button type="submit">Borrow Books</button>
        </form>

        <h2>Return Books</h2>
        <form id="returnBooksForm">
            <input type="number" id="return_transaction_id" placeholder="Transaction ID" required><br>
            <input type="text" id="return_book_ids" placeholder="Book IDs (comma-separated)" required><br>
            <button type="submit">Return Books</button>
        </form>
    </div>
</body>
</html>
