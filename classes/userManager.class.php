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
                header("Location: profile.php");

            }else{
                $this->err_login = "Invalid password.";
            }
        }
    }

    public function getProfileInfo($ID){
        $sql = "SELECT * FROM profiles WHERE user_id = :userID";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':user_ID', $ID);

        if(!$stmt->execute(array($ID))){
            $stmt = null;
            header("location: profile.php?error=stmtfailed");
            exit();
        }

        if($stmt->rowCount() == 0){
            $stmt = null;
            header("location: profile.php?error=profileNotFound");
            exit();
        }

        $profileData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $profileData;

    }

    public function updateProfileInfo($profileAbout, $ID){
        $sql = "UPDATE profiles SET profile_about = :profile_about WHERE user_id = :ID";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':profile_about', $profileAbout);
        $stmt->bindValue(':user_id', $ID);

        if(!$stmt->execute(array($profileAbout, $ID))){
            $stmt = null;
            header("location: profile.php?error=stmtfailed");
            exit();
        }

        $stmt = null;

    }
    
    public function setProfileInfo($profileAbout, $ID){
        $sql = "INSERT INTO profiles (profile_about, user_id) VALUES (:profileAbout, :user_id)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':profile_about', $profileAbout);
        $stmt->bindValue(':user_id', $ID);

        if(!$stmt->execute(array($profileAbout, $ID))){
            $stmt = null;
            header("location: profile.php?error=stmtfailed");
            exit();
        }

        $stmt = null;

    }





}