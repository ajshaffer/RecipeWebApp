<?php



require_once "connect.php";

$database = new Database(); // Instantiate the Database class
$pdo = $database->getConnection(); // Get the PDO connection object

$currentFile = basename($_SERVER['SCRIPT_FILENAME']);

$showForm = 1;

$errExists = 0;







if ($_SERVER['REQUEST_METHOD'] == "POST"){
    
}

if($showForm == 1){
?>


<form name="shoplist" id="shoplist" method="post" action="<?php echo $currentFile;?>">
    


        <p>Select days to create a shopping list for </p>
        <label for="day-0">Sunday</label>
        <input type="checkbox" name="day" id="day-0" value="sun">
        <br>
        <label for="day-1">Monday</label>
        <input type="checkbox" name="day" id="day-1" value="mon">
        <br>
        <label for="day-2">Tuesday</label>
        <input type="checkbox" name="day" id="day-2" value="tue">
        <br>
        <label for="day-3">Wednesday</label>
        <input type="checkbox" name="day" id="day-3" value="wed">
        <br>
        <label for="day-4">Thursday</label>
        <input type="checkbox" name="day" id="day-4" value="thu">
        <br>
        <label for="day-5">Friday</label>
        <input type="checkbox" name="day" id="day-5" value="fri">
        <br>
        <label for="day-6">Saturday</label>
        <input type="checkbox" name="day" id="day-6" value="sat">
        <br>

        <br><br>

        
        

        

        <input type="submit" id="submit" name="submit" value="Add Recipe">
    
</form>

<?php

}


?>