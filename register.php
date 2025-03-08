<?php
require 'db_config/connection.php';
require 'vendor/autoload.php';

use Firebase\JWT\JWT;

$data = json_decode(file_get_contents("php://input"));
$name = $data->name;
$email = $data->email;
$password = password_hash($data->password, PASSWORD_BCRYPT);

// Insert user
$stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
if ($stmt->execute([$name, $email, $password])) {
    echo json_encode(["message" => "User registered successfully"]);
} else {
    echo json_encode(["error" => "Registration failed"]);
}


