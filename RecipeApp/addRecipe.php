<?php

require_once "connect.php";

$database = new Database(); // Instantiate the Database class
$pdo = $database->getConnection(); // Get the PDO connection object


if (isset($_GET['search'])){
    if (empty($_GET['term'])) {
        echo "<p class='error'> Empty Search Field. Please try again.</p>";
    }else{
        $term = trim($_GET['term']) . "%";

        //select from database
        $sql = "SELECT fname, lname FROM users WHERE fname LIKE :term ORDER BY fname";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':term', $term);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (empty($result)){
            echo"<p class = 'error'>No results found for " . htmlspecialchars($_GET['term']) . ". Please try again.</p>";
        }else{
            echo "<p class = 'success'>We found results for " . htmlspecialchars($_GET['term']) . ". Here they are: </p> ";

        }

        if (!empty($result)){
            foreach ($result as $row){
                echo $row['fname'] . " " . $row['lname'] . "<br>";
            }
        }
    }
}
?>

<p>Please enter the beginning of the employee's first name: </p>
<form name="mysearch" id="mysearch" method="get" action="<?php echo $currentFile;?>">
    <label for="term">Search Employee First Name:</label>
    <input type="search" id="term" name="term" placeholder="Search">
    <input type="submit" id="search" name="search" value="Search">
    <br><br>


        <p>Which day do you want to add this recipe to? </p>
        <label for="day-0">Sunday</label>
        <input type="radio" name="day" id="day-0" value="sun">
        <br>
        <label for="day-1">Monday</label>
        <input type="radio" name="day" id="day-1" value="mon">
        <br>
        <label for="day-2">Tuesday</label>
        <input type="radio" name="day" id="day-2" value="tue">
        <br>
        <label for="day-3">Wednesday</label>
        <input type="radio" name="day" id="day-3" value="wed">
        <br>
        <label for="day-4">Thursday</label>
        <input type="radio" name="day" id="day-4" value="thu">
        <br>
        <label for="day-5">Friday</label>
        <input type="radio" name="day" id="day-5" value="fri">
        <br>
        <label for="day-6">Saturday</label>
        <input type="radio" name="day" id="day-6" value="sat">
        <br>

        <br><br>

        <p>Which meal is this recipe for? </p>
        <label for="brk">Sunday</label>
        <input type="radio" name="meal" id="brk" value="meal0">
        <br>
        <label for="lun">Monday</label>
        <input type="radio" name="meal" id="lun" value="meal1">
        <br>
        <label for="din">Tuesday</label>
        <input type="radio" name="meal" id="din" value="meal2">
        <br>

        <br><br>
    
</form>

<?php




?>