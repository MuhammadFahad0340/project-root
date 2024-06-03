<?php
// stay_in_touch.php

require 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    $sql = "INSERT INTO stay_in_touch (email) VALUES (:email)";
    $stmt = $pdo->prepare($sql);

    try {
        $stmt->execute(['email' => $email]);
        echo json_encode(["message" => "Success"]);
    } catch (\PDOException $e) {
        http_response_code(500);
        echo json_encode(["message" => "Error"]);
    }
}
?>
