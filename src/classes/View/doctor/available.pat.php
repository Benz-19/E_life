<?php
session_start();
include_once __DIR__ . "/../../../handle_error/handle_error.php";
require __DIR__ . "/../../../../vendor/autoload.php";
require __DIR__ . "/../../Models/userState.model.php";

if (!isset($_SESSION["doctorEmail"])) {
    echo handle_error("Failed to provide more details.") . "<br>" . handle_error("Logging you out...");
    echo "<script>setTimeout(() => { window.location.href = 'index.doctor.php'; }, 10000);</script>";
} else {
    $loggedIn = new loggedInUser;

    $users = [];
    try {
        $users = $loggedIn->getLoggedInUsers(); // Fetch logged-in users from the database
    } catch (Exception $e) {
        echo handle_error("Failed to fetch logged-in users.") . "<br>" . $e->getMessage();
    }
}

// Preventing return to the previous page.
echo '    <script type="text/javascript">
    function preventBack(){window.history.forward()};
    setTimeout("preventBack()", 0);
    window.onunload = function(){null;}
</script>
';
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Users</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .online-dot {
            background-color: #34d399;
        }

        .offline-dot {
            background-color: #d1d5db;
        }

        .disabled {
            pointer-events: none;
            opacity: 0.6;
        }
    </style>
</head>

<body class="bg-gray-100">
    <div class="max-w-2xl mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4 text-gray-800">Available patients</h1>
        <div class="bg-white shadow rounded-lg">
            <?php if (!empty($users)) : ?>
                <ul class="divide-y divide-gray-200">
                    <?php foreach ($users as $user) : ?>
                        <?php if ($user["user_type"] === "patient") : ?>
                            <li class="flex items-center p-4 hover:bg-gray-50 cursor-pointer <?php echo $user['presence'] === 'busy' ? 'disabled' : ''; ?>"
                                onclick="handleSelectUser(event, '<?php echo $user['user_id']; ?>', '<?php echo $user['presence']; ?>')">
                                <img src="../../../../public/images/user.png" alt="User Avatar" class="w-12 h-12 rounded-full mr-4">
                                <div class="flex-grow">
                                    <div class="text-lg font-medium text-gray-800"><?php echo $user['user_name']; ?></div>
                                    <div class="text-sm text-gray-500">ID: <?php echo $user['user_id']; ?> | Type:
                                        <?php echo ucfirst($user['user_type']); ?>
                                    </div>
                                    <?php if ($user["presence"] === "busy"): ?>
                                        <div class="text-sm text-red-500 font-semibold mt-1">Patient is currently busy with another doctor. Please try again later.</div>
                                    <?php endif; ?>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <?php if ($user["presence"] === "available"): ?>
                                        <span class="text-sm text-green-500"><?php echo $user['presence']; ?></span>
                                    <?php endif ?>
                                    <?php if ($user["presence"] === "busy"): ?>
                                        <span class="text-sm text-red-500"><?php echo $user['presence']; ?></span>
                                    <?php endif ?>
                                </div>
                            </li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </ul>
            <?php else : ?>
                <div class="p-4 text-center text-gray-500">
                    No users are currently online.
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        function handleSelectUser(event, userId, presence) {
            if (presence === 'busy') {
                event.preventDefault();
                alert("The Patient is currently busy with another doctor. Please try again later.");
                return;
            }
            window.location.href = `./message.doctor.php?user_id=${userId}`;
        }
    </script>
</body>

</html>