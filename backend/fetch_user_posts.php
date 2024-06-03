<?php
header('Content-Type: application/json');

try {
    // Establish database connection
    $pdo = new PDO("mysql:host=127.0.0.1;dbname=blogify", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Retrieve author name from the URL query parameter
    $authorName = isset($_GET['author_name']) ? $_GET['author_name'] : null;

    // Prepare SQL query
    if ($authorName !== null) {
        $stmt = $pdo->prepare("SELECT PostID, Title, Category,AuthorName FROM blog_posts WHERE AuthorName = :author_name");
        $stmt->execute(['author_name' => $authorName]); // Bind the parameter
    } else {
        $stmt = $pdo->query("SELECT PostID, Title, Category FROM blog_posts");
    }

    // Fetch posts
    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return posts as JSON
    echo json_encode($posts);
} catch (PDOException $e) {
    // Handle database error
    echo json_encode(["error" => $e->getMessage()]);
}
?>
