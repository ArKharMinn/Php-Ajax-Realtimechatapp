<?php
require 'db.php';

$last_id = isset($_GET['last_id']) ? (int)$_GET['last_id'] : 0;

$stmt = $pdo->prepare("SELECT * FROM chat_messages WHERE id > :last_id ORDER BY created_at ASC");
$stmt->execute([':last_id' => $last_id]);
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode($messages);
