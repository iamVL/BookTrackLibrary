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
                    <span class="navbar-text text-white">Welcome, Librarian</span>
                </li>
                <li class="nav-item">
                    <button class="btn btn-outline-light ml-3" onclick="logout()">Logout</button>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mt-4">
        <!-- Manage Books Section -->
        <h2 class="mb-4">Manage Books</h2>
        <!-- Add Book Button -->
        <button class="btn btn-success mb-3" data-toggle="modal" data-target="#addBookModal">
            <i class="fas fa-plus"></i> Add New Book
        </button>
        <!-- Book Management Table -->
        <div id="bookManagement" class="animate__animated animate__fadeIn"></div>

        <!-- Add Book Modal -->
        <div class="modal fade" id="addBookModal" tabindex="-1" aria-labelledby="addBookModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <form id="addBookFormElement">
                <div class="modal-header">
                  <h5 class="modal-title" id="addBookModalLabel">Add New Book</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
                </div>
                <div class="modal-body">
                  <!-- Form Fields -->
                  <div class="form-group">
                    <label for="title">Title<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="title" required>
                  </div>
                  <div class="form-group">
                    <label for="author">Author<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="author" required>
                  </div>
                  <div class="form-group">
                    <label for="genre">Genre</label>
                    <input type="text" class="form-control" id="genre">
                  </div>
                  <div class="form-group">
                    <label for="isbn">ISBN<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="isbn" required>
                  </div>
                  <div class="form-group">
                    <label for="total_copies">Total Copies<span class="text-danger">*</span></label>
                    <input type="number" class="form-control" id="total_copies" required>
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="submit" class="btn btn-primary">Add Book</button>
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
              </form>
            </div>
          </div>
        </div>

        <!-- Manage Members Section -->
        <h2 class="mt-5 mb-4">Manage Members</h2>
        <!-- Add Member Button -->
        <button class="btn btn-success mb-3" data-toggle="modal" data-target="#addMemberModal">
            <i class="fas fa-user-plus"></i> Add New Member
        </button>
        <!-- Member Management Table -->
        <div id="memberManagement" class="animate__animated animate__fadeIn"></div>

        <!-- Add Member Modal -->
        <div class="modal fade" id="addMemberModal" tabindex="-1" aria-labelledby="addMemberModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <form id="addMemberFormElement">
                <div class="modal-header">
                  <h5 class="modal-title" id="addMemberModalLabel">Add New Member</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
                </div>
                <div class="modal-body">
                  <!-- Form Fields -->
                  <div class="form-group">
                    <label for="member_username">Username<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="member_username" required>
                  </div>
                  <div class="form-group">
                    <label for="member_password">Password<span class="text-danger">*</span></label>
                    <input type="password" class="form-control" id="member_password" required>
                  </div>
                  <div class="form-group">
                    <label for="member_full_name">Full Name<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="member_full_name" required>
                  </div>
                  <div class="form-group">
                    <label for="member_email">Email<span class="text-danger">*</span></label>
                    <input type="email" class="form-control" id="member_email" required>
                  </div>
                  <div class="form-group">
                    <label for="member_phone">Phone</label>
                    <input type="text" class="form-control" id="member_phone">
                  </div>
                  <div class="form-group">
                    <label for="member_address">Address</label>
                    <textarea class="form-control" id="member_address"></textarea>
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="submit" class="btn btn-primary">Add Member</button>
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
              </form>
            </div>
          </div>
        </div>

        <!-- Current Transactions Section -->
        <h2 class="mt-5 mb-4">Current Transactions</h2>
        <div id="transactionManagement" class="animate__animated animate__fadeIn"></div>

        <!-- Borrow Books Section -->
        <h2 class="mt-5 mb-4">Borrow Books</h2>
        <form id="borrowBooksForm" class="animate__animated animate__fadeIn">
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="borrow_member_id">Member ID<span class="text-danger">*</span></label>
                    <input type="number" class="form-control" id="borrow_member_id" required>
                </div>
                <div class="form-group col-md-8">
                    <label for="borrow_book_ids">Book IDs (comma-separated)<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="borrow_book_ids" required>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Borrow Books</button>
        </form>

        <!-- Return Books Section -->
        <h2 class="mt-5 mb-4">Return Books</h2>
        <form id="returnBooksForm" class="animate__animated animate__fadeIn">
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="return_transaction_id">Transaction ID<span class="text-danger">*</span></label>
                    <input type="number" class="form-control" id="return_transaction_id" required>
                </div>
                <div class="form-group col-md-8">
                    <label for="return_book_ids">Book IDs (comma-separated)<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="return_book_ids" required>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Return Books</button>
        </form>
    </div>

    <!-- Include JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" defer></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js" defer></script>
    <!-- Custom JavaScript -->
    <script src="librarian.js" defer></script>
</body>
</html>
