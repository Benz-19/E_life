<?php

include_once __DIR__ . "/database.php";

class ChatModel extends Database
{
    public function getConversationId($senderId, $receiverId)
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

    public function setConversation($conversation_id, $sender_id, $receiver_id, $message)
    {
        try {
            $sql = "INSERT INTO conversation (conversation_id, sender_id, receiver_id, message) 
                    VALUES (:conversation_id, :sender_id, :receiver_id, :message)";
            $stmt = $this->Connection()->prepare($sql);
            $stmt->execute([
                ":conversation_id" => $conversation_id,
                ":sender_id" => $sender_id,
                ":receiver_id" => $receiver_id,
                ":message" => $message
            ]);
            return true;
        } catch (PDOException $error) {
            echo "Unable to start a conversation: " . $error->getMessage();
            return false;
        }
    }

    public function startConversation($conversation_id, $sender_id, $receiver_id, $message)
    {
        try {
            $sql = "INSERT INTO conversation (conversation_id, sender_id, receiver_id, message) 
                    VALUES (:conversation_id, :sender_id, :receiver_id, :message)";
            $stmt = $this->Connection()->prepare($sql);
            $stmt->execute([
                ":conversation_id" => $conversation_id,
                ":sender_id" => $sender_id,
                ":receiver_id" => $receiver_id,
                ":message" => $message
            ]);
            return true;
        } catch (PDOException $error) {
            echo "Unable to start a conversation: " . $error->getMessage();
            return false;
        }
    }

    public function updateConversation($conversation_id, $sender_id, $message)
    {
        try {
            $sql = "UPDATE conversation SET message = :message WHERE conversation_id = :conversation_id";
            $stmt = $this->Connection()->prepare($sql);
            $stmt->execute([
                ":message" => $message,
                ":conversation_id" => $conversation_id
            ]);
            return true;
        } catch (PDOException $error) {
            echo "Unable to update the conversation: " . $error->getMessage();
            return false;
        }
    }

    public function getMessages($doctor_id, $patient_id)
    {
        try {
            $query = "SELECT * FROM messages 
                      WHERE (sender_id = :doctor_id AND recipient_id = :patient_id)
                         OR (sender_id = :patient_id AND recipient_id = :doctor_id)
                      ORDER BY created_at ASC";
            $stmt = $this->Connection()->prepare($query);
            $stmt->execute([
                ':doctor_id' => $doctor_id,
                ':patient_id' => $patient_id
            ]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $error) {
            echo "Error fetching messages: " . $error->getMessage();
            return [];
        }
    }

    public function deleteSavedConversation($conversation_id)
    {
        try {
            $sql = "DELETE FROM conversation WHERE conversation_id = :conversation_id";
            $stmt = $this->Connection()->query($sql);
            $stmt->execute([':conversation_id' => $conversation_id]);
            return true;
        } catch (PDOException $error) {
            echo "Error fetching messages: " . $error->getMessage();
            return false;
        }
    }
}
