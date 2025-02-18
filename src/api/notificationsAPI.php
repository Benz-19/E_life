<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

include_once __DIR__ . "/../../vendor/autoload.php";
include_once __DIR__ . "/../classes/Models/notification.model.php";

// Initialize database and notification model
$database = new Database();
$db = $database->Connection();
$notification = new Notification();

// Get the request method
$method = $_SERVER["REQUEST_METHOD"];

// Handle different API requests
switch ($method) {
    case "POST":
        // Add a new notification
        $data = json_decode(file_get_contents("php://input"), true);
        if (!isset($data['user_id'], $data['message'], $data['notification_type'])) {
            echo json_encode(["error" => "Missing required fields"]);
            http_response_code(400);
            exit;
        }

        $userId = $data['user_id'];
        $message = $data['message'];
        $notificationType = $data['notification_type'];

        if ($notification->addNotification($userId, $message, $notificationType)) {
            echo json_encode(["message" => "Notification added successfully"]);
            http_response_code(201);
        } else {
            echo json_encode(["error" => "Failed to add notification"]);
            http_response_code(500);
        }
        break;

    case "GET":
        // Fetch unread notifications
        if (!isset($_GET["user_id"])) {
            echo json_encode(["error" => "User ID is required"]);
            http_response_code(400);
            exit;
        }

        $userId = $_GET["user_id"];
        $unreadNotifications = $notification->getUnreadNotifications($userId);

        if ($unreadNotifications) {
            echo json_encode(["notifications" => $unreadNotifications]);
            http_response_code(200);
        } else {
            echo json_encode(["message" => "No unread notifications found"]);
            http_response_code(404);
        }
        break;

    case "PUT":
        // Mark notification as read
        parse_str(file_get_contents("php://input"), $data);

        if (!isset($data["notification_id"])) {
            echo json_encode(["error" => "Notification ID is required"]);
            http_response_code(400);
            exit;
        }

        $notificationId = $data["notification_id"];

        if ($notification->markAsRead($notificationId)) {
            echo json_encode(["message" => "Notification marked as read"]);
            http_response_code(200);
        } else {
            echo json_encode(["error" => "Failed to mark notification as read"]);
            http_response_code(500);
        }
        break;

    default:
        echo json_encode(["error" => "Method not allowed"]);
        http_response_code(405);
        break;
}
