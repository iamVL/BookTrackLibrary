# 📚 BookTrack Library Management System

A full-stack web application to manage library workflows — track book inventory, manage members, and streamline borrowing/returning. Built to minimize manual errors and cut search latency.

![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
![JavaScript](https://img.shields.io/badge/JavaScript-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black)
![PostgreSQL](https://img.shields.io/badge/PostgreSQL-4169E1?style=for-the-badge&logo=postgresql&logoColor=white)
![CSS](https://img.shields.io/badge/CSS3-1572B6?style=for-the-badge&logo=css3&logoColor=white)

---

## ✨ Features

- 🔐 **Login portal** for Librarians and Members  
- 🔍 **Real-time search** and filter by book titles, authors, or availability  
- 🔄 **Borrow & return tracking**, with timestamped transaction logs  
- 🧾 Librarian dashboard for full inventory + member access  
- 👤 Member dashboard to view borrowed books + due dates  
- 🧹 Optimized PostgreSQL schema for fast lookups and record updates  

---

## 🚀 Tech Stack

| Frontend         | Backend         | Database   |
|------------------|------------------|------------|
| HTML, CSS, JS    | PHP, REST APIs   | PostgreSQL |

---

## 🛠️ Setup Instructions

---

### ✅ Prerequisites

- PHP 7.x or higher  
- PostgreSQL 9.x or higher  
- Web server (e.g., Apache, XAMPP, or built-in PHP server)  
- Modern web browser  

---
## ⚙️ Installation & Setup Guide

## 📁 1. Clone the Repository

```bash
git clone https://github.com/iamVL/BookTrackLibrary.git
cd BookTrackLibrary
```

## 🗃️ 2. Set Up the Database
## In your PostgreSQL terminal:
```bash
CREATE ROLE your_username WITH LOGIN PASSWORD 'your_password';
CREATE DATABASE booktrack_library WITH OWNER your_username;
pg_restore -U your_username -d booktrack_library booktrack_library_backup.sqlc
```

## 🛠️ 3. Configure DB Connection
## Update backend/db_connect.php with:
```bash
$conn = pg_connect("host=localhost dbname=booktrack_library user=your_username password=your_password");
```

## 🚀 4. Run the App Locally
```bash
php -S localhost:8000
```
## Then open your browser at:
```bash
 http://localhost:8000/index.php
```

## 🔐 Demo Credentials

## 🧑‍🏫 Librarian
- Username:`admin`  
- Password: `adminpass`

## 🧑‍🎓 Member
- Username: `john_doe`  
- Password: `password123`

---


## 🧑‍💻 Team

- **Vaishnavi Lokhande** – Full Stack Dev, PostgreSQL Optimization  
- **Sathwin** – UI + PHP Backend  
---

## 📄 License

MIT License © 2025  
Feel free to fork, star ⭐ and use!
