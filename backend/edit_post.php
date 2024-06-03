<?php
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Handle form data with proper sanitization
    $postId = $_POST["postId"];
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
                        
                        // Update the post data in the blog_posts table
                        $stmt = $pdo->prepare("UPDATE blog_posts SET Title = ?, Caption = ?, AuthorName = ?, Description = ?, Category = ?, FeaturedPost = ? WHERE PostID = ?");
                        $stmt->execute([$title, $caption, $author, $description, $category, $featured, $postId]);

                        // Success message
                        echo json_encode(["success" => true]);
                    } catch (PDOException $e) {
                        echo json_encode(["success" => false, "message" => $e->getMessage()]);
                    }
                } else {
                    echo json_encode(["success" => false, "message" => "Sorry, there was an error uploading your file."]);
                }
            } else {
                echo json_encode(["success" => false, "message" => "Sorry, only JPG, JPEG, PNG & GIF files are allowed."]);
            }
        } else {
            echo json_encode(["success" => false, "message" => "File is not an image."]);
        }
    } else {
        // If no new image is uploaded, update only the post data (without changing the image)
        try {
            $pdo = new PDO("mysql:host=127.0.0.1;dbname=blogify", "root", "");
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // Update the post data in the blog_posts table
            $stmt = $pdo->prepare("UPDATE blog_posts SET Title = ?, Caption = ?, AuthorName = ?, Description = ?, Category = ?, FeaturedPOST = ? WHERE id = ?");
            $stmt->execute([$title, $caption, $author, $description, $category, $featured, $postId]);

            // Success message
            echo json_encode(["success" => true]);
        } catch (PDOException $e) {
            echo json_encode(["success" => false, "message" => $e->getMessage()]);
        }
    }
} else {
    echo json_encode(["success" => false, "message" => "Form submission method not allowed."]);
}
?>
