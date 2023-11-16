<?php


function check_duplicates($pdo, $sql, $field)
{
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':field', $field);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return ($row !== false); 
}


function checkLogin()
{
    if (!isset($_SESSION['ID'])) {
        echo "<p class='error'>This page requires authentication.  Please log in to view details.</p>";
        exit();
    }
}


function recipeDay($pdo, $day, $userID)
{
    $sql = "SELECT recipeName FROM mealplanner WHERE day LIKE :day AND userID = :userID ORDER BY meal";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':day', $day);
    $stmt->bindValue(':userID', $userID);
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

