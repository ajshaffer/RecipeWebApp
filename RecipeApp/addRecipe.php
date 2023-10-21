<?php

require_once "connect.php";

$database = new Database(); // Instantiate the Database class
$pdo = $database->getConnection(); // Get the PDO connection object

$currentFile = basename($_SERVER['SCRIPT_FILENAME']);

echo "Add a recipe to meal planner " . $_GET['t']; 



if ($_SERVER['REQUEST_METHOD'] == "POST"){
    if (isset($_POST['day'])) {$day = $_POST['day'];}
    if (isset($_POST['meal'])) {$meal = $_POST['meal'];}
    $recipeName = $_POST['recName'];

    $sql = "INSERT INTO mealplanner (recipeName, day, meal) VALUES (:recipeName, :day, :meal)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':recipeName', $recipeName);
        $stmt->bindValue(':day', $day);
        $stmt->bindValue(':meal', $meal);
        
        $stmt->execute();
}
?>


<form name="mealplan" id="mealplan" method="post" action="<?php echo $currentFile;?>">
    


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

        <input type="hidden" id="recName" name="recName" value="<?php echo $_GET['t']; ?>">

        <input type="submit" id="submit" name="submit" value="Add Recipe">
    
</form>

<?php




?>