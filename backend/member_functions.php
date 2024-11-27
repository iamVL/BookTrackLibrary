<?php
// member_functions.php

session_start();
include 'db_connect.php';

// Ensure the user is logged in as a member
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'member') {
    header('Location: ../index.php');
    exit();
}

$member_id = $_SESSION['user_id'];

// Handle different actions based on 'action' parameter
if (isset($_GET['action'])) {
    $action = $_GET['action'];

    switch ($action) {
        case 'search_books':
            searchBooks($pdo);
            break;
        case 'add_to_wishlist':
            addToWishlist($pdo, $member_id);
            break;
        case 'view_wishlist':
            viewWishlist($pdo, $member_id);
            break;
        case 'load_borrowing_history':
            loadBorrowingHistory($pdo, $member_id);
            break;
        case 'load_current_loans':
            loadCurrentLoans($pdo, $member_id);
            break;
        case 'leave_review':
            leaveReview($pdo, $member_id);
            break;
        case 'get_notifications':
            getNotifications($pdo, $member_id);
            break;
        case 'reserve_book':
            reserveBook($pdo, $member_id);
            break;
        default:
            echo json_encode(['error' => 'Invalid action']);
    }
}

function searchBooks($pdo) {
    $query = isset($_GET['query']) ? trim($_GET['query']) : '';
    $search_by = isset($_GET['search_by']) ? trim($_GET['search_by']) : 'title';

    $allowed_search = ['title', 'author', 'genre', 'isbn'];
    if (!in_array($search_by, $allowed_search)) {
        echo json_encode(['error' => 'Invalid search parameter']);
        return;
    }

    $sql = "SELECT * FROM Books WHERE $search_by ILIKE :query";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['query' => "%$query%"]);
    $books = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($books);
}

function loadCurrentLoans($pdo, $member_id) {
    $stmt = $pdo->prepare("
        SELECT T.transaction_id, TB.book_id, B.title, T.borrow_date, T.due_date
        FROM Transactions T
        JOIN TransactionBooks TB ON T.transaction_id = TB.transaction_id
        JOIN Books B ON TB.book_id = B.book_id
        WHERE T.member_id = :member_id AND T.return_date IS NULL
        ORDER BY T.borrow_date DESC
    ");
    $stmt->execute(['member_id' => $member_id]);
    $current_loans = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($current_loans);
}

function addToWishlist($pdo, $member_id) {
    // Retrieve book_id from POST data
    $data = json_decode(file_get_contents('php://input'), true);
    $book_id = isset($data['book_id']) ? intval($data['book_id']) : 0;

    if ($book_id <= 0) {
        echo json_encode(['error' => 'Invalid book ID']);
        return;
    }

    // Check if the book exists
    $stmt = $pdo->prepare("SELECT * FROM Books WHERE book_id = :book_id");
    $stmt->execute(['book_id' => $book_id]);
    $book = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$book) {
        echo json_encode(['error' => 'Book not found']);
        return;
    }

    // Add to wishlist
    try {
        $stmt = $pdo->prepare("INSERT INTO Wishlist (member_id, book_id) VALUES (:member_id, :book_id)");
        $stmt->execute(['member_id' => $member_id, 'book_id' => $book_id]);
        echo json_encode(['success' => 'Book added to wishlist']);
    } catch (PDOException $e) {
        // Handle duplicate entries
        if ($e->getCode() == '23505') { // Unique violation
            echo json_encode(['error' => 'Book already in wishlist']);
        } else {
            echo json_encode(['error' => 'Failed to add to wishlist']);
        }
    }
}

function viewWishlist($pdo, $member_id) {
    $stmt = $pdo->prepare("
        SELECT Books.*, Wishlist.added_date
        FROM Wishlist
        JOIN Books ON Wishlist.book_id = Books.book_id
        WHERE Wishlist.member_id = :member_id
    ");
    $stmt->execute(['member_id' => $member_id]);
    $wishlist = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($wishlist);
}

function loadBorrowingHistory($pdo, $member_id) {
    $stmt = $pdo->prepare("
        SELECT T.transaction_id, T.borrow_date, T.due_date, T.return_date, T.fine,
               TB.book_id, B.title, B.author
        FROM Transactions T
        JOIN TransactionBooks TB ON T.transaction_id = TB.transaction_id
        JOIN Books B ON TB.book_id = B.book_id
        WHERE T.member_id = :member_id
        ORDER BY T.borrow_date DESC
    ");
    $stmt->execute(['member_id' => $member_id]);
    $history = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($history);
}

function leaveReview($pdo, $member_id) {
    $data = json_decode(file_get_contents('php://input'), true);
    $book_id = intval($data['book_id']);
    $rating = intval($data['rating']);
    $review_text = trim($data['review_text']);

    if ($book_id <= 0 || $rating < 1 || $rating > 5) {
        echo json_encode(['error' => 'Invalid book ID or rating']);
        return;
    }

    try {
        $stmt = $pdo->prepare("
            INSERT INTO Reviews (member_id, book_id, rating, review_text)
            VALUES (:member_id, :book_id, :rating, :review_text)
        ");
        $stmt->execute([
            'member_id' => $member_id,
            'book_id' => $book_id,
            'rating' => $rating,
            'review_text' => $review_text
        ]);
        echo json_encode(['success' => 'Review submitted successfully']);
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Failed to submit review']);
    }
}

function getNotifications($pdo, $member_id) {
    // Fetch transactions that are due soon or overdue
    $stmt = $pdo->prepare("
        SELECT T.*, B.title, B.author, TB.book_id
        FROM Transactions T
        JOIN TransactionBooks TB ON T.transaction_id = TB.transaction_id
        JOIN Books B ON TB.book_id = B.book_id
        WHERE T.member_id = :member_id AND T.return_date IS NULL
    ");
    $stmt->execute(['member_id' => $member_id]);
    $transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $notifications = [];

    foreach ($transactions as $transaction) {
        $due_date = new DateTime($transaction['due_date']);
        $current_date = new DateTime();
        $interval = $current_date->diff($due_date);
        $days_remaining = $interval->days * ($due_date > $current_date ? 1 : -1);

        if ($days_remaining <= 3 && $days_remaining >= 0) {
            $notifications[] = [
                'type' => 'due_soon',
                'message' => "Book '{$transaction['title']}' is due in {$days_remaining} day(s)."
            ];
        } elseif ($days_remaining < 0) {
            $notifications[] = [
                'type' => 'overdue',
                'message' => "Book '{$transaction['title']}' is overdue by " . abs($days_remaining) . " day(s)."
            ];
        }
    }

    echo json_encode($notifications);
}

function reserveBook($pdo, $member_id) {
    $data = json_decode(file_get_contents('php://input'), true);
    $book_id = intval($data['book_id']);

    if ($book_id <= 0) {
        echo json_encode(['error' => 'Invalid book ID']);
        return;
    }

    // Check if the book is available
    $stmt = $pdo->prepare("SELECT available_copies FROM Books WHERE book_id = :book_id");
    $stmt->execute(['book_id' => $book_id]);
    $book = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$book) {
        echo json_encode(['error' => 'Book not found']);
        return;
    }

    if ($book['available_copies'] > 0) {
        echo json_encode(['error' => 'Book is currently available. No need to reserve.']);
        return;
    }

    // Insert into Reservations
    try {
        $stmt = $pdo->prepare("
            INSERT INTO Reservations (member_id, book_id, reservation_date)
            VALUES (:member_id, :book_id, CURRENT_DATE)
        ");
        $stmt->execute([
            'member_id' => $member_id,
            'book_id' => $book_id
        ]);
        echo json_encode(['success' => 'Book reserved successfully']);
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Failed to reserve book']);
    }
}
?>
