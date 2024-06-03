<?php
require 'db_connect.php';

if (isset($_GET['id'])) {
    $imageId = $_GET['id'];

    // Adjust the SQL query to exclude the image_type
    $sql = "SELECT image_data FROM images WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $imageId]);
    $image = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($image) {
        // Serve the image with a default MIME type
        header("Content-Type: image/jpeg"); // Adjust this if your images are a different type (e.g., image/png)
        echo $image['image_data'];
    } else {
        http_response_code(404);
        echo "Image not found.";
    }
} else {
    http_response_code(400);
    echo "No image ID specified.";
}
?>
