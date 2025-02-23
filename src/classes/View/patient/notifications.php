<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . "/../../../../vendor/autoload.php";
require_once __DIR__ . "/../../Models/notification.model.php";
if (!isset($_SESSION['patient-login'])) {
    header('Location: index.php');
    exit();
}

$userNotification = new Notification();
$notifications = $userNotification->getUnreadNotifications(0);

$users = new User();
$users = $users->getAllUsers();
$index = 0;


?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        #notification-cont {
            cursor: pointer;
            transition: all 0.3s ease-in-out;
        }

        #notification-cont:hover {
            background-color: #f7f7f7;
        }
    </style>
</head>

<body class="bg-gray-100 h-screen flex flex-col">
    <div id="main-cont" class="w-full max-w-md mx-auto bg-white shadow-lg rounded-lg flex flex-col h-full relative">
        <div class="flex justify-between items-center p-4 border-b">
            <p class="font-semibold text-lg">Notifications</p>
            <a href="index.php" class="text-blue-500">Back</a>
        </div>
        <div class="overflow-y-auto" id="notification-cont">
            <?php if ($notifications): ?>
                <?php while ($index != count($users)): ?>
                    <?php foreach ($notifications as $notification): ?>
                        <?php if ($notification['user_id'] === $users[$index]['user_id']): ?>
                            <div class="p-4 border-b flex items-center">
                                <div class="w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div class="ml-2">
                                    <p class="font-semibold"><?php echo htmlspecialchars($users[$index]['name']); ?></p>
                                    <p class="text-sm text-gray-600"><?php echo htmlspecialchars($notification['notification_type']); ?></p>
                                </div>
                            </div>
                            <?php break; ?>
                        <?php endif; ?>
                    <?php endforeach; ?>
                    <?php $index++; ?>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="p-4 text-center text-gray-500">
                    No notifications available.
                </div>
            <?php endif; ?>
        </div>
</body>

</html>