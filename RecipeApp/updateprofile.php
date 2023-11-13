<?php

session_start();

$pageName = "Update Profile";

require_once "header.php";
require_once "connect.php";
include_once "../classes/userManager.class.php";

checkLogin();

$database = new Database(); # Instantiate the Database class
$pdo = $database->getConnection(); # Get the PDO connection object

$showForm = 1;
$errExists = 0;

$err_fname = "";
$err_lname = "";
$err_email = "";
$err_pwd = "";
$err_about = "";
$err_profilepic = "";

$ID = $_SESSION['ID'];



if ($_SERVER['REQUEST_METHOD'] == "POST") {

    //Variables
    $fname = trim($_POST['fname']);
    $lname = trim($_POST['lname']);
    $email = trim(strtolower($_POST['email']));
    $profileAbout = trim($_POST['about']);

    $new_pwd = $_POST['new_pwd'];
    $confirm_pwd = $_POST['confirm_pwd'];
    $pwd_hashed = ''; 
    $change_password = !empty($new_pwd) || !empty($confirm_pwd);



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

    if ($change_password) {
        if (empty($new_pwd) || empty($confirm_pwd)) {
            $errExists = 1;
            $err_pwd = "Missing password.<br>";
        } elseif (strlen($new_pwd) < 10) {
            $errExists = 1;
            $err_pwd .= " Password must be at least 10 characters in length.";
        }

        if (empty($confirm_pwd)) {
            $errExists = 1;
            $err_pwd = "Missing password confirmation.<br>";
        } elseif (strlen($confirm_pwd) < 10) {
            $errExists = 1;
            $err_pwd .= " Password must be at least 10 characters in length.";
        }

        if ($new_pwd != $confirm_pwd) {
            $errExists = 1;
            $err_pwd .= " Passwords do not match.";
        } else {
            $pwd_hashed = password_hash($confirm_pwd, PASSWORD_DEFAULT);
        }
    }





    //Submitting the form
    if ($errExists == 1) {
        echo "<p class='error'>There are errors with your submission. Please make changes and re-submit.</p>";
    } else {
        $sql = "UPDATE profiles SET profile_about = :profile_about WHERE user_id = :ID";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':profile_about', $profileAbout);
        $stmt->bindValue(':ID', $ID);

        if (!$stmt->execute()) {
            $stmt = null;
            header("Location: updateprofile.php?error=stmtfailed");
            exit();
        }

        $sql = "UPDATE users SET fname = :fname, lname = :lname, email = :email, pwd = :pwd  WHERE user_id = :ID";
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
    $sql = "SELECT users.*, profiles.profile_about 
            FROM users
            LEFT JOIN profiles ON users.user_id = profiles.user_id
            WHERE users.user_id = :ID";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':ID', $ID);
    $stmt->execute();
    $row = $stmt->fetch();

    $fname = $row['fname'];
    ?>


<div class = "container-fluid update-profile">
    <div class="update_profile">
        <h1>Update Your Profile</h1>
    
    <div class="form-group">
        <form name="myFormUpdate" id="myFormUpdate" method="post" action="updateprofile.php" enctype="multipart/form-data">

            <label for="profile_pic">Profile Picture:</label>
            <input type="file" id="profile_pic" name="profile_pic" accept="image/*">
            <span class="error"> <?php echo $err_profilepic;?></span>


            <div class = "name_change">
                <label for="fname">First Name:</label>
                <input type="text" id="fname" name="fname" placeholder="Enter your first name:" maxlength="30" value="<?php if(isset($fname)){echo htmlspecialchars($fname);}else{echo htmlspecialchars($row['fname']);}?>">
                <span class="error"> <?php echo $err_fname;?></span><br>
            </div>

            <label for="lname">Last Name:</label>
            <input type="text" id="lname" name="lname" placeholder="Enter your last name:" maxlength="50" value="<?php if(isset($lname)){echo htmlspecialchars($lname);}else{echo htmlspecialchars($row['lname']);}?>">
            <span class="error"> <?php echo $err_lname;?></span>

            <hr>

            <div class = "about">
                <label for="about">About Me:</label>
                <textarea id="about" name="about" maxlength="1000"><?php echo nl2br(htmlspecialchars($row['profile_about'])); ?></textarea>
                <span class="error"> <?php echo $err_about;?></span>
            </div>


            <hr>


            <div class="form-field">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" placeholder="Enter your email:" value="<?php if(isset($email)){echo htmlspecialchars($email);}else{echo htmlspecialchars($row['email']);}?>">
                <span class="error"> <?php if(!empty($err_email)) echo $err_email;?></span>
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


            <button type="submit" class="btn btn-primary">Submit</button>

        </form>

        </div>
    </div>

</div>
    <?php
   
}

require_once "footer.php";
?>
