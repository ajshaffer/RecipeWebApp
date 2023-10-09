<?php


function check_duplicates($pdo, $sql, $field)
{
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':field', $field);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return ($row !== false); // Return true if a row was found (duplicate), false otherwise
}


function checkLogin()
{
    if (!isset($_SESSION['ID'])) {
        echo "<p class='error'>This page requires authentication.  Please log in to view details.</p>";
        require_once "footer.php";
        exit();
    }
}

class UserManager
{
    private $db;
    private $showForm = 1;

    public function __construct($pdo)
    {
        $this->db = $pdo;
    }

    public function registerUser($fname, $lname, $email, $pwd, $joined, $errExists, $showForm)
    {
        # This checks to see if the username already exists in the database
        if ($errExists == 1) {
            echo "<p class='error'>There are errors with your submission. Please make changes and re-submit.</p>";
        } else {
            $joined = date("Y-m-d H:i:s");

            $sql = "INSERT INTO users (fname, lname, email, pwd, joined) VALUES (:fname, :lname, :email, :pwd, :joined)";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':fname', $fname);
            $stmt->bindValue(':lname', $lname);
            $stmt->bindValue(':email', $email);
            $stmt->bindValue(':pwd', $pwd);
            $stmt->bindValue(':joined', $joined);

            $success = $stmt->execute(); # Execute the query and display whether it was successful or not

            if ($success) {
                echo "<p class='success'>Your account has been created!</p>";
                $showForm = 0;

            } else {
                echo "<p class='error'> Registration failed.</p>";
            }
        }
    }

    public function login($email, $pwd)
    {
        $sql = "SELECT * FROM users WHERE email = :email";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if(!$user){
          echo "<p class = 'error'>The email and password combination could not be found.</p>";
          echo "<p class = 'error'>You must register first before logging in.</p>";
      }else {
          if (password_verify($pwd, $user['pwd'])) {
              // SET SESSION VARIABLES
              $_SESSION['ID'] = $user['ID'];
              $_SESSION['email'] = $user['email'];
              $_SESSION['fname'] = $user['fname'];
              $_SESSION['status'] = $user['status'];

              // REDIRECT TO CONFIRMATION PAGE
              header("Location: confirm.php?state=2");

          }else{
              echo "<p class = 'error'> Invalid password.</p>";
          }
      }
    }
}