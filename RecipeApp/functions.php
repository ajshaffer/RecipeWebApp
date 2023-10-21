<?php


function check_duplicates($pdo, $sql, $field)
{
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':field', $field);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return ($row !== false); // Return true if a row was found (duplicate), false otherwise
}


function checkLogin()
{
    if (!isset($_SESSION['ID'])) {
        echo "<p class='error'>This page requires authentication.  Please log in to view details.</p>";
        require_once "footer.php";
        exit();
    }
}

function recipeDay($pdo, $day)
{
    $sql = "SELECT recipeName FROM mealplanner WHERE day LIKE :day";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':day', $day);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (empty($result)){
        echo"<p class = 'error'>No recipes saved to this day yet</p>";
    }else{
        foreach ($result as $row){
            echo $row['recipeName'] . "<br>";

    }
}
}