<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *"); // Allowing requests from any origin

// Retrieve category title from URL
$title = isset($_GET['title']) ? $_GET['title'] : '';

try {
    $pdo = new PDO("mysql:host=127.0.0.1;dbname=blogify", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Prepare SQL query to fetch posts based on category title
    $stmt = $pdo->prepare("SELECT * FROM blog_posts WHERE Category = :title");
    $stmt->bindParam(':title', $title);
    $stmt->execute();
    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($posts);
} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
?>
