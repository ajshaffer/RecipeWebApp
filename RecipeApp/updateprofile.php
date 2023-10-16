<?php

session_start();

$pageName = "Update Profile";
require_once "header.php";
require_once "connect.php";

checkLogin();

$database = new Database(); # Instantiate the Database class
$pdo = $database->getConnection(); # Get the PDO connection object

$showForm = 1;
$errExists = 0;
$err_email = "";
$err_pwd = "";


$ID = $_SESSION['ID'];

$err_fname = "";
$err_lname = "";
$err_email = "";

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

    //Variables
    $fname = trim($_POST['fname']);
    $lname = trim($_POST['lname']);
    $email = trim(strtolower($_POST['email']));
    $new_pwd = $_POST['new_pwd'];
    $confirm_pwd = $_POST['confirm_pwd'];


    //Error checking
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
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errExists = 1;
            $err_email .= "Email is invalid.";
        } elseif ($email != $_POST['origEmail']) {
            $sql = "SELECT email FROM students WHERE email = :field";
            $emailExists = check_duplicates($pdo, $sql, $email);
            if ($emailExists) {
                $errExists = 1;
                $err_email = "<span class = 'error'>That email is already taken. Please choose another email.</a>";
            }
        }
    }

    if (empty($new_pwd) || empty($confirm_pwd)) {
        $errExists = 1;
        $err_pwd = "Missing password.<br>";
    } else if (strlen($new_pwd) < 10) {
        $errExists = 1;
        $err_pwd .= " Password must be at least 10 characters in length.";
    }

    if (empty($confirm_pwd)) {
        $errExists = 1;
        $err_pwd = "Missing password.<br>";
    } else if (strlen($confirm_pwd) < 10) {
        $errExists = 1;
        $err_pwd .= " Password must be at least 10 characters in length.";
    }

    if ($new_pwd != $confirm_pwd) {
        $errExists = 1;
        $err_pwd .= " Passwords do not match.";
    } else {
        $pwd_hashed = password_hash($confirm_pwd, PASSWORD_DEFAULT);
    }





    //Submitting the form
    if ($errExists == 1) {
        echo "<p class='error'>There are errors with your submission. Please make changes and re-submit.</p>";
    } else {
        $sql = "UPDATE users SET fname = :fname, lname = :lname, email = :email, pwd = :pwd  WHERE ID = :ID";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':fname', $fname);
        $stmt->bindValue(':lname', $lname);
        $stmt->bindValue(':email', $email);
        $stmt->bindValue(':pwd', $pwd_hashed);
        $stmt->bindValue(':ID', $ID);
        $result = $stmt->execute();
        if ($result) {
            echo "<p class='success'>Your information has been updated.</p>";
            $showForm = 0;
        } else {
            echo "There was an error.";
        }
    }
}//Submit form

if($showForm == 1){
    $sql = "SELECT * FROM users WHERE user_id = :ID";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':ID', $ID);
    $stmt->execute();
    $row = $stmt->fetch();

    $fname = $row['fname'];
    ?>

<h1>Update Your Profile</h1>
    <form name="myFormUpdate" id="myFormUpdate" method="post" action="updateprofile.php">

        <label for="fname">First Name:</label>
        <input type="text" id="fname" name="fname" placeholder="Enter your first name:" maxlength="30" value="<?php if(isset($fname)){echo htmlspecialchars($fname);}else{echo htmlspecialchars($row['fname']);}?>">
        <span class="error"> <?php echo $err_fname;?></span><br>

        <label for="lname">Last Name:</label>
        <input type="text" id="lname" name="lname" placeholder="Enter your last name:" maxlength="50" value="<?php if(isset($lname)){echo htmlspecialchars($lname);}else{echo htmlspecialchars($row['lname']);}?>">
        <span class="error"> <?php echo $err_lname;?></span><br><br>

        <hr>

        <div class="form-field">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" placeholder="Enter your email:" value="<?php if(isset($email)){echo htmlspecialchars($email);}else{echo htmlspecialchars($row['email']);}?>">
            <span class="error"> <?php if(!empty($err_email)) echo $err_email;?></span><br><br>
        </div>

        <hr>

        <!--Hidden Fields-->
        <input type="hidden" name="origEmail" value="<?php echo $row['email'];?>">
        <input type="hidden" name="ID" value="<?php echo $row['user_id'];?>">



        <label for="new_pwd">New password:</label>
        <input type="password" id="new_pwd" name="new_pwd" placeholder="Enter your new password" size="40"><span class="error"> <?php echo $err_pwd;?></span>
        <br>
        <label for="confirm_pwd">Confirm password:</label>
        <input type="password" id="confirm_pwd" name="confirm_pwd" placeholder="Confirm password" size="40"><span class="error"> <?php echo $err_pwd;?></span>


        <br>
        <input type="submit" id="submit" value="Submit">

    </form>

    <?php
}

require_once "footer.php";
?>
