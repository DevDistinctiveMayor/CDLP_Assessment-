<?php
require 'config.php';
require 'vendor/autoload.php';

use Firebase\JWT\JWT;

$data = json_decode(file_get_contents("php://input"));
$email = $data->email;
$password = $data->password;

$stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user && password_verify($password, $user['password'])) {
    $payload = [
        "user_id" => $user['id'],
        "email" => $user['email'],
        "exp" => time() + (60 * 60) // Token expires in 1 hour
    ];
    $jwt = JWT::encode($payload, SECRET_KEY, 'HS256');
    echo json_encode(["token" => $jwt]);
} else {
    echo json_encode(["error" => "Invalid email or password"]);
}
?>
