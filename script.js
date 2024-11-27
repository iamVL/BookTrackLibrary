// script.js

// Logout Function
function logout() {
    window.location.href = 'backend/logout.php';
}

// Search Books
function searchBooks() {
    const query = document.getElementById('searchInput').value;
    const searchBy = document.getElementById('searchBy').value;

    if (query.trim() === '') {
        alert('Please enter a search query.');
        return;
    }

    fetch(`backend/member_functions.php?action=search_books&query=${encodeURIComponent(query)}&search_by=${searchBy}`)
        .then(response => response.json())
        .then(data => {
            const resultsDiv = document.getElementById('searchResults');
            resultsDiv.innerHTML = '';

            if (data.error) {
                resultsDiv.innerHTML = `<p class="error">${data.error}</p>`;
                return;
            }

            if (data.length === 0) {
                resultsDiv.innerHTML = '<p>No books found.</p>';
                return;
            }

            data.forEach(book => {
                const bookDiv = document.createElement('div');
                bookDiv.innerHTML = `
                    <h3>${book.title} by ${book.author}</h3>
                    <p>Genre: ${book.genre}</p>
                    <p>ISBN: ${book.isbn}</p>
                    <p>Available Copies: ${book.available_copies}</p>
                    <button onclick="addToWishlist(${book.book_id})">Add to Wishlist</button>
                    <button onclick="leaveReview(${book.book_id})">Leave a Review</button>
                    ${book.available_copies == 0 ? `<button onclick="reserveBook(${book.book_id})">Reserve Book</button>` : ''}
                `;
                resultsDiv.appendChild(bookDiv);
            });
        })
        .catch(error => console.error('Error:', error));
}

// Add to Wishlist
function addToWishlist(bookId) {
    fetch('backend/member_functions.php?action=add_to_wishlist', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({book_id: bookId})
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.success);
        } else if (data.error) {
            alert(data.error);
        }
    })
    .catch(error => console.error('Error:', error));
}

// View Wishlist
function viewWishlist() {
    fetch('backend/member_functions.php?action=view_wishlist')
        .then(response => response.json())
        .then(data => {
            const wishlistDiv = document.getElementById('wishlist');
            wishlistDiv.innerHTML = '';

            if (data.error) {
                wishlistDiv.innerHTML = `<p class="error">${data.error}</p>`;
                return;
            }

            if (data.length === 0) {
                wishlistDiv.innerHTML = '<p>Your wishlist is empty.</p>';
                return;
            }

            data.forEach(book => {
                const bookDiv = document.createElement('div');
                bookDiv.innerHTML = `
                    <h3>${book.title} by ${book.author}</h3>
                    <p>Genre: ${book.genre}</p>
                    <p>ISBN: ${book.isbn}</p>
                    <p>Added on: ${book.added_date}</p>
                `;
                wishlistDiv.appendChild(bookDiv);
            });
        })
        .catch(error => console.error('Error:', error));
}

// Load Borrowing History
function loadBorrowingHistory() {
    fetch('backend/member_functions.php?action=load_borrowing_history')
        .then(response => response.json())
        .then(data => {
            const historyDiv = document.getElementById('borrowingHistory');
            historyDiv.innerHTML = '';

            if (data.error) {
                historyDiv.innerHTML = `<p class="error">${data.error}</p>`;
                return;
            }

            if (data.length === 0) {
                historyDiv.innerHTML = '<p>You have no borrowing history.</p>';
                return;
            }

            data.forEach(record => {
                const recordDiv = document.createElement('div');
                recordDiv.innerHTML = `
                    <h3>${record.title} by ${record.author}</h3>
                    <p>Borrowed on: ${record.borrow_date}</p>
                    <p>Due on: ${record.due_date}</p>
                    <p>Returned on: ${record.return_date ? record.return_date : 'Not returned yet'}</p>
                    <p>Fine: $${record.fine}</p>
                `;
                historyDiv.appendChild(recordDiv);
            });
        })
        .catch(error => console.error('Error:', error));
}

// Leave Review
function leaveReview(bookId) {
    const rating = prompt('Enter your rating (1-5):');
    const review_text = prompt('Enter your review:');

    if (!rating || rating < 1 || rating > 5) {
        alert('Invalid rating');
        return;
    }

    fetch('backend/member_functions.php?action=leave_review', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({book_id: bookId, rating: rating, review_text: review_text})
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.success);
        } else if (data.error) {
            alert(data.error);
        }
    })
    .catch(error => console.error('Error:', error));
}

// Reserve Book
function reserveBook(bookId) {
    fetch('backend/member_functions.php?action=reserve_book', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({book_id: bookId})
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.success);
        } else if (data.error) {
            alert(data.error);
        }
    })
    .catch(error => console.error('Error:', error));
}

// Load Notifications
function loadNotifications() {
    fetch('backend/member_functions.php?action=get_notifications')
        .then(response => response.json())
        .then(data => {
            const notificationsDiv = document.getElementById('notifications');
            notificationsDiv.innerHTML = '';

            if (data.length === 0) {
                notificationsDiv.innerHTML = '<p>No notifications.</p>';
                return;
            }

            data.forEach(notification => {
                const notificationDiv = document.createElement('div');
                notificationDiv.innerHTML = `<p>${notification.message}</p>`;
                notificationsDiv.appendChild(notificationDiv);
            });
        })
        .catch(error => console.error('Error:', error));
}

// Fetch Book Details
function loadBookDetails() {
    const params = new URLSearchParams(window.location.search);
    const bookId = params.get('book_id');

    if (!bookId) {
        document.getElementById('bookDetails').innerHTML = '<p>Book ID not provided.</p>';
        return;
    }

    fetch(`backend/get_book_details.php?book_id=${bookId}`)
        .then(response => response.json())
        .then(data => {
            const detailsDiv = document.getElementById('bookDetails');

            if (data.error) {
                detailsDiv.innerHTML = `<p class="error">${data.error}</p>`;
                return;
            }

            detailsDiv.innerHTML = `
                <h2>${data.title}</h2>
                <p><strong>Author:</strong> ${data.author}</p>
                <p><strong>Genre:</strong> ${data.genre}</p>
                <p><strong>ISBN:</strong> ${data.isbn}</p>
                <p><strong>Total Copies:</strong> ${data.total_copies}</p>
                <p><strong>Available Copies:</strong> ${data.available_copies}</p>
                <!-- Add more details and actions as needed -->
            `;
        })
        .catch(error => console.error('Error:', error));
}

// Load Current Loans
function loadCurrentLoans() {
    fetch('backend/member_functions.php?action=load_current_loans')
        .then(response => response.json())
        .then(data => {
            const loansDiv = document.getElementById('currentLoans');
            loansDiv.innerHTML = '';

            if (data.error) {
                loansDiv.innerHTML = `<p class="error">${data.error}</p>`;
                return;
            }

            if (data.length === 0) {
                loansDiv.innerHTML = '<p>You have no current loans.</p>';
                return;
            }

            let table = `<table>
                <tr>
                    <th>Transaction ID</th>
                    <th>Book ID</th>
                    <th>Title</th>
                    <th>Borrowed On</th>
                    <th>Due On</th>
                </tr>`;

            data.forEach(loan => {
                table += `
                    <tr>
                        <td>${loan.transaction_id}</td>
                        <td>${loan.book_id}</td>
                        <td>${loan.title}</td>
                        <td>${loan.borrow_date}</td>
                        <td>${loan.due_date}</td>
                    </tr>
                `;
            });

            table += '</table>';
            loansDiv.innerHTML = table;
        })
        .catch(error => console.error('Error:', error));
}

// On Page Load
document.addEventListener('DOMContentLoaded', () => {
    if (window.location.pathname.endsWith('member_dashboard.php')) {
        loadNotifications();
        loadCurrentLoans();
        loadBorrowingHistory();
    } else if (window.location.pathname.endsWith('book_details.php')) {
        loadBookDetails();
    }
});
