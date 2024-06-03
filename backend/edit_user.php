<?php
require_once 'db_connect.php'; // Include the database connection file

// Check if form data is received
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $userId = $_POST['user-id'];
    $name = $_POST['name'];
    $password = $_POST['password'];
    $category = $_POST['category'];
    $image = $_FILES['image'];

    // Check if user ID is valid
    if (!empty($userId)) {
        // Update user details in the database
        $sql = "UPDATE users SET Name = ?, Password = ?, Type = ? WHERE UserID = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$name, $password, $category, $userId]);

        // Handle image upload if provided
        if (!empty($image['name'])) {
            $imageData = file_get_contents($image['tmp_name']);
            $sql = "UPDATE images SET image_name = ?, image_data = ? WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$image['name'], $imageData, $userId]);
        }

        echo "User updated successfully";
    } else {
        echo "Invalid user ID";
    }
} else {
    echo "Invalid request method";
}
?>
