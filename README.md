# BookTrack Library Management System

## Introduction

BookTrack Library is a web-based application for managing library operations, including book inventory, member management, transactions, and more.

## Prerequisites

- PHP 7.x or higher
- PostgreSQL 9.x or higher
- Web server (e.g., Apache)
- Web browser

## Setup Instructions

1. **Clone the Repository or Copy Files**

   Copy all project files to your web server directory (e.g., `htdocs/BookTrackLibrary`).

2. **Set Up the Database**

   - **Create Database and User:**

     ```sql
     CREATE ROLE your_username WITH LOGIN PASSWORD 'your_password';
     CREATE DATABASE booktrack_library WITH OWNER your_username;
     ```

   - **Restore Database from Dump:**

     ```bash
     pg_restore -U your_username -d booktrack_library path/to/booktrack_library_backup.sqlc
     ```

3. **Configure Database Connection**

   - Update `backend/db_connect.php` with your database credentials.

4. **Run the Application**

   - Navigate to `http://localhost/BookTrackLibrary/index.php` in your web browser.

5. **Login Credentials**

   - **Librarian:**
     - Username: `admin`
     - Password: `adminpass`
   - **Member:**
     - Username: `john_doe`
     - Password: `password123`
