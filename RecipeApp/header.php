<!-- <?php

//Error Reporting
error_reporting(E_ALL);
ini_set('display_errors','1');

//Include Files
require_once "connect.php";
require_once "functions.php";

//Initial Variables
$currentFile = basename($_SERVER['SCRIPT_FILENAME']);
$rightNow = time();

?> -->


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CookTogether</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://cdn.tiny.cloud/1/5o7mj88vhvtv3r2c5v5qo4htc088gcb5l913qx5wlrtjn81y/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
    <script>tinymce.init({ selector:'textarea' });</script>
</head>
<header>
    <div>
        <nav class="navbar navbar-expand-sm bg-dark navbar-dark">
            <div class = "navbar-left">
                <?php
                    if(isset($_SESSION['ID'])){
                        echo "<a href ='contentmanage.php'>Manage Your Posts</a>";
                    }
                    if(isset($_SESSION['ID'])){
                        echo "<a href ='contentadd.php'>Post</a>";
                    }
                    if(isset($_SESSION['ID'])){
                        echo "<a href='contentdisplay.php'>Feed</a>";
                    }

                ?>
            </div>
            <div class="navbar-right">
                <?php
                    if(isset($_SESSION['ID'])){
                        echo "<a href ='updateprofile.php'>Update Profile</a>";
                    }
                    if(isset($_SESSION['ID'])){
                        echo "<a href='contentsearch.php'>Search</a>";
                    }
                    if(isset($_SESSION['ID'])){
                        echo "<a href='logout.php'>Logout</a>";
                    }
                    
                    
                ?>
            </div>
        </nav>
    </div>
</header>


















