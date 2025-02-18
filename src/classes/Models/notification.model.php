<?php

include_once __DIR__ . "/../../../vendor/autoload.php";

class Notification extends Database
{

    public function addNotification($userId, $message, $type)
    {

        try {
            $sql = "INSERT INTO notifications (user_id, message, type) VALUES (:user_id, :message, :type)";
            $stmt = $this->Connection()->prepare($sql);
            $stmt->execute([
                ':user_id' => $userId,
                ':message' => $message,
                ':type' => $type
            ]);

            return true;
        } catch (PDOException $error) {
            echo "Error: Unable to populate the database" . $error->getMessage();
            return false;
        }
    }

    public function getUnreadNotification($userId)
    {
        try {
            $sql = "SELECT * FROM notifications WHERE user_id=:user_id";
            $stmt = $this->Connection()->prepare($sql);
            $stmt->execute([':user_id' => $userId]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $err) {
            echo "Error: Failed to retrieve unread notifications for the user with ID = {$userId}\n" . $err->getMessage();
            return false;
        }
    }

    public function getNotificationId($userId)
    {
        try {
            $sql = "SELECT notification_id FROM notifications WHERE user_id=:user_id";
            $stmt = $this->Connection()->prepare($sql);
            $stmt->execute([':user_id' => $userId]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $err) {
            echo "Error: Failed to retrieve the notification_id for the user with ID = {$userId}\n" . $err->getMessage();
        }
    }

    public function markAsRead($notification_id)
    {
        try {
            $is_read = "True";
            $sql = "UPDATE notifications SET is_read = :is_read WHERE notification_id = :notification_id";
            $stmt = $this->Connection()->prepare($sql);
            $stmt->execute([
                ':is_read' => $is_read,
                ':notification_id' => $notification_id
            ]);
            return true;
        } catch (PDOException $err) {
            echo "Error: Failed to mark the message with ID = {$notification_id} as read!\n" . $err->getMessage();
        }
    }
}
