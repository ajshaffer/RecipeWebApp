<?php

$pageName = "Home";
session_start();

require_once "header.php";
require_once "connect.php";
require_once "functions.php";

$database = new Database(); // Instantiate the Database class
$pdo = $database->getConnection(); // Get the PDO connection object

$showForm = 1;
$errExists = 0;

$err_fname = "";
$err_lname = "";
$err_email = "";
$err_pwd = "";


class UserManager
{
    private $db;
    private $showForm = 1;

    public function __construct($pdo)
    {
        $this->db = $pdo;
    }

    public function registerUser($fname, $lname, $email, $pwd, $joined, $errExists, $showForm)
    {
        // This checks to see if the username already exists in the database
        if ($errExists == 1) {
            echo "<p class='error'>There are errors with your submission. Please make changes and re-submit.</p>";
        } else {
            $joined = date("Y-m-d H:i:s");

            $sql = "INSERT INTO users (fname, lname, email, pwd, joined) VALUES (:fname, :lname, :email, :pwd, :joined)";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':fname', $fname);
            $stmt->bindValue(':lname', $lname);
            $stmt->bindValue(':email', $email);
            $stmt->bindValue(':pwd', $pwd);
            $stmt->bindValue(':joined', $joined);

            $success = $stmt->execute(); // Execute the query and display whether it was successful or not

            if ($success) {
                echo "<p class='success'>Your account has been created!</p>";
                $_SESSION['showForm'] = 0;

            } else {
                echo "<p class='error'> Registration failed.</p>";
            }
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $fname = trim($_POST['fname']);
    $lname = trim($_POST['lname']);
    $email = trim(strtolower($_POST['new-email']));
    $pwd = $_POST['pwd'];
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

    $userManager->registerUser($fname, $lname, $email, $pwd_hashed, $joined, $errExists, $showForm);
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
    <div class="container">
        <h1>Welcome to CookTogether</h1>
        <div class="form-container">

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

                <button type="submit">Login</button>
            </form>

            <div class="separator"></div>

            <form id="signup-form" class="form" method="POST" action="index.php">

                <h2>Sign Up</h2>

                <div class="form-group">
                    <label for="fname">First Name:</label>
                    <input type="text" id="fname" name="fname" required placeholder="Enter your first name:" maxlength="30" value="<?php if(isset($fname)){ echo htmlspecialchars($fname);}?>">
                    <span class="error"> <?php echo $err_fname;?></span><br>
                </div>

                <div class="form-group">
                    <label for="lname">Last Name:</label>
                    <input type="text" id="lname" name="lname" required placeholder="Enter your last name:" maxlength="50" value="<?php if(isset($lname)){ echo htmlspecialchars($lname);}?>">
                    <span class="error"> <?php echo $err_lname;?></span><br><br>
                </div>

                <div class="form-group">
                    <label for="new-email">Email:</label>
                    <input type="email" id="new-email" name="new-email" required placeholder="Enter your email:" value="<?php if(isset($email)){ echo htmlspecialchars($email);}?>">
                    <span class="error"> <?php echo $err_email;?></span><br><br>
                </div>

                <div class="form-group">
                    <label for="pwd">Password:</label>
                    <input type="password" id="pwd" name="pwd" required placeholder="Enter a password:">
                    <span class="error"> <?php echo $err_pwd;?></span><br>
                </div>

                <button type="submit">Sign Up</button>
            </form>
        </div>
    </div>
</body>
</html>

<?php
}
  require_once "footer.php";
?>
