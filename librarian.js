// librarian.js

// Logout Function
function logout() {
    window.location.href = 'backend/logout.php';
}

// Show Add Book Form
function showAddBookForm() {
    const form = document.getElementById('addBookForm');
    form.style.display = form.style.display === 'none' ? 'block' : 'none';
}

// Handle Add Book Form Submission
document.addEventListener('DOMContentLoaded', () => {
    const addBookForm = document.getElementById('addBookFormElement');
    if (addBookForm) {
        addBookForm.addEventListener('submit', function(event) {
            event.preventDefault();

            const title = document.getElementById('title').value.trim();
            const author = document.getElementById('author').value.trim();
            const genre = document.getElementById('genre').value.trim();
            const isbn = document.getElementById('isbn').value.trim();
            const total_copies = document.getElementById('total_copies').value.trim();

            if (!title || !author || !isbn || !total_copies) {
                alert('Please fill in all required fields.');
                return;
            }

            const formData = new FormData();
            formData.append('title', title);
            formData.append('author', author);
            formData.append('genre', genre);
            formData.append('isbn', isbn);
            formData.append('total_copies', total_copies);

            fetch('backend/librarian_functions.php?action=add_book', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.success);
                    addBookForm.reset();
                    loadBookManagement();
                } else if (data.error) {
                    alert(data.error);
                }
            })
            .catch(error => console.error('Error:', error));
        });
    }
});

// Load Book Management
function loadBookManagement() {
    fetch('backend/get_all_books.php')
        .then(response => response.json())
        .then(data => {
            const managementDiv = document.getElementById('bookManagement');
            managementDiv.innerHTML = '';

            if (data.error) {
                managementDiv.innerHTML = `<p class="error">${data.error}</p>`;
                return;
            }

            if (data.length === 0) {
                managementDiv.innerHTML = '<p>No books available.</p>';
                return;
            }

            let table = `<table>
                <tr>
                    <th>Book ID</th>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Genre</th>
                    <th>ISBN</th>
                    <th>Total Copies</th>
                    <th>Available Copies</th>
                    <th>Actions</th>
                </tr>`;

            data.forEach(book => {
                table += `
                    <tr>
                        <td>${book.book_id}</td>
                        <td>${book.title}</td>
                        <td>${book.author}</td>
                        <td>${book.genre}</td>
                        <td>${book.isbn}</td>
                        <td>${book.total_copies}</td>
                        <td>${book.available_copies}</td>
                        <td class="action-buttons">
                            <!-- <button onclick="editBook(${book.book_id})">Edit</button> -->
                            <button onclick="deleteBook(${book.book_id})">Delete</button>
                        </td>
                    </tr>
                `;
            });

            table += '</table>';
            managementDiv.innerHTML = table;
        })
        .catch(error => console.error('Error:', error));
}


// Delete Book
function deleteBook(bookId) {
    if (!confirm('Are you sure you want to delete this book?')) return;

    const formData = new FormData();
    formData.append('book_id', bookId);

    fetch('backend/librarian_functions.php?action=delete_book', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.success);
            loadBookManagement();
        } else if (data.error) {
            alert(data.error);
        }
    })
    .catch(error => console.error('Error:', error));
}

// Show Add Member Form
function showAddMemberForm() {
    const form = document.getElementById('addMemberForm');
    form.style.display = form.style.display === 'none' ? 'block' : 'none';
}

// Handle Add Member Form Submission
document.addEventListener('DOMContentLoaded', () => {
    const addMemberForm = document.getElementById('addMemberFormElement');
    if (addMemberForm) {
        addMemberForm.addEventListener('submit', function(event) {
            event.preventDefault();

            const username = document.getElementById('member_username').value.trim();
            const password = document.getElementById('member_password').value;
            const full_name = document.getElementById('member_full_name').value.trim();
            const email = document.getElementById('member_email').value.trim();
            const phone = document.getElementById('member_phone').value.trim();
            const address = document.getElementById('member_address').value.trim();

            if (!username || !password || !full_name || !email) {
                alert('Please fill in all required fields.');
                return;
            }

            const formData = new FormData();
            formData.append('username', username);
            formData.append('password', password);
            formData.append('full_name', full_name);
            formData.append('email', email);
            formData.append('phone', phone);
            formData.append('address', address);

            fetch('backend/librarian_functions.php?action=add_member', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.success);
                    addMemberForm.reset();
                    loadMemberManagement();
                } else if (data.error) {
                    alert(data.error);
                }
            })
            .catch(error => console.error('Error:', error));
        });
    }
});

// Load Member Management
function loadMemberManagement() {
    fetch('backend/get_all_members.php')
        .then(response => response.json())
        .then(data => {
            const managementDiv = document.getElementById('memberManagement');
            managementDiv.innerHTML = '';

            if (data.error) {
                managementDiv.innerHTML = `<p class="error">${data.error}</p>`;
                return;
            }

            if (data.length === 0) {
                managementDiv.innerHTML = '<p>No members available.</p>';
                return;
            }

            let table = `<table>
                <tr>
                    <th>Member ID</th>
                    <th>Username</th>
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Address</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>`;

            data.forEach(member => {
                table += `
                    <tr>
                        <td>${member.member_id}</td>
                        <td>${member.username}</td>
                        <td>${member.full_name}</td>
                        <td>${member.email}</td>
                        <td>${member.phone}</td>
                        <td>${member.address}</td>
                        <td>${member.is_active ? 'Active' : 'Inactive'}</td>
                        <td class="action-buttons">
                            <!-- <button onclick="editMember(${member.member_id})">Edit</button> -->
                            <button onclick="${member.is_active ? `deactivateMember(${member.member_id})` : `reactivateMember(${member.member_id})`}">
                                ${member.is_active ? 'Deactivate' : 'Reactivate'}
                            </button>
                        </td>
                    </tr>
                `;
            });

            table += '</table>';
            managementDiv.innerHTML = table;
        })
        .catch(error => console.error('Error:', error));
}


function loadTransactionManagement() {
    fetch('backend/get_all_transactions.php')
        .then(response => response.json())
        .then(data => {
            const transactionDiv = document.getElementById('transactionManagement');
            transactionDiv.innerHTML = '';

            if (data.error) {
                transactionDiv.innerHTML = `<p class="error">${data.error}</p>`;
                return;
            }

            if (data.length === 0) {
                transactionDiv.innerHTML = '<p>No transactions available.</p>';
                return;
            }

            let table = `<table>
                <tr>
                    <th>Transaction ID</th>
                    <th>Member ID</th>
                    <th>Member Name</th>
                    <th>Book IDs</th>
                    <th>Books</th>
                    <th>Borrow Date</th>
                    <th>Due Date</th>
                    <th>Return Date</th>
                    <th>Fine</th>
                </tr>`;

            data.forEach(transaction => {
                table += `
                    <tr>
                        <td>${transaction.transaction_id}</td>
                        <td>${transaction.member_id}</td>
                        <td>${transaction.full_name}</td>
                        <td>${transaction.book_ids.join(', ')}</td>
                        <td>${transaction.book_titles.join(', ')}</td>
                        <td>${transaction.borrow_date}</td>
                        <td>${transaction.due_date}</td>
                        <td>${transaction.return_date || 'Not returned'}</td>
                        <td>${transaction.fine || 0}</td>
                    </tr>
                `;
            });

            table += '</table>';
            transactionDiv.innerHTML = table;
        })
        .catch(error => console.error('Error:', error));
}
// Deactivate Member
function deactivateMember(memberId) {
    if (!confirm('Are you sure you want to deactivate this member?')) return;

    const formData = new FormData();
    formData.append('member_id', memberId);

    fetch('backend/librarian_functions.php?action=deactivate_member', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.success);
            loadMemberManagement();
        } else if (data.error) {
            alert(data.error);
        }
    })
    .catch(error => console.error('Error:', error));
}

// Reactivate Member
function reactivateMember(memberId) {
    const formData = new FormData();
    formData.append('member_id', memberId);

    fetch('backend/librarian_functions.php?action=reactivate_member', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.success);
            loadMemberManagement();
        } else if (data.error) {
            alert(data.error);
        }
    })
    .catch(error => console.error('Error:', error));
}

// Handle Borrow Books Form Submission
document.addEventListener('DOMContentLoaded', () => {
    const borrowBooksForm = document.getElementById('borrowBooksForm');
    if (borrowBooksForm) {
        borrowBooksForm.addEventListener('submit', function(event) {
            event.preventDefault();

            const member_id = document.getElementById('borrow_member_id').value.trim();
            const book_ids_input = document.getElementById('borrow_book_ids').value.trim();

            if (!member_id || !book_ids_input) {
                alert('Please fill in all required fields.');
                return;
            }

            const book_ids = book_ids_input.split(',').map(id => id.trim());

            const formData = new FormData();
            formData.append('member_id', member_id);
            book_ids.forEach(id => {
                formData.append('book_ids[]', id);
            });

            fetch('backend/librarian_functions.php?action=borrow_books', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.success);
                    borrowBooksForm.reset();
                } else if (data.error) {
                    alert(data.error);
                }
            })
            .catch(error => console.error('Error:', error));
        });
    }
});

// Handle Return Books Form Submission
document.addEventListener('DOMContentLoaded', () => {
    const returnBooksForm = document.getElementById('returnBooksForm');
    if (returnBooksForm) {
        returnBooksForm.addEventListener('submit', function(event) {
            event.preventDefault();

            const transaction_id = document.getElementById('return_transaction_id').value.trim();
            const book_ids_input = document.getElementById('return_book_ids').value.trim();

            if (!transaction_id || !book_ids_input) {
                alert('Please fill in all required fields.');
                return;
            }

            const book_ids = book_ids_input.split(',').map(id => id.trim());

            const formData = new FormData();
            formData.append('transaction_id', transaction_id);
            book_ids.forEach(id => {
                formData.append('book_ids[]', id);
            });

            fetch('backend/librarian_functions.php?action=return_books', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.success);
                    returnBooksForm.reset();
                } else if (data.error) {
                    alert(data.error);
                }
            })
            .catch(error => console.error('Error:', error));
        });
    }
});

// On Page Load, load book, member, and transaction management
document.addEventListener('DOMContentLoaded', () => {
    loadBookManagement();
    loadMemberManagement();
    loadTransactionManagement();
});

