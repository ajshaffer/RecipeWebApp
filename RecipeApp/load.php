<?php

//load.php

require_once "connect.php";

$database = new Database(); // Instantiate the Database class
$pdo = $database->getConnection();

$data = array(); //store events table data 

$query = "SELECT * FROM events ORDER BY id";

$stmt = $pdo->prepare($query);

$stmt->execute();

$result = $stmt->fetchAll();

foreach($result as $row)
{
 $data[] = array( //event data 
  'id'   => $row["id"],
  'title'   => $row["title"],
  'start'   => $row["start_event"],
  'end'   => $row["end_event"]
 );
}

echo json_encode($data); //display event data

?>