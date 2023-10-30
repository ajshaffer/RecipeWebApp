<?php

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
            return "success"; 

            header("Location: index.php");
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

        if(!$user){
            $this->err_login = "The email and password combination could not be found. You must register first before logging in.";
        }else {
            if (password_verify($pwd, $user['pwd'])) {
                // SET SESSION VARIABLES
                $_SESSION['ID'] = $user['user_id'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['fname'] = $user['fname'];
                $_SESSION['status'] = $user['status'];

                // REDIRECT TO CONFIRMATION PAGE
                header("Location: account.php");

            }else{
                $this->err_login = "Invalid password.";
            }
        }
    }
}