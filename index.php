<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>BookTrack Library - Login</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>BookTrack Library</h1>
        <form action="backend/login.php" method="POST">
            <input type="text" name="username" placeholder="Username" required><br>
            <input type="password" name="password" placeholder="Password" required><br>
            <button type="submit">Login</button>
        </form>
        <?php
        session_start();
        if (isset($_SESSION['error'])) {
            echo '<p class="error">' . htmlspecialchars($_SESSION['error']) . '</p>';
            unset($_SESSION['error']);
        }
        ?>
    </div>
</body>
</html>
