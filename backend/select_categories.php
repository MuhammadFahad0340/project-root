<?php
// Fetch categories from the database and return as JSON
try {
    $pdo = new PDO("mysql:host=127.0.0.1;dbname=blogify", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $stmt = $pdo->query("SELECT Title FROM blog_categories");
    $categories = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
    
    echo json_encode($categories);
} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
?>
