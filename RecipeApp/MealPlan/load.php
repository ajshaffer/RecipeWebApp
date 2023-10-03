<?php

//load.php

$connect = new PDO('mysql:host=localhost;dbname=testing', 'root', '');

$data = array(); //store events table data 

$query = "SELECT * FROM events ORDER BY id";

$statement = $connect->prepare($query);

$statement->execute();

$result = $statement->fetchAll();

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