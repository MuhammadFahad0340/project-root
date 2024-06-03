<?php
require_once 'db_connect.php'; // Include the database connection file

try {
    // Fetch users with their profile pictures
    $stmt = $pdo->query("SELECT users.UserID, users.Name, users.Email, users.Password, users.Type, images.image_data 
                         FROM users 
                         LEFT JOIN images ON users.ProfilePic = images.id");

    // Fetch the results as associative arrays
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Encode image data as base64
    foreach ($users as &$user) {
        if (!empty($user['image_data'])) {
            $user['image_data'] = base64_encode($user['image_data']);
        }
    }

    // Output the results as JSON
    echo json_encode($users);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
