<?php
// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if all required fields are set
    if (isset($_POST["name"]) && isset($_POST["email"]) && isset($_POST["password"]) && isset($_POST["confirm-password"]) && isset($_POST["Type"]) && isset($_FILES["image"])) {
        // Validate password matching
        $password = $_POST["password"];
        $confirmPassword = $_POST["confirm-password"];
        if ($password !== $confirmPassword) {
            echo "Error: Passwords do not match.";
            exit;
        }

        // Connect to the database
        $pdo = new PDO("mysql:host=127.0.0.1;dbname=blogify", "root", "");
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
        // Check if email already exists
        $stmt = $pdo->prepare("SELECT UserID FROM users WHERE Email = ?");
        $stmt->execute([$_POST["email"]]);
        if ($stmt->rowCount() > 0) {
            echo "Error: Email already exists.";
            exit;
        }

        // Upload image
        $targetDirectory = "uploads/"; // Directory where images will be uploaded
        $targetFile = $targetDirectory . basename($_FILES["image"]["name"]);
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        // Check file size
        if ($_FILES["image"]["size"] > 5000000) {
            echo "Error: Image file size is too large.";
            exit;
        }

        // Allow only certain file formats
        $allowedFormats = array("jpg", "jpeg", "png", "gif");
        if (!in_array($imageFileType, $allowedFormats)) {
            echo "Error: Only JPG, JPEG, PNG, and GIF files are allowed.";
            exit;
        }

        // Move uploaded file to target directory
        if (!move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
            echo "Error: Failed to upload image.";
            exit;
        }

        // Insert user data into users table
        $stmt = $pdo->prepare("INSERT INTO users (Name, Email, Password, Type, ProfilePic) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$_POST["name"], $_POST["email"],password_hash($_POST['password'], PASSWORD_BCRYPT), $_POST["Type"], $targetFile]);

        // Assuming the insertion is successful, redirect to manage_user.html
        header("Location: ./project-root/html/admin/manage_user.html");
        exit;
    } else {
        echo "Error: All required fields not provided.";
    }
} else {
    echo "Error: Invalid request method.";
}
?>
