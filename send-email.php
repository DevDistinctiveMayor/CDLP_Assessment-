<?php
require 'config.php';
require 'auth.php'; // Middleware to validate JWT
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$data = json_decode(file_get_contents("php://input"));

$mail = new PHPMailer(true);
try {
    $mail->isSMTP();
    $mail->Host = 'smtp.example.com'; // Change to Mailgun, SendGrid, etc.
    $mail->SMTPAuth = true;
    $mail->Username = 'your-email@example.com';
    $mail->Password = 'your-password';
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->setFrom('your-email@example.com', 'Your Name');
    $mail->addAddress($data->recipient_email);
    $mail->Subject = $data->subject;
    $mail->Body = $data->message;
    $mail->isHTML(true);

    if ($mail->send()) {
        $status = 'sent';
    } else {
        $status = 'failed';
    }
} catch (Exception $e) {
    $status = 'failed';
}

$stmt = $pdo->prepare("INSERT INTO emails (user_id, recipient_email, subject, message, status) VALUES (?, ?, ?, ?, ?)");
$stmt->execute([$user_id, $data->recipient_email, $data->subject, $data->message, $status]);

echo json_encode(["message" => "Email processed", "status" => $status]);
?>
