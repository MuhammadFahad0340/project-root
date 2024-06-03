<?php
// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the category ID and title are set
    if (isset($_POST["id"]) && isset($_POST["title"])) {
        // Get the category ID and new title from the POST data
        $categoryId = $_POST["id"];
        $newTitle = $_POST["title"];

        // Perform the update operation here
        // 1. Connect to your database
        // Example:
        $pdo = new PDO("mysql:host=127.0.0.1;dbname=blogify", "root", "");

        // 2. Prepare and execute the UPDATE query
        // Example:
        $stmt = $pdo->prepare("UPDATE blog_categories SET Title = :title WHERE CategoryID = :id");
        $stmt->bindParam(':title', $newTitle, PDO::PARAM_STR);
        $stmt->bindParam(':id', $categoryId, PDO::PARAM_INT);
        $stmt->execute();

        // Assuming the update is successful, you can send a success message
        echo "Category updated successfully.";
    } else {
        echo "Error: Category ID or title not provided.";
    }
} else {
    echo "Error: Invalid request method.";
}
?>
