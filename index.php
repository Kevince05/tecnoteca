<?php
session_start();

// Check if user is not logged in
if (!isset($_SESSION['usr'])) {
    header("Location: login.php");
    exit;
}

// Logout functionality
if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Website</title>
    <link rel="stylesheet" type="text/css" href="style/index_style.css">
</head>
<body>
    <!-- Your page content here -->
    <header>
        <form method="post" action="">
            <input type="submit" name="logout" value="Logout">
        </form>
    </header>
</body>
</html>
