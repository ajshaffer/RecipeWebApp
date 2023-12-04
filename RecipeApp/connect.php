<?php

class Database {
    private $dsn = 'mysql:host=sql9.freesqldatabase.com;dbname=sql9653105';
    private $user = 'sql9653105';
    private $pass = '2Px1l89U3h';
    private $pdo;

    public function __construct(){
        $this->connect(); // Call the connect method in the constructor
    }

    private function connect(){
        try {
            $this->pdo = new PDO($this->dsn, $this->user, $this->pass);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }

    public function getConnection(){
        return $this->pdo;
    }
}

?>
