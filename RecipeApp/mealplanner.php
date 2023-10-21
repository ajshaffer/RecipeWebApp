<?php
$currentFile = basename($_SERVER['SCRIPT_FILENAME']);
require_once "connect.php";

$database = new Database(); // Instantiate the Database class
$pdo = $database->getConnection(); // Get the PDO connection object

$currentFile = basename($_SERVER['SCRIPT_FILENAME']);



if (isset($_GET['search'])){
    if (empty($_GET['term'])) {
        echo "<p class='error'> Empty Search Field. Please try again.</p>";
    }else{
        $term = trim($_GET['term']) . "%";

        //select from database
        $sql = "SELECT title FROM recipes WHERE title LIKE :term";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':term', $term);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (empty($result)){
            echo"<p class = 'error'>No results found for " . htmlspecialchars($_GET['term']) . ". Please try again.</p>";
        }else{
            echo "<p class = 'success'>We found results for " . htmlspecialchars($_GET['term']) . ". Here they are: </p> ";

        }

        

        
    }
}
?>

<!DOCTYPE html>
<html>
<style>
table, th, td {
  border:1px solid black;
}
</style>
<body>

<h2>Meal Planner</h2>

<table style="width:100%">
  <tr>
    <th>Sunday</th>
    <th>Monday</th>
    <th>Tuesday</th>
    <th>Wednesday</th>
    <th>Thursday</th>
    <th>Friday</th>
    <th>Saturday</th>

  </tr>
  <tr>
    <td>Example</td>
    <td>Example</td>
    <td>Example</td>
    <td>Example</td>
    <td>Example</td>
    <td>Example</td>
    <td>Example</td>
  </tr>
 
</table>

<p>Please enter the beginning of the employee's first name: </p>
<form name="mysearch" id="mysearch" method="get" action="<?php echo $currentFile;?>">
    <label for="term">Search Employee First Name:</label>
    <input type="search" id="term" name="term" placeholder="Search">
    <input type="submit" id="search" name="search" value="Search">
    <br><br>

</form>

<?php
if (!empty($result)){
            
  foreach ($result as $row){
    echo "<a href='addRecipe.php?t=" . $row['title'] . "'>" . $row['title'] . "</a><br>";
}

  
  
  
}

?>

</body>
</html>
