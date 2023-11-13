<?php

session_start();

$pageName = "User Profile";

require_once "connect.php";
require_once "functions.php";
require_once "header.php";
require_once "../classes/userManager.class.php";

checkLogin();

$database = new Database(); # Instantiate the Database class
$pdo = $database->getConnection(); # Get the PDO connection object
$userManager = new UserManager($pdo);

$user_id = $_SESSION['ID'];
$user_name = $_SESSION['fname'];

$userManager->getProfileInfo($user_id);

?>

<body>
    <div class = "container profile-page">
        <div class = "row about">

            <div class = "col profile-pic username">
                <div class = "intro">
                    <h2>Hi! I'm <?php echo $user_name; ?>
                    <a href="updateprofile.php">
                        <img src="../images/edit_FILL0_wght400_GRAD0_opsz24.png" alt="Edit About" id="editAboutBtn" class="edit-about-button">
                    </a>
                    </h2>
                </div>


                <div class = "profile-pic">
                    <?php
                        $profilePic = $_SESSION['profile_pic'] ?? "../images/default_profile.jpg";
                        ?>
                    <img src="<?php echo $profilePic; ?>" alt="Profile Picture" class="profileImage">
    
                </div>
            </div>



            <div class = "col about">
                <h3>About Me</h3>
                <div class = "about-section">
                    <p>
                        <?php echo $_SESSION['profileAbout'];?>
                    </p>
                </div>
            </div>

        </div>
    </div>
</body>
