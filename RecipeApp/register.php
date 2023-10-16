<?php

$pageName = "Registration";
session_start();

require_once "connect.php";
require_once "functions.php";

$database = new Database(); # Instantiate the Database class
$pdo = $database->getConnection(); # Get the PDO connection object

$showForm = 1;
$errExists = 0;

$err_fname = "";
$err_lname = "";
$err_email = "";
$err_pwd = "";
$err_login = "";


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
            $this->err_login = "There's an error with your registration.";
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
            $_SESSION['email'] = $user['email'];
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
            $err_email = "<span class='error'>The email is taken. Please choose a different email.</span>";
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
        $err_login = "There's an error with your login.<br>";
    } else {    
        # Registers user
        $userManager->registerUser($fname, $lname, $email, $pwd_hashed, $joined, $errExists, $showForm);

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
    <title>Registration</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>


<div class="container">
    <div class = "left-side left-side-registration">
        <!-- Image placed here with CSS -->
    </div>

        <div class="right-side">
            <div class = "right-side-text">
                <h1>Registration</h1>
            </div>

            <div class = "signup-form">
                <!-- Form signup -->
                <form id="signup-form" class="form" method="POST" action="register.php">

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
                        <?php
                            if (!empty($err_email)) {
                                echo "<div class='error'>$err_email</div>";
                            }
                        ?>
                    </div>

                    <div class="form-group">
                        <label for="pwd">Password:</label>
                        <input type="password" id="pwd" name="pwd" required placeholder="Enter a password:">
                        <span class="error"> <?php echo $err_pwd;?></span><br>
                    </div>

                    <button type="submit" name = "signup">Sign Up</button>
                    
                    <div class = "login-link">
                        <p>Already have an account? <a href="index.php">Login</a>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>  

</body>
</html>

<?php
}

require_once "footer.php"
?>
