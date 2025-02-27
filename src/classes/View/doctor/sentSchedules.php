<?php
session_start();
require_once __DIR__ . '/../../../../vendor/autoload.php';
$doctor = new Doctor();
$doctor_email = $doctor->getUserDetails($_SESSION['user_id'])['email'];
$schedules = $doctor->getSentSchedules($doctor_email);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://kit.fontawesome.com/YOUR_FONTAWESOME_KIT.js" crossorigin="anonymous"></script>
    <title>Sent Schedules</title>
</head>

<body class="bg-gray-100 p-6">
    <div class="max-w-4xl mx-auto">
        <div class="mb-6">
            <button class="bg-red-500 text-white px-4 py-2 rounded-lg">
                <a href="schedule.php">Return To Schedules</a>
            </button>
        </div>

        <h2 class="text-2xl font-semibold text-center mb-6">Sent Schedules</h2>

        <?php if (!empty($schedules)): ?>
            <?php foreach ($schedules as $schedule): ?>
                <?php if ($schedule['sender'] === $doctor_email): ?>
                    <div class="bg-white p-6 rounded-lg shadow-md mb-4">
                        <h3 class="text-lg font-bold text-blue-600 mb-2">
                            Appointment ID: <?= htmlspecialchars($schedule['appointment_id']) ?>
                        </h3>

                        <div class="flex justify-between text-gray-700 mb-2">
                            <p><strong>Patient ID:</strong> <?= htmlspecialchars($schedule['patient_id']) ?></p>
                            <p><strong>Doctor ID:</strong> <?= htmlspecialchars($schedule['doctor_id']) ?></p>
                        </div>

                        <div class="flex justify-between text-gray-700 mb-4">
                            <p><strong>Date:</strong> <?= htmlspecialchars($schedule['date']) ?></p>
                            <p><strong>Time:</strong> <?= htmlspecialchars($schedule['time']) ?></p>
                        </div>

                        <div class="bg-gray-100 p-4 rounded-lg">
                            <p class="text-gray-700"><strong>Message:</strong></p>
                            <p class="text-gray-900"> <?= htmlspecialchars($schedule['description']) ?> </p>
                        </div>

                        <div class="flex justify-between text-sm text-gray-600 mt-4">
                            <p><strong>From:</strong> <?= htmlspecialchars($schedule['sender']) ?></p>
                            <p><strong>To:</strong> <?= htmlspecialchars($schedule['receiver']) ?></p>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-center text-gray-500">No sent schedules.</p>
        <?php endif; ?>
    </div>
</body>

</html>