<?php

$pageName = "User Registration";
session_start();

require_once "header.php";


$showForm = 1;
$errExists = 0;

$err_fname = "";
$err_lname = "";
$err_email = "";
$err_pwd = "";


class UserManager
{
    private $db;

    public function __construct()
    {
        $this -> db = new Database();
    }

    public function registerUser($username, $password)
    {
        $conn = $this -> db -> getConnection();

        //This checks to see if the username already exists in the database
        if ($this -> errExists == 1) {
        echo "<p class='error'>There are errors with your submission. Please make changes and re-submit.</p>";
    } else {
        $joined = date("Y-m-d H:i:s");

        $sql = "INSERT INTO users (fname, lname, email, pwd, joined) VALUES (:fname, :lname, :email, :pwd, :joined)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':fname', $fname);
        $stmt->bindValue(':lname', $lname);
        $stmt->bindValue(':email', $email);
        $stmt->bindValue(':pwd', $pwd_hashed);
        $stmt->bindValue(':joined', $joined);
        $stmt->execute();

        if ($stmt -> execute()){
            echo "<p class='success'>Your account has been created. Please log in to continue.</p>";
            echo "<ul style='list-style-type: none;'>
                <li><a href='login.php'>login</a></li>
             </ul>";

            $this -> showForm == 0; 
        } else {
            echo "<p class = 'error'> Registration failed.">
        }
    }

    }
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {

    $fname = trim($_POST['fname']);
    $lname = trim($_POST['lname']);
    $email = trim(strtolower($_POST['email']));
    $pwd = $_POST['pwd'];

    $userManager = new UserManager(); 

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
    } else{
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errExists = 1;
            $err_email .= "Email is invalid.";
        } else {
            $sql = "SELECT email FROM users WHERE email = :field";
            $dupemail = check_duplicates($pdo, $sql, $email);
            if ($dupemail){
                $errExists = 1;
                $err_email = "<span class = 'error'>The email is taken.</span>";
            }
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


    $userManager -> registerUser($fname, $lname, $email, $pwd, $joined);

}

if($showForm == 1){
    ?>

    <h1>Create an account</h1>
    <p><b>All fields except where indicated are required.</b></p>

    <form name="myform" id="myform" method="post" action="register.php">

        <label for="fname">First Name:</label>
        <input type="text" id="fname" name="fname" placeholder="Enter your first name:" maxlength="30" value="<?php if(isset($fname)){ echo htmlspecialchars($fname);}?>">
        <span class="error"> <?php echo $err_fname;?></span><br>


        <label for="lname">Last Name:</label>
        <input type="text" id="lname" name="lname" placeholder="Enter your last name:" maxlength="50" value="<?php if(isset($lname)){ echo htmlspecialchars($lname);}?>">
        <span class="error"> <?php echo $err_lname;?></span><br><br>
        <hr>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" placeholder="Enter your email:" value="<?php if(isset($email)){ echo htmlspecialchars($email);}?>">
        <span class="error"> <?php echo $err_email;?></span><br><br>

        <label for="pwd">Password:</label>
        <input type="password" id="pwd" name="pwd" placeholder="Enter a password:">
        <span class="error"> <?php echo $err_pwd;?></span><br>


        <input type="submit" id="submit" value="Submit">

    </form>

<?php
}
require_once "footer.php";
?>