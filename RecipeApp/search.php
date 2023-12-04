<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        #search-container {
            max-width: 600px;
            margin: 0 auto;
        }

        form {
            background-color: #f4f4f4;
            padding: 20px;
            border-radius: 8px;
        }

        label {
            display: block;
            margin-bottom: 8px;
        }

        input[type="text"],
        select {
            width: 100%;
            padding: 8px;
            margin-bottom: 12px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            background-color: #4caf50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        h2 {
            margin-top: 20px;
        }

        .recipe-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            background-color: #f9f9f9;
        }

        h3 {
            color: #333;
            margin-bottom: 10px;
        }

        p {
            margin: 0 0 10px 0;
        }

        hr {
            border: 1px solid #ddd;
            margin-bottom: 20px;
        }

        .no-results {
            color: #555;
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <?php
    session_start();
    $pageName = "Search";
    require_once "connect.php";
    require_once "functions.php";
    require_once "header.php";
    $database = new Database();
    $pdo = $database->getConnection();
    ?>

    <div id="search-container">
        <form method="post" action="">
            <label for="search">Search for Recipes:</label>
            <input type="text" name="search" id="search" placeholder="Search for Recipes">

            <label for="ingredients">Filter by Ingredients:</label>
            <input type="text" name="ingredients" id="ingredients" placeholder="Enter Ingredients">

            <label for="health">Filter by Dietary Restrictions:</label>
            <select name="dietary" id="dietary">
                <option value="">All</option>
                <option value="Vegetarian">Vegetarian</option>
                <option value="Vegan">Vegan</option>
                <option value="Low Calorie">Low Calorie</option>
                <option value="Gluten-Free">Gluten-Free</option>
                <option value="Diabetic">Diabetic</option>
                <option value="Lactose-Intolerant">Lactose-Intolerant</option>
            </select>

            <input type="submit" value="Search">
        </form>

        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $search = $_POST["search"];
            $ingredients = $_POST["ingredients"];
            $dietary = $_POST["dietary"];

            // Build the SQL query based on the provided filters
            $sql = "SELECT * FROM recipes WHERE (title LIKE '%$search%' OR ingredients LIKE '%$search%')";

            if (!empty($ingredients)) {
                $sql .= " AND ingredients LIKE '%$ingredients%'";
            }

            if (!empty($dietary)) {
                $sql .= " AND dietary = '$dietary'";
            }

            $stmt = $pdo->prepare($sql);
            $stmt->execute();

            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($result) {
                echo "<h2>Search Results:</h2>";
                foreach ($result as $row) {
                    echo '<div class="recipe-card">';
                    echo "<h3>" . $row["title"] . "</h3>";
                    echo "<p><b>Ingredients:</b> " . $row["ingredients"] . "</p>";
                    echo "<p><b>Instructions:</b> " . $row["directions"] . "</p>";
                    echo "</div>";
                }
            } else {
                echo '<p class="no-results">No recipes found for your search term and filters.</p>';
            }
        }
        ?>
    </div>
</body>

</html>

<?php
include('footer.php');
?>
