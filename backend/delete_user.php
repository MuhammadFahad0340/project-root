<?php
require_once 'db_connect.php'; // Include the database connection file

// Get the user ID from the request
$userID = intval($_GET['id']);

if ($userID > 0) {
    try {
        // Delete the user from the database
        $stmt = $pdo->prepare("DELETE FROM users WHERE UserID = :userID");
        $stmt->bindParam(':userID', $userID, PDO::PARAM_INT);
        if ($stmt->execute()) {
            echo "User deleted successfully";
        } else {
            echo "Error deleting user";
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "Invalid user ID";
}
?>
