<?php

session_start();

$pageName = "User Profile";

require_once "connect.php";
require_once "functions.php";
require_once "header.php";

checkLogin();

$database = new Database(); # Instantiate the Database class
$pdo = $database->getConnection(); # Get the PDO connection object

$user_id = $_SESSION['ID'];
$user_name = $_SESSION['fname'];

?>

<body>
    <div class = "container profile-page">
        <div class = "row about">

            <div class = "col profile-pic username">
                <div class = "intro">
                    <h1>Hi! I'm <?php echo $user_name; ?>
                    <a href="updateprofile.php">
                        <img src="../images/edit_FILL0_wght400_GRAD0_opsz24.png" alt="Edit About" id="editAboutBtn" class="edit-about-button">
                    </a>
                    </h1>
                </div>


                <div class = "profile-pic">
                    <img src="../images/istockphoto-610003972-612x612.jpg" alt="Profile Picture" class = "profileImage">
                </div>
            </div>



            <div class = "col about">
                <h3>About Me
                </h3>
                <p>
                    <?php echo $profileAbout; ?>
                </p>
            </div>

        </div>
    </div>
</body>
