<?php

require 'db_connect.php';

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM USERS WHERE Email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['Password'])) {
        $_SESSION['user_id'] = $user['UserID'];
        $_SESSION['user_name'] = $user['Name'];
        $_SESSION['user_type'] = $user['Type'];
        echo "Login successful! Welcome " . htmlspecialchars($user['Name']) . ". You are logged in as " . htmlspecialchars($user['Type']) . ".";
        
        // Redirect based on user type
        if ($user['Type'] === "ADMIN") {
            header("Location: /project-root/html/admin/manage_user.html");
            exit();
        } elseif ($user['Type'] === "AUTHOR") {
            header("Location: /project-root/html/author/manage_posts.html?author_name=" . $user['Name']);
            exit();
        } else {
            // Handle other user types if needed
        }
    } else {
        echo "Invalid email or password.";
    }
}
?>
