<!DOCTYPE html>
<html>
<head>
    <title>Recipe Search</title>
</head>
<body>
    <h1>LetsCook</h1>

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
            <!-- Add more options as needed -->
        </select>

        <input type="submit" value="Search">
    </form>

    <?php
    // Include the connect.php file to access the Database class
    include 'connect.php';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $search = $_POST["search"];
        $ingredients = $_POST["ingredients"];
        $dietary = $_POST["dietary"];

        // Create a new Database object to establish a database connection
        $db = new Database();
        $pdo = $db->getConnection();

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
                echo "<h3>" . $row["title"] . "</h3>";
                echo "<p><b>Ingredients:</b> " . $row["ingredients"] . "</p>";
                echo "<p><b>Instructions:</b> " . $row["directions"] . "</p>";
                echo "<hr>";
            }
        } else {
            echo "No recipes found for your search term and filters.";
        }
    }
    ?>
</body>
</html>

