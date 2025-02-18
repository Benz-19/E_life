<?php

include_once __DIR__ . "/../../../vendor/autoload.php";

class Notification extends Database
{

    public function addNotification($userId, $message, $notificationType)
    {
        try {
            $sql = "INSERT INTO notifications (user_id, message, notification_type) 
                    VALUES (:user_id, :message, :notification_type)";
            $stmt = $this->Connection()->prepare($sql);
            $stmt->execute([
                ':user_id' => $userId,
                ':message' => $message,
                ':notification_type' => $notificationType
            ]);

            return true;
        } catch (PDOException $error) {
            echo "Error: Unable to insert notification - " . $error->getMessage();
            return false;
        }
    }

    public function getUnreadNotifications($userId)
    {
        try {
            $sql = "SELECT * FROM notifications WHERE user_id = :user_id AND is_read = 0 ORDER BY created_at DESC";
            $stmt = $this->Connection()->prepare($sql);
            $stmt->execute([':user_id' => $userId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $err) {
            echo "Error: Failed to retrieve unread notifications for user ID {$userId} - " . $err->getMessage();
            return false;
        }
    }

    public function getNotificationIds($userId)
    {
        try {
            $sql = "SELECT notification_id FROM notifications WHERE user_id = :user_id";
            $stmt = $this->Connection()->prepare($sql);
            $stmt->execute([':user_id' => $userId]);
            return $stmt->fetchAll(PDO::FETCH_COLUMN); // Fetch only IDs as an array
        } catch (PDOException $err) {
            echo "Error: Failed to retrieve notification IDs for user ID {$userId} - " . $err->getMessage();
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
