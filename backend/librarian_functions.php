<?php
// librarian_functions.php

session_start();
include 'db_connect.php';

// Ensure the user is logged in as a librarian
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'librarian') {
    header('Location: ../index.php');
    exit();
}

$librarian_id = $_SESSION['user_id'];

// Handle different actions based on 'action' parameter
if (isset($_GET['action'])) {
    $action = $_GET['action'];

    switch ($action) {
        case 'add_book':
            addBook($pdo);
            break;
        case 'update_book':
            updateBook($pdo);
            break;
        case 'delete_book':
            deleteBook($pdo);
            break;
        case 'add_member':
            addMember($pdo);
            break;
        case 'update_member':
            updateMember($pdo);
            break;
        case 'deactivate_member':
            deactivateMember($pdo);
            break;
        case 'reactivate_member':
            reactivateMember($pdo);
            break;
        case 'borrow_books':
            borrowBooks($pdo, $librarian_id);
            break;
        case 'return_books':
            returnBooks($pdo, $librarian_id);
            break;
        default:
            echo json_encode(['error' => 'Invalid action']);
    }
}

function addBook($pdo) {
    // Retrieve book data from POST
    $title = trim($_POST['title']);
    $author = trim($_POST['author']);
    $genre = trim($_POST['genre']);
    $isbn = trim($_POST['isbn']);
    $total_copies = intval($_POST['total_copies']);

    if (empty($title) || empty($author) || empty($isbn) || $total_copies <= 0) {
        echo json_encode(['error' => 'Please provide all required fields']);
        return;
    }

    // Insert into Books
    try {
        $stmt = $pdo->prepare("
            INSERT INTO Books (title, author, genre, isbn, total_copies, available_copies)
            VALUES (:title, :author, :genre, :isbn, :total_copies, :available_copies)
        ");
        $stmt->execute([
            'title' => $title,
            'author' => $author,
            'genre' => $genre,
            'isbn' => $isbn,
            'total_copies' => $total_copies,
            'available_copies' => $total_copies
        ]);
        echo json_encode(['success' => 'Book added successfully']);
    } catch (PDOException $e) {
        // Handle duplicate ISBN
        if ($e->getCode() == '23505') { // Unique violation
            echo json_encode(['error' => 'A book with this ISBN already exists']);
        } else {
            echo json_encode(['error' => 'Failed to add book']);
        }
    }
}

function updateBook($pdo) {
    // Retrieve book data from POST
    $book_id = intval($_POST['book_id']);
    $title = trim($_POST['title']);
    $author = trim($_POST['author']);
    $genre = trim($_POST['genre']);
    $isbn = trim($_POST['isbn']);
    $total_copies = intval($_POST['total_copies']);

    if ($book_id <= 0 || empty($title) || empty($author) || empty($isbn) || $total_copies <= 0) {
        echo json_encode(['error' => 'Please provide all required fields']);
        return;
    }

    // Update Books
    try {
        // Calculate available copies based on total copies
        $stmt = $pdo->prepare("SELECT total_copies, available_copies FROM Books WHERE book_id = :book_id");
        $stmt->execute(['book_id' => $book_id]);
        $book = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$book) {
            echo json_encode(['error' => 'Book not found']);
            return;
        }

        $difference = $total_copies - $book['total_copies'];
        $new_available = $book['available_copies'] + $difference;

        if ($new_available < 0) {
            echo json_encode(['error' => 'Cannot reduce total copies below borrowed copies']);
            return;
        }

        $stmt = $pdo->prepare("
            UPDATE Books
            SET title = :title, author = :author, genre = :genre, isbn = :isbn,
                total_copies = :total_copies, available_copies = :available_copies
            WHERE book_id = :book_id
        ");
        $stmt->execute([
            'title' => $title,
            'author' => $author,
            'genre' => $genre,
            'isbn' => $isbn,
            'total_copies' => $total_copies,
            'available_copies' => $new_available,
            'book_id' => $book_id
        ]);
        echo json_encode(['success' => 'Book updated successfully']);
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Failed to update book']);
    }
}

function deleteBook($pdo) {
    // Retrieve book_id from POST
    $book_id = intval($_POST['book_id']);

    if ($book_id <= 0) {
        echo json_encode(['error' => 'Invalid book ID']);
        return;
    }

    // Delete the book
    try {
        $stmt = $pdo->prepare("DELETE FROM Books WHERE book_id = :book_id");
        $stmt->execute(['book_id' => $book_id]);
        echo json_encode(['success' => 'Book deleted successfully']);
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Failed to delete book']);
    }
}

function manageMembers($pdo) {
    // Implement member management functionalities here
    // e.g., add_member, update_member, deactivate_member, etc.
}

function recordTransaction($pdo) {
    // Implement transaction recording here
    // e.g., borrowing and returning books
}

function addMember($pdo) {
    // Retrieve member data from POST
    $username = trim($_POST['username']);
    $password = $_POST['password']; // Use plain text password
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);

    if (empty($username) || empty($password) || empty($full_name) || empty($email)) {
        echo json_encode(['error' => 'Please provide all required fields']);
        return;
    }

    // Store the password as plain text (Note: This is not secure)
    // $password_hash = password_hash($password, PASSWORD_BCRYPT); // Remove this line

    // Insert into LibraryMembers
    try {
        $stmt = $pdo->prepare("
            INSERT INTO LibraryMembers (username, password, full_name, email, phone, address)
            VALUES (:username, :password, :full_name, :email, :phone, :address)
        ");
        $stmt->execute([
            'username' => $username,
            'password' => $password, // Store plain text password
            'full_name' => $full_name,
            'email' => $email,
            'phone' => $phone,
            'address' => $address
        ]);
        echo json_encode(['success' => 'Member added successfully']);
    } catch (PDOException $e) {
        // Handle duplicate username or email
        if ($e->getCode() == '23505') { // Unique violation
            echo json_encode(['error' => 'Username or email already exists']);
        } else {
            echo json_encode(['error' => 'Failed to add member']);
        }
    }
}


function updateMember($pdo) {
    // Retrieve member data from POST
    $member_id = intval($_POST['member_id']);
    $username = trim($_POST['username']);
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);

    if ($member_id <= 0 || empty($username) || empty($full_name) || empty($email)) {
        echo json_encode(['error' => 'Please provide all required fields']);
        return;
    }

    // Update LibraryMembers
    try {
        $stmt = $pdo->prepare("
            UPDATE LibraryMembers
            SET username = :username, full_name = :full_name, email = :email, phone = :phone, address = :address
            WHERE member_id = :member_id
        ");
        $stmt->execute([
            'username' => $username,
            'full_name' => $full_name,
            'email' => $email,
            'phone' => $phone,
            'address' => $address,
            'member_id' => $member_id
        ]);
        echo json_encode(['success' => 'Member updated successfully']);
    } catch (PDOException $e) {
        // Handle duplicate username or email
        if ($e->getCode() == '23505') { // Unique violation
            echo json_encode(['error' => 'Username or email already exists']);
        } else {
            echo json_encode(['error' => 'Failed to update member']);
        }
    }
}

function deactivateMember($pdo) {
    $member_id = intval($_POST['member_id']);

    if ($member_id <= 0) {
        echo json_encode(['error' => 'Invalid member ID']);
        return;
    }

    try {
        $stmt = $pdo->prepare("
            UPDATE LibraryMembers
            SET is_active = FALSE
            WHERE member_id = :member_id
        ");
        $stmt->execute(['member_id' => $member_id]);
        echo json_encode(['success' => 'Member deactivated successfully']);
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Failed to deactivate member']);
    }
}

function reactivateMember($pdo) {
    $member_id = intval($_POST['member_id']);

    if ($member_id <= 0) {
        echo json_encode(['error' => 'Invalid member ID']);
        return;
    }

    try {
        $stmt = $pdo->prepare("
            UPDATE LibraryMembers
            SET is_active = TRUE
            WHERE member_id = :member_id
        ");
        $stmt->execute(['member_id' => $member_id]);
        echo json_encode(['success' => 'Member reactivated successfully']);
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Failed to reactivate member']);
    }
}
function borrowBooks($pdo, $librarian_id) {
    // Retrieve data from POST
    $member_id = intval($_POST['member_id']);
    $book_ids = $_POST['book_ids']; // Assume this is an array of book_ids

    if ($member_id <= 0 || empty($book_ids) || !is_array($book_ids)) {
        echo json_encode(['error' => 'Invalid member ID or book IDs']);
        return;
    }

    // Start transaction
    try {
        $pdo->beginTransaction();

        // Create a new transaction
        $due_date = date('Y-m-d', strtotime('+14 days')); // 2 weeks from today
        $stmt = $pdo->prepare("
            INSERT INTO Transactions (member_id, librarian_id, due_date)
            VALUES (:member_id, :librarian_id, :due_date)
            RETURNING transaction_id
        ");
        $stmt->execute([
            'member_id' => $member_id,
            'librarian_id' => $librarian_id,
            'due_date' => $due_date
        ]);
        $transaction_id = $stmt->fetchColumn();

        // For each book, insert into TransactionBooks and update available_copies
        foreach ($book_ids as $book_id) {
            $book_id = intval($book_id);
            if ($book_id <= 0) continue;

            // Check if the book is available
            $stmt = $pdo->prepare("SELECT available_copies FROM Books WHERE book_id = :book_id");
            $stmt->execute(['book_id' => $book_id]);
            $book = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$book || $book['available_copies'] <= 0) {
                throw new Exception('Book not available');
            }

            // Insert into TransactionBooks
            $stmt = $pdo->prepare("
                INSERT INTO TransactionBooks (transaction_id, book_id, quantity)
                VALUES (:transaction_id, :book_id, 1)
            ");
            $stmt->execute([
                'transaction_id' => $transaction_id,
                'book_id' => $book_id
            ]);

            // Update available_copies
            $stmt = $pdo->prepare("
                UPDATE Books
                SET available_copies = available_copies - 1
                WHERE book_id = :book_id
            ");
            $stmt->execute(['book_id' => $book_id]);
        }

        $pdo->commit();
        echo json_encode(['success' => 'Books borrowed successfully']);
    } catch (Exception $e) {
        $pdo->rollBack();
        echo json_encode(['error' => $e->getMessage()]);
    }
}

function returnBooks($pdo, $librarian_id) {
    // Retrieve data from POST
    $transaction_id = intval($_POST['transaction_id']);
    $book_ids = $_POST['book_ids']; // Assume this is an array of book_ids

    if ($transaction_id <= 0 || empty($book_ids) || !is_array($book_ids)) {
        echo json_encode(['error' => 'Invalid transaction ID or book IDs']);
        return;
    }

    // Start transaction
    try {
        $pdo->beginTransaction();

        // Get the transaction details
        $stmt = $pdo->prepare("
            SELECT * FROM Transactions
            WHERE transaction_id = :transaction_id
        ");
        $stmt->execute(['transaction_id' => $transaction_id]);
        $transaction = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$transaction) {
            throw new Exception('Transaction not found');
        }

        // Update return_date if not already set
        if (!$transaction['return_date']) {
            $stmt = $pdo->prepare("
                UPDATE Transactions
                SET return_date = CURRENT_DATE
                WHERE transaction_id = :transaction_id
            ");
            $stmt->execute(['transaction_id' => $transaction_id]);
        }

        // For each book, update available_copies and calculate fines if overdue
        foreach ($book_ids as $book_id) {
            $book_id = intval($book_id);
            if ($book_id <= 0) continue;

            // Check if the book was borrowed in this transaction
            $stmt = $pdo->prepare("
                SELECT * FROM TransactionBooks
                WHERE transaction_id = :transaction_id AND book_id = :book_id
            ");
            $stmt->execute([
                'transaction_id' => $transaction_id,
                'book_id' => $book_id
            ]);
            $transaction_book = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$transaction_book) {
                throw new Exception('Book was not borrowed in this transaction');
            }

            // Update available_copies
            $stmt = $pdo->prepare("
                UPDATE Books
                SET available_copies = available_copies + 1
                WHERE book_id = :book_id
            ");
            $stmt->execute(['book_id' => $book_id]);

            // Calculate fine if overdue
            $due_date = new DateTime($transaction['due_date']);
            $return_date = new DateTime();
            if ($return_date > $due_date) {
                $interval = $due_date->diff($return_date);
                $days_overdue = $interval->days;
                $fine_per_day = 0.5; // For example, $0.50 per day
                $fine = $days_overdue * $fine_per_day;

                // Update fine in Transactions table
                $stmt = $pdo->prepare("
                    UPDATE Transactions
                    SET fine = fine + :fine
                    WHERE transaction_id = :transaction_id
                ");
                $stmt->execute([
                    'fine' => $fine,
                    'transaction_id' => $transaction_id
                ]);
            }
        }

        $pdo->commit();
        echo json_encode(['success' => 'Books returned successfully']);
    } catch (Exception $e) {
        $pdo->rollBack();
        echo json_encode(['error' => $e->getMessage()]);
    }
}
?>
