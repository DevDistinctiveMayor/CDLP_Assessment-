<?php
require 'config.php';
require 'auth.php';

$stmt = $pdo->prepare("SELECT * FROM emails WHERE user_id = ?");
$stmt->execute([$user_id]);
$emails = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($emails);
?>
