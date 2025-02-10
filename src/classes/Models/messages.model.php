<?php

require_once __DIR__ . "/database.php";

class MessageController extends Database
{
    private $db;


    public function __construct($dbConnection)
    {
        $this->db = $dbConnection;
    }

    /**
     * Save a message to the database
     *
     * @param int $senderId
     * @param int $recipientId
     * @param string $message
     * @return bool
     */
    public function saveMessage($senderId, $recipientId, $message)
    {
        $sql = "INSERT INTO messages (sender_id, recipient_id, message, created_at) VALUES (:sender_id, :recipient_id, :message, NOW())";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':sender_id', $senderId, \PDO::PARAM_INT);
        $stmt->bindParam(':recipient_id', $recipientId, \PDO::PARAM_INT);
        $stmt->bindParam(':message', $message, \PDO::PARAM_STR);

        return $stmt->execute();
    }

    /**
     * Get all messages between two users
     *
     * @param int $userId
     * @param int $recipientId
     * @return array
     */
    public function getMessages($userId, $recipientId)
    {
        $sql = "SELECT * FROM messages 
                WHERE (sender_id = :user_id AND recipient_id = :recipient_id) 
                   OR (sender_id = :recipient_id AND recipient_id = :user_id) 
                ORDER BY created_at ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':user_id', $userId, \PDO::PARAM_INT);
        $stmt->bindParam(':recipient_id', $recipientId, \PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
