<?php

//Error Reporting
error_reporting(E_ALL);
ini_set('display_errors','1');

//Include Files
require_once "connect.php";
require_once "functions.php";

//Initial Variables
$currentFile = basename($_SERVER['SCRIPT_FILENAME']);
$rightNow = time();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>ajshaffer</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://cdn.tiny.cloud/1/5o7mj88vhvtv3r2c5v5qo4htc088gcb5l913qx5wlrtjn81y/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
    <script>tinymce.init({ selector:'textarea' });</script>
</head>
<body>
<header>
    <h1>Recipes and Such</h1>
    <div>
        <nav>
            <div class = "navbar-left">
                <?php
                    echo ($currentFile == "index.php") ? "<a href='index.php'>Home</a>" : "<a href='index.php'>Home</a>";
                    echo ($currentFile == "contentdisplay.php") ? "<a href='contentdisplay.php'>Local Talk</a>" : "<a href='contentdisplay.php'>Local Talk</a>";
                    if(isset($_SESSION['ID'])){
                        echo "<a href ='contentmanage.php'>Manage Your Posts</a>";
                    }
                    if(isset($_SESSION['ID'])){
                        echo "<a href ='contentadd.php'>Post</a>";
                    }

                ?>
            </div>
            <div class="navbar-right">
                <?php
                    echo ($currentFile == "contentsearch.php") ? "<a href='contentsearch.php'>Search</a>" : "<a href='contentsearch.php'>Search</a>";
                    if(isset($_SESSION['ID'])){
                        echo "<a href ='updateprofile.php'>Update Profile</a>";
                    }
                    echo (isset($_SESSION['ID'])) ? "<a href ='logout.php'>Log Out</a>" : "<a href ='login.php'>Login / Register</a>";
                ?>
            </div>
        </nav>
    </div>
</header>



















