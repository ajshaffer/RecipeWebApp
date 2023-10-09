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




if ($_SERVER['REQUEST_METHOD'] == "POST") {
    // User submitted the signup form
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

    # Registers user
    $userManager->registerUser($fname, $lname, $email, $pwd_hashed, $joined, $errExists, $showForm);

    $showForm = 0;
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
    <div class = "left-side left-side-registration">
        <!-- Image placed here with CSS -->
    </div>

        <div class="right-side">
            <div class = "right-side-text">
                <h2>Registration</h2>
            </div>

            <div class = "signup-form">
                <!-- Form signup -->
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
