<?php

//insert.php
require_once "connect.php";

$database = new Database(); // Instantiate the Database class
$pdo = $database->getConnection();


if(isset($_POST["title"])) //checks if title is set
{
 $query = "
 INSERT INTO events 
 (title, start_event, end_event) 
 VALUES (:title, :start_event, :end_event)
 ";
 $stmt = $pdo->prepare($query);
 $stmt->execute(
  array(
   ':title'  => $_POST['title'],
   ':start_event' => $_POST['start'],
   ':end_event' => $_POST['end']
  )
 );
}


?>
