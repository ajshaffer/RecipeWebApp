<?php

session_start();

require_once "connect.php";
require_once "../classes/userManager.class.php";
require_once "header.php";

$database = new Database(); // Instantiate the Database class
$pdo = $database->getConnection(); // Get the PDO connection object
$userManager = new UserManager($pdo);

$user_id = $_SESSION['ID'];

$currentFile = basename($_SERVER['SCRIPT_FILENAME']);

$showForm = 1;

$errExists = 0;







if ($_SERVER['REQUEST_METHOD'] == "POST"){

    if(isset($_POST['day'])){$days = $_POST['day'];}
    if(isset($_POST['meal'])){$meals = $_POST['meal'];}

    if(empty($days)){
        echo "Error- please choose days to delete from";
    }
    
    if(empty($meals)){
        echo "Error- please choose meals to delete from";
    }
    else{



    foreach($days as $day){
        $sql = "SELECT * FROM mealplanner WHERE day = :day AND userID = :userID";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':day', $day);
        $stmt->bindValue(':userID', $user_id);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if(empty($results)){
            echo "No recipes have been added to these days!";
        }else{

        foreach($meals as $meal){
            $recipeName = $result['recipeName'];
            $sql = "DELETE FROM mealplanner WHERE meal = :meal AND day = :day AND userID = :userID";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':meal', $meal);
            $stmt->bindValue(':day', $day);
            $stmt->bindValue(':userID', $user_id);
            $stmt->execute();

            
        }
        
    }

    }
    header("Location: mealplanner.php");
}
    
}

if($showForm == 1){
?>


<form name="shoplist" id="shoplist" method="post" action="<?php echo $currentFile;?>">
    


        <p>Select days to delete recipes from </p>
        <label for="day-0">Sunday</label>
        <input type="checkbox" name="day[]" id="day-0" value="sun">
        <br>
        <label for="day-1">Monday</label>
        <input type="checkbox" name="day[]" id="day-1" value="mon">
        <br>
        <label for="day-2">Tuesday</label>
        <input type="checkbox" name="day[]" id="day-2" value="tue">
        <br>
        <label for="day-3">Wednesday</label>
        <input type="checkbox" name="day[]" id="day-3" value="wed">
        <br>
        <label for="day-4">Thursday</label>
        <input type="checkbox" name="day[]" id="day-4" value="thu">
        <br>
        <label for="day-5">Friday</label>
        <input type="checkbox" name="day[]" id="day-5" value="fri">
        <br>
        <label for="day-6">Saturday</label>
        <input type="checkbox" name="day[]" id="day-6" value="sat">
        <br>

        <br><br>

        <p>Select meals to delete </p>
        <label for="meal-0">Breakfast</label>
        <input type="checkbox" name="meal[]" id="meal-0" value="meal0">
        <br>
        <label for="meal-1">Lunch</label>
        <input type="checkbox" name="meal[]" id="meal-1" value="meal1">
        <br>
        <label for="meal-2">Dinner</label>
        <input type="checkbox" name="meal[]" id="meal-2" value="meal2">
        <br>

        
        

        

        <input type="submit" id="submit" name="submit" value="Create Shopping List">
    
</form>

<?php

}


?>