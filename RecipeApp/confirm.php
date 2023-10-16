
<?php

session_start();

$pageName = "Authentication Confirmation";

require_once "header.php";

$state = $_GET['state'];

?>
<h1>Login Confirmation</h1>

<?php
if($state == 1){
    echo "You have been logged out.";
}
if($state == 2){
    echo "<p>Welcome back,<strong>{$_SESSION['fname']}</strong></p>";
}
?>



<?php
require_once "footer.php";
?>
