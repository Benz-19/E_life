<?php

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../classes/Models/messages.model.php';

$dbConnection = new PDO('mysql:host=localhost;dbname=e_life', 'root', '');


$messageController = new MessageController($dbConnection);

// Ensure `user_id` and `recipient_id` are passed in the query string.
if (isset($_GET['user_id'], $_GET['recipient_id'])) {
    $userId = $_GET['user_id'];
    $recipientId = $_GET['recipient_id'];

    $messages = $messageController->getMessages($userId, $recipientId);
    header('Content-Type: application/json');
    echo json_encode($messages);
} else {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid parameters']);
}
