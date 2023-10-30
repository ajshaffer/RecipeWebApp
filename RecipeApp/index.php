<?php

$pageName = "Home";
session_start();

require_once "connect.php";
require_once "functions.php";
require_once "header.php";

include "../classes/userManager.class.php";


$database = new Database(); # Instantiate the Database class
$pdo = $database->getConnection(); # Get the PDO connection object

$showForm = 1;
$errExists = 0;

$err_email = "";
$err_pwd = "";


if ($_SERVER['REQUEST_METHOD'] == "POST") {

    $email = isset($_POST['email']) ? trim(strtolower($_POST['email'])) : '';
    $pwd = isset($_POST['pwd']) ? $_POST['pwd'] : '';

    $userManager = new UserManager($pdo);

    $joined = date("Y-m-d H:i:s");

    if (empty($email)) {
        $errExists = 1;
        $err_email = "Please enter your email.<br>";
    }

    if (empty($pwd)) {
        $errExists = 1;
        $err_pwd = "Missing password.<br>";
    }

    if ($errExists == 1) {
        $err_login .= "There's an error with your login.<br>";
    } else {    
        # Logs user in
        $loginResult = $userManager->login($email, $pwd);

        if ($loginResult === "success") {
        } else {
            $err_login = $loginResult; 
        }
    }
}


if($showForm == 1){
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<div class="container-fluid">

    <div class = "left-side left-side-login">
        <!-- Image placed here with CSS -->
    </div>

    <div class="right-side">
        <div class="right-side-text">
            <h1>Welcome to LetsCook</h1>
            <p>Cook at <u>home</u>. Share <u>recipes</u>. Save <u>money</u>.</p>
        </div>
        <div class="login-form">
            <h2>Login</h2>
            <?php
                if (!empty($err_email) || !empty($err_pwd)) {
                    echo "<div class='error'>There are errors with your submission.<br>Please make changes and re-submit.</div>";
                }
            ?>
            <form id="login-form" class="form" method="POST" action="index.php">
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required placeholder="Enter your email:" value="<?php if (isset($email)) {
                        echo htmlspecialchars($email);
                    } ?>" size="30">
                    <?php
                        if (!empty($err_email)) {
                            echo "<div class='error'>$err_email</div>";
                        }
                    ?>
                </div>
                <div class="form-group">
                    <label for="pwd">Password:</label>
                    <input type="password" id="pwd" name="pwd" required placeholder="Enter your password:" size="30">
                    <?php
                         if (!empty($err_pwd)) {
                            echo "<div class='error'>$err_pwd</div>";
                        }
                        if (!empty($userManager->err_login)) {
                            echo "<div class='error'>" . $userManager->err_login . "</div>";
                        }

                    ?>
                </div>

                <button type="submit" name="login">Login</button>

                <div class="register-link">
                    <p>Don't have an account? <a href="register.php">Sign up</a></p>
                </div>
            </form>
        </div>
    </div>
</div>


</body>
</html>

<?php
}
  require_once "footer.php";
?>
