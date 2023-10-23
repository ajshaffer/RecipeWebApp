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
    <div class="container">
        <h1>User Profile</h1>
        <p>Welcome, <?php echo $user_name; ?>!</p>
    </div>

</body>
</html>
