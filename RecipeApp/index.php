<?php

$pageName = "Home";
session_start();

require_once "connect.php";
require_once "functions.php";


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
    $userManager->login($email, $pwd);

    $fname = isset($_POST['fname']) ? trim($_POST['fname']) : '';
    $lname = isset($_POST['lname']) ? trim($_POST['lname']) : '';
    $email = isset($_POST['new-email']) ? trim(strtolower($_POST['new-email'])) : '';
    $pwd = isset($_POST['pwd']) ? $_POST['pwd'] : '';
    $joined = date("Y-m-d H:i:s");

    $userManager = new UserManager($pdo);

    if (empty($fname)) {
        $errExists = 1;
        $err_fname = "Missing first name.<br>";
    }

    if (empty($lname)) {
        $errExists = 1;
        $err_lname = "Missing last name.<br>";
    }

    if (empty($email)) {
        $errExists = 1;
        $err_email = "Missing email.<br>";
    } else {
        $sql = "SELECT email FROM users WHERE email = :field";
        if (check_duplicates($pdo, $sql, $email)) {
            $errExists = 1;
            $err_email = "<span class='error'>The email is taken.</span>";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errExists = 1;
            $err_email .= "Email is invalid.";
        }
    }

    if (empty($pwd)) {
        $errExists = 1;
        $err_pwd = "Missing password.<br>";
    } else if (strlen($pwd) < 10) {
        $errExists = 1;
        $err_pwd .= "Password must be at least 10 characters in length.";
    }
    $pwd_hashed = password_hash($pwd, PASSWORD_DEFAULT);

    if ($errExists == 1) {
        echo "<p class='error'>There are errors with your submission. Please make changes and re-submit.</p>";
    } else {    
        # Logs user in
        $userManager->login($email, $pwd);

        $showForm = 0;
    }
}


if($showForm == 1){
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CookTogether</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<div class = "container">
    <div class = "left-side left-side-login"> 
        <!-- Image placed here with CSS -->
    </div>

    <div class="right-side">
        <div class = "right-side-text">
            <h1>Welcome to LetsCook</h1>
            <p>Cook at <u>home</u>. Share <u>recipes</u>. Save <u>money</u>.</p>
        </div>

        <div class = "login-form">
        <!-- Form login -->
            <form id="login-form" class="form" method="POST" action="index.php">

                <h2>Login</h2>

                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required placeholder="Enter your email:" value="<?php if(isset($email)){ echo htmlspecialchars($email);}?>" size="30">
                    <span class="error"> <?php echo $err_email;?></span><br><br>
                </div>

                <div class="form-group">
                    <label for="pwd">Password:</label>
                    <input type="password" id="pwd" name="pwd" required placeholder="Enter your password:" size="30">
                    <span class="error"> <?php echo $err_pwd;?></span><br>
                </div>

                <button type="submit" name = "login">Login</button>

                <div class = "register-link">
                    <p>Don't have an account? 
                    <a href="register.php">Sign up</a></p>
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
