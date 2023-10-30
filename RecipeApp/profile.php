<?php

session_start();

$pageName = "User Profile";

require_once "connect.php";
require_once "functions.php";
require_once "header.php";

checkLogin();

$database = new Database(); # Instantiate the Database class
$pdo = $database->getConnection(); # Get the PDO connection object

$user_id = $_SESSION['ID'];
$user_name = $_SESSION['fname'];

?>

<body>

<div class = 'container profile-page'>
    <h3>ABOUT</h3>
    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Excepturi ullam cum consectetur quae quam quas dolore amet veniam vel illum, expedita delectus? Reprehenderit autem alias quidem. Illum tenetur sit autem!</p>

    <h3>Hi! I'm <?php echo $user_name; ?> </h3>
    <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Repellendus provident veniam consequatur, laudantium animi et, recusandae, vitae perferendis ad sunt facere distinctio. Eos voluptates culpa amet non rem consequatur beatae.</p><br><br>
</div>



</body>
</html>
