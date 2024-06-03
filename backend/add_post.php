<?php
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Handle form data with proper sanitization
    $title = htmlspecialchars($_POST["title"]);
    $caption = htmlspecialchars($_POST["caption"]);
    $author = htmlspecialchars($_POST["author"]);
    $description = htmlspecialchars($_POST["description"]);
    $category = htmlspecialchars($_POST["category"]);
    $featured = isset($_POST["featured"]) ? htmlspecialchars($_POST["featured"]) : "No";

    // Image upload handling
    if (isset($_FILES["image"]) && $_FILES["image"]["error"] == 0) {
        $targetDir = "uploads/";
        $imageName = basename($_FILES["image"]["name"]);
        $targetFile = $targetDir . $imageName;
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        // Check if image file is a actual image or fake image
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if($check !== false) {
            // Allow certain file formats
            $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
            if(in_array($imageFileType, $allowedTypes)) {
                // Move the uploaded file to the target directory
                if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
                    // Insert image data into the images table
                    try {
                        $pdo = new PDO("mysql:host=127.0.0.1;dbname=blogify", "root", "");
                        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                        
                        $stmt = $pdo->prepare("INSERT INTO images (image_name, image_data) VALUES (?, ?)");
                        $imageData = file_get_contents($targetFile);
                        $stmt->bindParam(1, $imageName);
                        $stmt->bindParam(2, $imageData, PDO::PARAM_LOB);
                        $stmt->execute();

                        // Get the ID of the inserted image
                        $imageId = $pdo->lastInsertId();

                        // Insert the rest of the form data into the blog_posts table
                        $stmt = $pdo->prepare("INSERT INTO blog_posts (Title, Caption, AuthorName, Description, Category, ImageID, FeaturedPOST) VALUES (?, ?, ?, ?, ?, ?, ?)");
                        $stmt->execute([$title, $caption, $author, $description, $category, $imageId, $featured]);
                        
                        
                        // Success message
                        echo "Post submitted successfully.<br>";
                        echo "Title: " . $title . "<br>";
                        echo "Caption: " . $caption . "<br>";
                        echo "Author: " . $author . "<br>";
                        echo "Description: " . $description . "<br>";
                        echo "Category: " . $category . "<br>";
                        echo "Featured: " . $featured . "<br>";
                        echo "Image ID: " . $imageId . "<br>";
                    } catch (PDOException $e) {
                        echo "Error: " . $e->getMessage();
                    }
                } else {
                    echo "Sorry, there was an error uploading your file.";
                }
            } else {
                echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            }
        } else {
            echo "File is not an image.";
        }
    } else {
        echo "No file uploaded or file upload error.";
    }
} else {
    // If the form is not submitted, return an error message
    echo "Error: Form not submitted.";
}
?>
