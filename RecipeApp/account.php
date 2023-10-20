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

?>





<body>
    <div class="body-container">
        <h1>User Profile</h1>
        <p>Welcome, <?php echo $user_name; ?>!</p>

        <div class="profile-info">
            <p>User ID: <?php echo $user_id; ?></p>
        </div>
    </div>

</body>
</html>
