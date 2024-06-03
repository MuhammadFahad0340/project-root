<?php
// Fetch categories from the database and return as JSON

// Allow requests from any origin
header("Access-Control-Allow-Origin: *");
// Allow requests with the Content-Type header
header("Access-Control-Allow-Headers: Content-Type");

// Your existing PHP code here


try {
    $pdo = new PDO("mysql:host=127.0.0.1;dbname=blogify", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $stmt = $pdo->query("SELECT CategoryID,Title,ImageID FROM blog_categories");
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($categories);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
