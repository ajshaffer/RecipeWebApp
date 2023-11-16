<?php

session_start(); 

if (isset($_GET['t'])) {
    $_SESSION['t'] = $_GET['t'];
}

require_once "connect.php";
require_once "header.php";
require_once "../classes/userManager.class.php";

$database = new Database(); // Instantiate the Database class
$pdo = $database->getConnection(); // Get the PDO connection object
$userManager = new UserManager($pdo);

$user_id = $_SESSION['ID'];

$currentFile = basename($_SERVER['SCRIPT_FILENAME']);

$showForm = 1;

$errExists = 0;

$errDay = ""; 

$errMeal = ""; 

echo "Add a recipe to meal planner " . $_SESSION['t']; 



if ($_SERVER['REQUEST_METHOD'] == "POST"){
    if (isset($_POST['day'])) {$day = $_POST['day'];}
    if (isset($_POST['meal'])) {$meal = $_POST['meal'];}
    $recipeName = $_POST['recName'];

    if(empty($day)){
        $errExists = 1;
        $errDay = "Please select a Day to add your recipe to";
    }

    if(empty($meal)){
        $errExists = 1;
        $errMeal = "Please select a meal to add your recipe to";
    }

    if ($errExists == 1) {
        echo "<p class='error'>There are errors.  Please make changes and resubmit.</p>";
     }else{ 

    $sql = "INSERT INTO mealplanner (recipeName, day, meal, userID) VALUES (:recipeName, :day, :meal, :userID)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':recipeName', $recipeName);
        $stmt->bindValue(':day', $day);
        $stmt->bindValue(':meal', $meal);
        $stmt->bindValue(':userID', $user_id);
        
        $stmt->execute();

        header("Location: mealplanner.php");

     }
}

if($showForm == 1){
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
        <label for="brk">Breakfast</label>
        <input type="radio" name="meal" id="brk" value="meal0">
        <br>
        <label for="lun">Lunch</label>
        <input type="radio" name="meal" id="lun" value="meal1">
        <br>
        <label for="din">Dinner</label>
        <input type="radio" name="meal" id="din" value="meal2">
        <br>

        <br><br>
        

        <input type="hidden" id="recName" name="recName" value="<?php echo $_SESSION['t']; ?>">

        <input type="submit" id="submit" name="submit" value="Add Recipe">
    
</form>

<?php

}


?>