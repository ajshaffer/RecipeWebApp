<?php

require_once "connect.php";
require_once "functions.php";

$currentFile = basename($_SERVER['SCRIPT_FILENAME']);
$rightNow = time();

?> 


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://cdn.tiny.cloud/1/5o7mj88vhvtv3r2c5v5qo4htc088gcb5l913qx5wlrtjn81y/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script>tinymce.init({ selector:'textarea' });</script>
</head>


<header>
        <nav class="navbar navbar-expand-lg bg-dark navbar-dark">
            <?php
            echo '<a class="navbar-brand" href="#">LetsCook</a>';
            if (isset($_SESSION['ID'])) {
                echo "<a href='#'>Feed</a>";
                echo "<a href ='#'>Post</a>";
                echo "<a href ='#'>Manage Your Posts</a>";
                echo "<a href='profile.php'>Profile</a>";
                
                if ($currentFile == 'profile.php'){
                    echo "<a href ='updateprofile.php'>Update Profile</a>";
                }

                echo '<ul class="navbar-nav ml-auto">';
                
                // Search form
                echo '<li class="nav-item-search bg-dark">
                            <form class="form-inline my-2 my-lg-0 bg-dark search-button">
                                <input class="form-control mr-sm-2" type="search" placeholder="Search..." aria-label="Search">
                                <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
                            </form>
                        </li>';

                // Logout button
                echo '<li class="nav-item-logout">
                            <a href="logout.php" class="nav-link bg-dark navbar-dark logout-button">Logout</a>
                        </li>';

                echo '</ul>';
            }
            ?>
        </nav>

</header>


















