<?php
// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the category ID is set
    if (isset($_POST["id"])) {
        // Get the category ID from the POST data
        $categoryId = $_POST["id"];

        // Perform deletion operation here
        // 1. Connect to your database
        // Example:
        $pdo = new PDO("mysql:host=127.0.0.1;dbname=blogify", "root", "");

        // 2. Prepare and execute the DELETE query
        // Example:
        $stmt = $pdo->prepare("DELETE FROM blog_categories WHERE CategoryID = :id");
        $stmt->bindParam(':id', $categoryId, PDO::PARAM_INT);
        $stmt->execute();

        // Assuming the deletion is successful, you can send a success message
        echo "Category deleted successfully.";
    } else {
        echo "Error: Category ID not provided.";
    }
} else {
    echo "Error: Invalid request method.";
}
?>
