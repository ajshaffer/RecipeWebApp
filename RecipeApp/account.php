<?php

session_start();

$pageName = "User Profile";

require_once "connect.php";
require_once "functions.php";
require_once "header.php";

checkLogin();

$database = new Database(); # Instantiate the Database class
$pdo = $database->getConnection(); # Get the PDO connection object

$showForm = 1;
$errExists = 0;

$user_id = $_SESSION['ID'];
$user_name = $_SESSION['fname'];
$state = $_GET['state']; 

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <!-- Include your CSS styles here -->
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>User Profile</h1>
        <p>Welcome, <?php echo $user_name; ?>!</p>

        <!-- Display user information or allow for profile editing -->
        <div class="profile-info">
            <p>User ID: <?php echo $user_id; ?></p>
            <!-- Display other user details here -->
        </div>

        <div class="profile-actions">
            <a href="updateprofile.php">Edit Profile</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>

    <footer>
        <!-- Add footer content here -->
    </footer>
</body>
</html>
