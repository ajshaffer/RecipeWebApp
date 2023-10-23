<?php

$pageName = "Home";
session_start();

require_once "connect.php";
require_once "functions.php";
require_once "header.php";


$database = new Database(); # Instantiate the Database class
$pdo = $database->getConnection(); # Get the PDO connection object

$showForm = 1;
$errExists = 0;

$err_email = "";
$err_pwd = "";

class UserManager
{
    private $db;
    public $err_login;

    public function __construct($pdo)
    {
        $this->db = $pdo;
        $this->err_login = "";
    }

    public function registerUser($fname, $lname, $email, $pwd, $joined)
    {
        $joined = date("Y-m-d H:i:s");
        $sql = "INSERT INTO users (fname, lname, email, pwd, joined) VALUES (:fname, :lname, :email, :pwd, :joined)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':fname', $fname);
        $stmt->bindValue(':lname', $lname);
        $stmt->bindValue(':email', $email);
        $stmt->bindValue(':pwd', $pwd);
        $stmt->bindValue(':joined', $joined);

        if ($stmt->execute()) {
            return "success"; // You can return a success message
        } else {
            throw new Exception("Registration failed.");
        }
    }

    public function login($email, $pwd)
    {
        $sql = "SELECT * FROM users WHERE email = :email";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':email', $email);
        $stmt->execute();  

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // SET SESSION VARIABLES
            $_SESSION['ID'] = $user['user_id'];
            $_SESSION['fname'] = $user['fname'];
            $_SESSION['status'] = $user['status'];
        
            // REDIRECT TO CONFIRMATION PAGE
            header("Location: account.php?state=2");
        } else {
            $this->err_login = "The email could not be found.<br> You must register first before logging in.";
        }
    }
}



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
        $userManager->login($email, $pwd);
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
                    ?>
                </div>

                <button type="submit" name="login">Login</button>

                <div class="register-link">
                    <p>Don't have an account? <a href="register.php">Sign up</a></p>
                    <?php
                        if (!empty($userManager->err_login)) {
                            echo "<div class='error'>" . $userManager->err_login . "</div>";
                        }
                    ?>
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
