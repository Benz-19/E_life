<?php

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Database;

include_once __DIR__ . "/database.php";
include_once __DIR__ . "/userState.model.php";

class Chat extends Database implements MessageComponentInterface
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

        if (!$data || !isset($data['type'])) {
            return;
        }

        switch ($data['type']) {
            case 'connect':
                // Store the connection under the user's ID
                $userId = $data['user_id'];
                $this->userConnections[$userId] = $from;
                break;

            case 'typing':
                // Notify the recipient that the sender is typing
                $recipientId = $data['recipient_id'];
                if (isset($this->userConnections[$recipientId])) {
                    $this->userConnections[$recipientId]->send(json_encode([
                        'type' => 'typing',
                        'sender_id' => $data['sender_id']
                    ]));
                }
                break;

            case 'stop_typing':
                // Notify the recipient that the sender stopped typing
                $recipientId = $data['recipient_id'];
                if (isset($this->userConnections[$recipientId])) {
                    $this->userConnections[$recipientId]->send(json_encode([
                        'type' => 'stop_typing',
                        'sender_id' => $data['sender_id']
                    ]));
                }
                break;

            case 'message':
                // Save the message in the database and send it to the recipient
                $recipientId = $data['recipient_id'];
                $senderId = $data['sender_id'];
                $message = $data['message'];

                try {
                    // Save the message in the database
                    $conversationId = $this->getConversationId($senderId, $recipientId);
                    if ($conversationId) {
                        $this->updateConversation($conversationId, $senderId, $message);
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

    /**
     * Get the conversation ID between two users.
     */
    private function getConversationId($senderId, $receiverId)
    {
        try {
            $sql = "SELECT conversation_id FROM conversation 
                    WHERE (sender_id = :sender_id AND receiver_id = :receiver_id) 
                       OR (sender_id = :receiver_id AND receiver_id = :sender_id)";
            $stmt = $this->Connection()->prepare($sql);
            $stmt->execute([
                ":sender_id" => $senderId,
                ":receiver_id" => $receiverId
            ]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            return $result ? $result['conversation_id'] : null;
        } catch (PDOException $error) {
            echo "Error fetching conversation ID: " . $error->getMessage();
            return null;
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


    public function startConversation($conversation_id, $sender_id, $receiver_id, $message)
    {
        try {
            $sql = "INSERT INTO conversation (conversation_id, sender_id, receiver_id, message) VALUES (:conversation_id, :sender_id, :receiver_id, :message))";
            $stmt = $this->Connection()->prepare($sql);
            $stmt->execute([
                ":conversation_id" => $conversation_id,
                ":sender_id" => $sender_id,
                ":receiver_id" => $receiver_id,
                ":message" => $message
            ]);
            return true;
        } catch (PDOException $error) {
            echo handle_error("Unable to start a conversation...") . "<br>" . $error->getMessage();
        }
    }

    public function updateConversation($sender_id, $receiver_id, $message)
    {
        try {
            $sql = "UPDATE conversation SET message = :message WHERE sender_id = :sender_id AND receiver_id = :receiver_id";
            $stmt = $this->Connection()->prepare($sql);
            $stmt->execute([
                ":message" => $message,
                ":sender_id" => $sender_id,
                ":receiver_id" => $receiver_id
            ]);
            return true;
        } catch (PDOException $error) {
            echo handle_error("Unable to update the conversation...") . "<br>" . $error->getMessage();
        }
    }
}
