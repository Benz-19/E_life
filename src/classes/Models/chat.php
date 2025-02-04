<?php

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class Chat implements MessageComponentInterface
{
    protected $clients = [];
    protected $userConnections = []; // Stores user ID -> connection mapping

    public function onOpen(ConnectionInterface $conn)
    {
        $this->clients[$conn->resourceId] = $conn;
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
                $userId = $data['user_id']; // User ID from the frontend
                $this->userConnections[$userId] = $from;
                break;

            case 'message':
                // Ensure the recipient exists before sending
                $recipientId = $data['recipient_id'];
                if (isset($this->userConnections[$recipientId])) {
                    $this->userConnections[$recipientId]->send(json_encode([
                        'type' => 'message',
                        'message' => $data['message'],
                        'sender_id' => $data['sender_id']
                    ]));
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
