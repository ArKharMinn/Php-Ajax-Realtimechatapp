<?php
require 'db.php';

$username = trim($_POST['username'] ?? 'Anonymous');
$message = trim($_POST['message'] ?? '');

if (!empty($message)) {
    $stmt = $pdo->prepare("INSERT INTO chat_messages (username, message) VALUES (:username, :message)");
    $stmt->execute([
        ':username' => $username,
        ':message' => $message
    ]);
}

echo json_encode(['status' => 'success']);
