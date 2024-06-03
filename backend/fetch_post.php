<?php 
   // Allow from any origin
   if (isset($_SERVER['HTTP_ORIGIN'])) {
       header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
       header('Access-Control-Allow-Credentials: true');
       header('Access-Control-Max-Age: 86400');    // cache for 1 day
   }
   
   // Access-Control headers are received during OPTIONS requests
   if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
       if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
           header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
       if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
           header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
       exit(0);
   }
   
   header('Content-Type: application/json');
   
   try {
       // Connect to the database
       $pdo = new PDO("mysql:host=127.0.0.1;dbname=blogify", "root", "");
       $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
   
       // Check if the postID parameter is provided
       if (isset($_GET['PostID'])) {
           // Sanitize the input to prevent SQL injection
           $postID = $_GET['PostID'];
   
           // Prepare and execute the SQL query to fetch the post data
           $stmt = $pdo->prepare("SELECT bp.*, bc.ImageID AS CategoryImageID
           FROM blog_posts bp
           JOIN blog_categories bc ON bp.Category = bc.Title
           WHERE bp.PostID = ?
           ");
           $stmt->execute([$postID]);
           $post = $stmt->fetch(PDO::FETCH_ASSOC);
   
           // Check if the post exists
           if ($post) {
               // Return the post data as JSON
               echo json_encode($post);
           } else {
               // Return an error message if the post does not exist
               echo json_encode(["error" => "Post not found"]);
           }
       } else {
           // Return an error message if the postID parameter is not provided
           echo json_encode(["error" => "Missing postID parameter"]);
       }
   } catch (PDOException $e) {
       // Return an error message if an exception occurs
       echo json_encode(["error" => $e->getMessage()]);
   }
?>
