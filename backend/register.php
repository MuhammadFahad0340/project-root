<?php
// signup.php
require 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Hash the password
    $type = 'AUTHOR'; // Default type is AUTHOR

    $sql = "INSERT INTO USERS (Name, Email, Password, Type) VALUES (:name, :email, :password, :type)";
    $stmt = $pdo->prepare($sql);

    try {
        $stmt->execute(['name' => $name, 'email' => $email, 'password' => $password, 'type' => $type]);
        header("location: ./project-root/html/login.html");
    } catch (\PDOException $e) {
        if ($e->getCode() == 23000) { // Integrity constraint violation (duplicate entry)
            echo "A user with this email or name already exists.";
        } else {
            throw $e;
        }
    }
}
?>
