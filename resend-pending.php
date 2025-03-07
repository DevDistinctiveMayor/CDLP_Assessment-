<?php
require 'config.php';
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$stmt = $pdo->prepare("SELECT * FROM emails WHERE status IN ('pending', 'failed')");
$stmt->execute();
$emails = $stmt->fetchAll(PDO::FETCH_ASSOC);

$mail = new PHPMailer(true);
$mail->isSMTP();
$mail->Host = 'smtp.example.com';
$mail->SMTPAuth = true;
$mail->Username = 'your-email@example.com';
$mail->Password = 'your-password';
$mail->SMTPSecure = 'tls';
$mail->Port = 587;

foreach ($emails as $email) {
    try {
        $mail->setFrom('your-email@example.com', 'Your Name');
        $mail->addAddress($email['recipient_email']);
        $mail->Subject = $email['subject'];
        $mail->Body = $email['message'];
        $mail->isHTML(true);

        if ($mail->send()) {
            $status = 'sent';
        } else {
            $status = 'failed';
        }
    } catch (Exception $e) {
        $status = 'failed';
    }

    $updateStmt = $pdo->prepare("UPDATE emails SET status = ? WHERE id = ?");
    $updateStmt->execute([$status, $email['id']]);
}

echo json_encode(["message" => "Resend process completed"]);
?>
