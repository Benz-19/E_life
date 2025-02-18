<?php

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

include_once __DIR__ . "/../../../vendor/autoload.php";
include "chat.model.php";

class Chat extends ChatModel implements MessageComponentInterface
{
    protected $clients = [];
    protected $userConnections = []; // Stores user ID -> connection mapping
    protected $userStatuses = []; // Track user online/busy statuses

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

        switch ($data['type']) {
            case 'connect':
                $userId = $data['user_id'];
                $this->userConnections[$userId] = $from;
                $this->userStatuses[$userId] = 'online';
                $this->broadcastStatusUpdate($userId, 'online');
                break;

            case 'disconnect':
                $userId = $data['user_id'];
                unset($this->userConnections[$userId]);
                $this->userStatuses[$userId] = 'offline';
                $this->broadcastStatusUpdate($userId, 'offline');
                break;

            case 'typing':
            case 'stop_typing':
                $recipientId = $data['recipient_id'] ?? null;
                if (isset($this->userConnections[$recipientId])) {
                    $this->userConnections[$recipientId]->send(json_encode([
                        'type' => $data['type'],
                        'sender_id' => $data['sender_id']
                    ]));
                }
                break;

            case 'message':
                $senderId = $data['sender_id'];
                $recipientId = $data['recipient_id'];
                $message = $data['message'];

                if ($this->isDoctorBusy($recipientId)) {
                    if (isset($this->userConnections[$senderId])) {
                        $this->userConnections[$senderId]->send(json_encode([
                            'type' => 'busy',
                            'message' => 'Doctor is currently busy. Please wait.'
                        ]));
                    }
                    return;
                }

                try {
                    $conversationId = $this->getConversationId($senderId, $recipientId);
                    if (!$conversationId) {
                        $conversationId = uniqid();
                        $this->startConversation($conversationId, $senderId, $recipientId, $message);
                    } else {
                        $this->setConversation($conversationId, $senderId, $recipientId, $message);
                    }

                    if (isset($this->userConnections[$recipientId])) {
                        $this->userConnections[$recipientId]->send(json_encode([
                            'type' => 'message',
                            'message' => $message,
                            'sender_id' => $senderId
                        ]));
                    }

                    if ($this->isDoctor($recipientId)) {
                        $this->userStatuses[$recipientId] = 'busy';
                        $this->broadcastStatusUpdate($recipientId, 'busy');
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
                $this->userStatuses[$userId] = 'offline';
                $this->broadcastStatusUpdate($userId, 'offline');
                break;
            }
        }
        unset($this->clients[$conn->resourceId]);
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        $conn->close();
    }

    protected function broadcastStatusUpdate($userId, $status)
    {
        foreach ($this->clients as $client) {
            $client->send(json_encode([
                'type' => 'status_update',
                'user_id' => $userId,
                'status' => $status
            ]));
        }
    }

    protected function isDoctor($userId)
    {
        // Placeholder: Implement logic to check if user is a doctor
        return true;
    }

    protected function isDoctorBusy($doctorId)
    {
        return isset($this->userStatuses[$doctorId]) && $this->userStatuses[$doctorId] === 'busy';
    }
}
