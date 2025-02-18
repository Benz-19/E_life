<?php

include_once __DIR__ . "/../../../vendor/autoload.php";

class Notification extends Database
{

    public function addNotification($senderId, $recipientId, $message, $type)
    {
        try {
            $sql = "INSERT INTO notifications (sender_id, recipient_id, message, notification_type) 
                VALUES (:sender_id, :recipient_id, :message, :type)";
            $stmt = $this->Connection()->prepare($sql);
            $stmt->execute([
                ':sender_id' => $senderId,
                ':recipient_id' => $recipientId,
                ':message' => $message,
                ':type' => $type
            ]);

            return true;
        } catch (PDOException $error) {
            echo json_encode(["error" => "Unable to populate the database", "details" => $error->getMessage()]);
            return false;
        }
    }


    public function getUnreadNotifications($recipientId)
    {
        try {
            $sql = "SELECT * FROM notifications WHERE recipient_id=:recipient_id AND is_read=FALSE";
            $stmt = $this->Connection()->prepare($sql);
            $stmt->execute([':recipient_id' => $recipientId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $err) {
            echo json_encode(["error" => "Failed to retrieve unread notifications", "details" => $err->getMessage()]);
            return false;
        }
    }


    public function getNotificationIds($userId)
    {
        try {
            $sql = "SELECT notification_id FROM notifications WHERE user_id = :user_id";
            $stmt = $this->Connection()->prepare($sql);
            $stmt->execute([':user_id' => $userId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC); // Fetch only IDs as an array
        } catch (PDOException $err) {
            echo "Error: Failed to retrieve notification IDs for user ID {$userId} - " . $err->getMessage();
            return false;
        }
    }

    public function getRecipientId($userId)
    {
        try {
            $sql = "SELECT recipient_id FROM notifications WHERE user_id = :user_id";
            $stmt = $this->Connection()->prepare($sql);
            $stmt->execute([':user_id' => $userId]);
            return $stmt->fetchAll(PDO::FETCH_COLUMN); // Fetch only IDs as an array
        } catch (PDOException $err) {
            echo "Error: Failed to retrieve recipient_id for user ID {$userId} - " . $err->getMessage();
            return false;
        }
    }



    public function markAsRead($notificationId)
    {
        try {
            $sql = "UPDATE notifications SET is_read = 1 WHERE notification_id = :notification_id";
            $stmt = $this->Connection()->prepare($sql);
            $stmt->execute([':notification_id' => $notificationId]);
            return true;
        } catch (PDOException $err) {
            echo "Error: Failed to mark notification ID {$notificationId} as read - " . $err->getMessage();
            return false;
        }
    }
}
