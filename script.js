// script.js

(function() {
    // Logout Function
    function logout() {
        window.location.href = 'backend/logout.php';
    }

    window.logout = logout;

    // Search Books
    function searchBooks() {
        const query = document.getElementById('searchInput').value;
        const searchBy = document.getElementById('searchBy').value;

        if (query.trim() === '') {
            Swal.fire({
                icon: 'warning',
                title: 'No Search Query',
                text: 'Please enter a search query.'
            });
            return;
        }

        fetch(`backend/member_functions.php?action=search_books&query=${encodeURIComponent(query)}&search_by=${searchBy}`)
            .then(response => response.json())
            .then(data => {
                const resultsDiv = document.getElementById('searchResults');
                resultsDiv.innerHTML = '';

                if (data.error) {
                    resultsDiv.innerHTML = `<div class="alert alert-danger">${data.error}</div>`;
                    return;
                }

                if (data.length === 0) {
                    resultsDiv.innerHTML = '<p>No books found.</p>';
                    return;
                }

                data.forEach(book => {
                    const bookCard = document.createElement('div');
                    bookCard.classList.add('card', 'mb-3');

                    bookCard.innerHTML = `
                        <div class="card-body">
                            <h5 class="card-title">${book.title} by ${book.author}</h5>
                            <p class="card-text">Genre: ${book.genre || 'N/A'}</p>
                            <p class="card-text">ISBN: ${book.isbn}</p>
                            <p class="card-text">Available Copies: ${book.available_copies}</p>
                            <button class="btn btn-primary btn-sm" onclick="addToWishlist(${book.book_id})">
                                <i class="fas fa-heart"></i> Add to Wishlist
                            </button>
                            <button class="btn btn-secondary btn-sm" onclick="leaveReview(${book.book_id})">
                                <i class="fas fa-pencil-alt"></i> Leave a Review
                            </button>
                            ${book.available_copies == 0 ? `<button class="btn btn-warning btn-sm" onclick="reserveBook(${book.book_id})">
                            <i class="fas fa-bookmark"></i> Reserve Book</button>` : ''}
                        </div>
                    `;
                    resultsDiv.appendChild(bookCard);
                });
            })
            .catch(error => console.error('Error:', error));
    }

    window.searchBooks = searchBooks;

    // Add to Wishlist
    window.addToWishlist = function(bookId) {
        fetch('backend/member_functions.php?action=add_to_wishlist', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({book_id: bookId})
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Added to Wishlist',
                    text: data.success,
                    showConfirmButton: false,
                    timer: 1500
                });
            } else if (data.error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: data.error
                });
            }
        })
        .catch(error => console.error('Error:', error));
    };

    // View Wishlist
    window.viewWishlist = function() {
        fetch('backend/member_functions.php?action=view_wishlist')
            .then(response => response.json())
            .then(data => {
                const wishlistDiv = document.getElementById('wishlist');
                wishlistDiv.innerHTML = '';

                if (data.error) {
                    wishlistDiv.innerHTML = `<div class="alert alert-danger">${data.error}</div>`;
                    return;
                }

                if (data.length === 0) {
                    wishlistDiv.innerHTML = '<p>Your wishlist is empty.</p>';
                    return;
                }

                data.forEach(book => {
                    const bookCard = document.createElement('div');
                    bookCard.classList.add('card', 'mb-3');

                    bookCard.innerHTML = `
                        <div class="card-body">
                            <h5 class="card-title">${book.title} by ${book.author}</h5>
                            <p class="card-text">Genre: ${book.genre || 'N/A'}</p>
                            <p class="card-text">ISBN: ${book.isbn}</p>
                            <p class="card-text">Added on: ${book.added_date}</p>
                        </div>
                    `;
                    wishlistDiv.appendChild(bookCard);
                });
            })
            .catch(error => console.error('Error:', error));
    };

    // Load Current Loans
    function loadCurrentLoans() {
        fetch('backend/member_functions.php?action=load_current_loans')
            .then(response => response.json())
            .then(data => {
                const loansDiv = document.getElementById('currentLoans');
                loansDiv.innerHTML = '';

                if (data.error) {
                    loansDiv.innerHTML = `<div class="alert alert-danger">${data.error}</div>`;
                    return;
                }

                if (data.length === 0) {
                    loansDiv.innerHTML = '<p>You have no current loans.</p>';
                    return;
                }

                let table = `<table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Transaction ID</th>
                            <th>Book ID</th>
                            <th>Title</th>
                            <th>Borrowed On</th>
                            <th>Due On</th>
                        </tr>
                    </thead>
                    <tbody>`;

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

                table += '</tbody></table>';
                loansDiv.innerHTML = table;
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
                    historyDiv.innerHTML = `<div class="alert alert-danger">${data.error}</div>`;
                    return;
                }

                if (data.length === 0) {
                    historyDiv.innerHTML = '<p>You have no borrowing history.</p>';
                    return;
                }

                let table = `<table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Transaction ID</th>
                            <th>Book ID</th>
                            <th>Title</th>
                            <th>Borrowed On</th>
                            <th>Due On</th>
                            <th>Returned On</th>
                            <th>Fine</th>
                        </tr>
                    </thead>
                    <tbody>`;

                data.forEach(record => {
                    table += `
                        <tr>
                            <td>${record.transaction_id}</td>
                            <td>${record.book_id}</td>
                            <td>${record.title}</td>
                            <td>${record.borrow_date}</td>
                            <td>${record.due_date}</td>
                            <td>${record.return_date || 'Not returned yet'}</td>
                            <td>$${record.fine || '0.00'}</td>
                        </tr>
                    `;
                });

                table += '</tbody></table>';
                historyDiv.innerHTML = table;
            })
            .catch(error => console.error('Error:', error));
    }

    // Leave Review
    window.leaveReview = function(bookId) {
        Swal.fire({
            title: 'Leave a Review',
            html:
                `<input type="number" id="rating" class="swal2-input" placeholder="Rating (1-5)" min="1" max="5">
                <textarea id="review_text" class="swal2-textarea" placeholder="Your review"></textarea>`,
            showCancelButton: true,
            confirmButtonText: 'Submit',
            focusConfirm: false,
            preConfirm: () => {
                const rating = document.getElementById('rating').value;
                const review_text = document.getElementById('review_text').value;
                if (!rating || rating < 1 || rating > 5) {
                    Swal.showValidationMessage('Please enter a valid rating between 1 and 5');
                }
                return { rating: rating, review_text: review_text };
            }
        }).then((result) => {
            if (result.isConfirmed) {
                const { rating, review_text } = result.value;
                fetch('backend/member_functions.php?action=leave_review', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({book_id: bookId, rating: rating, review_text: review_text})
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Review Submitted',
                            text: data.success,
                            showConfirmButton: false,
                            timer: 1500
                        });
                    } else if (data.error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: data.error
                        });
                    }
                })
                .catch(error => console.error('Error:', error));
            }
        });
    };

    // Reserve Book
    window.reserveBook = function(bookId) {
        fetch('backend/member_functions.php?action=reserve_book', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({book_id: bookId})
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Book Reserved',
                    text: data.success,
                    showConfirmButton: false,
                    timer: 1500
                });
            } else if (data.error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: data.error
                });
            }
        })
        .catch(error => console.error('Error:', error));
    };

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
                    notificationDiv.classList.add('alert', notification.type === 'due_soon' ? 'alert-warning' : 'alert-danger');
                    notificationDiv.innerHTML = `<p>${notification.message}</p>`;
                    notificationsDiv.appendChild(notificationDiv);
                });
            })
            .catch(error => console.error('Error:', error));
    }

    // On Page Load
    document.addEventListener('DOMContentLoaded', () => {
        loadNotifications();
        loadCurrentLoans();
        loadBorrowingHistory();
    });
})();
