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


        if ($stmt->execute()){

            $defaultPicture = "../images/default_profile.jpg";

            $user_id = $this->db->lastInsertId();
            $this->setProfileInfo("Welcome to my profile! I'm still crafting my unique story. Stay tuned for updates about my interests, experiences, and more.", $user_id, $defaultPicture);
            header("Location: index.php");
        } else {
            $this->err_login = "Unable to register user.";
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
                $_SESSION['lname'] = $user['lname'];
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
        $stmt->bindValue(':userID', $ID);
        $stmt->execute();

        $profileData = $stmt->fetch(PDO::FETCH_ASSOC);
        $profilePicture = $this->getProfilePicture($ID);

        if ($profilePicture) {
            $_SESSION['profile_pic'] = $profilePicture;
        }

        if(empty($profileData)){
            $this->err_login = "No profile found for the user.";
        } else {
            $_SESSION['profileID'] = $profileData['profile_id'];
            $_SESSION['profileAbout'] = $profileData['profile_about'];

            $row = isset($row) ? $row : [];
            $row['profile_about'] = $profileData['profile_about'];
        }
    }

    public function updateProfileInfo($profileAbout, $ID, $profilePicture = null){
        try {
            $this->db->beginTransaction();
    
            // Update profile about
            $sql = "UPDATE profiles SET profile_about = :profile_about, profile_pic = :profile_pic WHERE user_id = :user_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':profile_about', $profileAbout);
            $stmt->bindValue(':profile_pic', $profilePicture);
            $stmt->bindValue(':user_id', $ID);
    
            if (!$stmt->execute()) {
                throw new Exception("Error updating profile information.");
            }
    
            // Update profile picture if provided
            if ($profilePicture) {
                $this->updateProfilePicture($ID, $profilePicture);
                $_SESSION['profile_pic'] = $profilePicture;
            }
    
            $_SESSION['profileAbout'] = $profileAbout;
    
            $this->db->commit();
        } catch (Exception $e) {
            $this->db->rollBack();
            header("Location: profile.php?error=stmtfailed");
            exit();
        }

    }
    
    

    public function setProfileInfo($profileAbout, $ID, $profilePicture = null){
        $sql = "INSERT INTO profiles (profile_about, user_id, profile_pic) VALUES (:profile_about, :user_id, :profile_pic)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':profile_about', $profileAbout);
        $stmt->bindValue(':user_id', $ID);
        $stmt->bindValue(':profile_pic', $profilePicture);


        if ($profilePicture) {
            $this->updateProfilePicture($ID, $profilePicture);
            $_SESSION['profile_pic'] = $profilePicture;
        }
    
        if(!$stmt->execute()){
            $stmt = null;
            throw new Exception("Error setting profile information.");
        }
    
        $stmt = null;
    }

    public function getProfilePicture($ID) {
        try {
            $stmt = $this->db->prepare("SELECT profile_pic FROM profiles WHERE user_id = :user_id");
            $stmt->bindValue(':user_id', $ID);
            $stmt->execute([$ID]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
            return $result ? $result['profile_pic'] : null;
        } catch (PDOException $e) {
            return null;
        }
    }
    
    public function updateProfilePicture($ID, $filename) {
        $stmt = $this->db->prepare("UPDATE profiles SET profile_pic = :profile_pic WHERE user_id = :user_id");
        $stmt->bindValue(':profile_pic', $filename);
        $stmt->bindValue(':user_id', $ID);
        $stmt->execute();
    }
    
}