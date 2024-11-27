// librarian.js

(function() {
    // Logout Function
    function logout() {
        window.location.href = 'backend/logout.php';
    }

    window.logout = logout;

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
                    Swal.fire({
                        icon: 'warning',
                        title: 'Incomplete Data',
                        text: 'Please fill in all required fields.'
                    });
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
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: data.success,
                            showConfirmButton: false,
                            timer: 1500
                        });
                        $('#addBookModal').modal('hide');
                        addBookForm.reset();
                        loadBookManagement();
                    } else if (data.error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: data.error
                        });
                    }
                })
                .catch(error => console.error('Error:', error));
            });
        }

        // Handle Add Member Form Submission
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
                    Swal.fire({
                        icon: 'warning',
                        title: 'Incomplete Data',
                        text: 'Please fill in all required fields.'
                    });
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
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: data.success,
                            showConfirmButton: false,
                            timer: 1500
                        });
                        $('#addMemberModal').modal('hide');
                        addMemberForm.reset();
                        loadMemberManagement();
                    } else if (data.error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: data.error
                        });
                    }
                })
                .catch(error => console.error('Error:', error));
            });
        }

        // Handle Borrow Books Form Submission
        const borrowBooksForm = document.getElementById('borrowBooksForm');
        if (borrowBooksForm) {
            borrowBooksForm.addEventListener('submit', function(event) {
                event.preventDefault();

                const member_id = document.getElementById('borrow_member_id').value.trim();
                const book_ids_input = document.getElementById('borrow_book_ids').value.trim();

                if (!member_id || !book_ids_input) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Incomplete Data',
                        text: 'Please fill in all required fields.'
                    });
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
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: data.success,
                            showConfirmButton: false,
                            timer: 1500
                        });
                        borrowBooksForm.reset();
                        loadTransactionManagement();
                    } else if (data.error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: data.error
                        });
                    }
                })
                .catch(error => console.error('Error:', error));
            });
        }

        // Handle Return Books Form Submission
        const returnBooksForm = document.getElementById('returnBooksForm');
        if (returnBooksForm) {
            returnBooksForm.addEventListener('submit', function(event) {
                event.preventDefault();

                const transaction_id = document.getElementById('return_transaction_id').value.trim();
                const book_ids_input = document.getElementById('return_book_ids').value.trim();

                if (!transaction_id || !book_ids_input) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Incomplete Data',
                        text: 'Please fill in all required fields.'
                    });
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
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: data.success,
                            showConfirmButton: false,
                            timer: 1500
                        });
                        returnBooksForm.reset();
                        loadTransactionManagement();
                    } else if (data.error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: data.error
                        });
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
                    managementDiv.innerHTML = `<div class="alert alert-danger">${data.error}</div>`;
                    return;
                }

                if (data.length === 0) {
                    managementDiv.innerHTML = '<p>No books available.</p>';
                    return;
                }

                let table = `<table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Book ID</th>
                            <th>Title</th>
                            <th>Author</th>
                            <th>Genre</th>
                            <th>ISBN</th>
                            <th>Total Copies</th>
                            <th>Available Copies</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>`;

                data.forEach(book => {
                    table += `
                        <tr>
                            <td>${book.book_id}</td>
                            <td>${book.title}</td>
                            <td>${book.author}</td>
                            <td>${book.genre || 'N/A'}</td>
                            <td>${book.isbn}</td>
                            <td>${book.total_copies}</td>
                            <td>${book.available_copies}</td>
                            <td>
                                <button class="btn btn-danger btn-sm" onclick="deleteBook(${book.book_id})">
                                    <i class="fas fa-trash-alt"></i> Delete
                                </button>
                            </td>
                        </tr>
                    `;
                });

                table += '</tbody></table>';
                managementDiv.innerHTML = table;
            })
            .catch(error => console.error('Error:', error));
    }

    window.deleteBook = function(bookId) {
        Swal.fire({
            title: 'Are you sure?',
            text: 'This book will be permanently deleted.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                const formData = new FormData();
                formData.append('book_id', bookId);

                fetch('backend/librarian_functions.php?action=delete_book', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted!',
                            text: data.success,
                            showConfirmButton: false,
                            timer: 1500
                        });
                        loadBookManagement();
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

    // Load Member Management
    function loadMemberManagement() {
        fetch('backend/get_all_members.php')
            .then(response => response.json())
            .then(data => {
                const managementDiv = document.getElementById('memberManagement');
                managementDiv.innerHTML = '';

                if (data.error) {
                    managementDiv.innerHTML = `<div class="alert alert-danger">${data.error}</div>`;
                    return;
                }

                if (data.length === 0) {
                    managementDiv.innerHTML = '<p>No members available.</p>';
                    return;
                }

                let table = `<table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Member ID</th>
                            <th>Username</th>
                            <th>Full Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Address</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>`;

                data.forEach(member => {
                    table += `
                        <tr>
                            <td>${member.member_id}</td>
                            <td>${member.username}</td>
                            <td>${member.full_name}</td>
                            <td>${member.email}</td>
                            <td>${member.phone || 'N/A'}</td>
                            <td>${member.address || 'N/A'}</td>
                            <td>${member.is_active ? 'Active' : 'Inactive'}</td>
                            <td>
                                <button class="btn btn-${member.is_active ? 'warning' : 'success'} btn-sm" onclick="${member.is_active ? `deactivateMember(${member.member_id})` : `reactivateMember(${member.member_id})`}">
                                    <i class="fas fa-${member.is_active ? 'user-slash' : 'user-check'}"></i> ${member.is_active ? 'Deactivate' : 'Reactivate'}
                                </button>
                            </td>
                        </tr>
                    `;
                });

                table += '</tbody></table>';
                managementDiv.innerHTML = table;
            })
            .catch(error => console.error('Error:', error));
    }

    window.deactivateMember = function(memberId) {
        Swal.fire({
            title: 'Are you sure?',
            text: 'This member will be deactivated.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, deactivate!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                const formData = new FormData();
                formData.append('member_id', memberId);

                fetch('backend/librarian_functions.php?action=deactivate_member', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Deactivated!',
                            text: data.success,
                            showConfirmButton: false,
                            timer: 1500
                        });
                        loadMemberManagement();
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

    window.reactivateMember = function(memberId) {
        const formData = new FormData();
        formData.append('member_id', memberId);

        fetch('backend/librarian_functions.php?action=reactivate_member', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Reactivated!',
                    text: data.success,
                    showConfirmButton: false,
                    timer: 1500
                });
                loadMemberManagement();
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

    // Load Transaction Management
    function loadTransactionManagement() {
        fetch('backend/get_all_transactions.php')
            .then(response => response.json())
            .then(data => {
                const transactionDiv = document.getElementById('transactionManagement');
                transactionDiv.innerHTML = '';

                if (data.error) {
                    transactionDiv.innerHTML = `<div class="alert alert-danger">${data.error}</div>`;
                    return;
                }

                if (data.length === 0) {
                    transactionDiv.innerHTML = '<p>No transactions available.</p>';
                    return;
                }

                let table = `<table class="table table-bordered table-hover">
                    <thead>
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
                        </tr>
                    </thead>
                    <tbody>`;

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
                            <td>$${transaction.fine || '0.00'}</td>
                        </tr>
                    `;
                });

                table += '</tbody></table>';
                transactionDiv.innerHTML = table;
            })
            .catch(error => console.error('Error:', error));
    }

    // On Page Load
    document.addEventListener('DOMContentLoaded', () => {
        loadBookManagement();
        loadMemberManagement();
        loadTransactionManagement();
    });
})();
