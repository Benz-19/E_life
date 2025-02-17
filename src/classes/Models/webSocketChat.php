<?php

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

include_once __DIR__ . "/../../../vendor/autoload.php";
include "chat.model.php";

class Chat extends ChatModel implements MessageComponentInterface
{
    protected $clients = [];
    protected $userConnections = []; // Stores user ID -> connection mapping

    public function onOpen(ConnectionInterface $conn)
    {
        $this->clients[$conn->resourceId] = $conn;
        echo "New connection! ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        $data = json_decode($msg, true);
        print_r($data);
        if (!$data || !isset($data['type'])) {
            return;
        }

        if ($data['type'] === 'message') {
            $senderId = $data['sender_id'];
            $recipientId = $data['recipient_id'];

            foreach ($this->clients as $client) {
                // Broadcast only to the sender and recipient
                if ($client->userId === $recipientId || $client->userId === $senderId) {
                    $client->send(json_encode($data));
                }
            }
        }


        switch ($data['type']) {
            case 'connect':
                // Store the connection under the user's ID
                $userId = $data['user_id'];
                $this->userConnections[$userId] = $from;
                break;

            case 'typing':
                // Notify the recipient that the sender is typing
                $recipientId = $data['recipient_id'] ?? null;

                if (isset($this->userConnections[$recipientId])) {
                    $this->userConnections[$recipientId]->send(json_encode([
                        'type' => 'typing',
                        'sender_id' => $data['sender_id']
                    ]));
                }
                break;

            case 'stop_typing':
                // Notify the recipient that the sender stopped typing
                $recipientId = $data['recipient_id'] ?? null;

                if (isset($this->userConnections[$recipientId])) {
                    $this->userConnections[$recipientId]->send(json_encode([
                        'type' => 'stop_typing',
                        'sender_id' => $data['sender_id']
                    ]));
                }
                break;
            case 'message':
                if (empty($data['sender_id']) || empty($data['recipient_id']) || empty($data['message'])) {
                    echo "Invalid message data received.\n";
                    return;
                }
                $recipientId = $data['recipient_id'] ?? null;

                $senderId = $data['sender_id'] ?? null;
                $message = $data['message'];

                try {
                    // Save the message in the database
                    $conversationId = $this->getConversationId($senderId, $recipientId);

                    if ($conversationId) {
                        // $this->updateConversation($conversationId, $senderId, $message);
                        $this->setConversation($conversationId, $senderId, $recipientId, $message);
                    } else {
                        $conversationId = uniqid(); // Generate a unique conversation ID
                        $this->startConversation($conversationId, $senderId, $recipientId, $message);
                    }

                    // Forward the message to the recipient
                    if (isset($this->userConnections[$recipientId])) {
                        $this->userConnections[$recipientId]->send(json_encode([
                            'type' => 'message',
                            'message' => $message,
                            'sender_id' => $senderId
                        ]));
                    }
                } catch (Exception $e) {
                    echo "Error saving or forwarding message: " . $e->getMessage();
                }
                break;
        }
    }



    public function onClose(ConnectionInterface $conn)
    {
        foreach ($this->userConnections as $userId => $connection) {
            if ($connection === $conn) {
                unset($this->userConnections[$userId]);
                break;
            }
        }
        unset($this->clients[$conn->resourceId]);
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        $conn->close();
    }
}
