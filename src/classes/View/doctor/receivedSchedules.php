<?php
session_start();
require_once __DIR__ . '/../../../../vendor/autoload.php';
$doctor = new Doctor();
$doctor_email = $doctor->getUserDetails($_SESSION['user_id'])['email'];
$doctor_id = $doctor->getUserDetails($_SESSION['user_id'])['user_id'];
$receivedSchedules = $doctor->getReceivedSchedules($doctor_id);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://kit.fontawesome.com/YOUR_FONTAWESOME_KIT.js" crossorigin="anonymous"></script>
    <title>Received Schedules</title>
</head>

<body class="bg-gray-100 p-6">

    <div class="max-w-4xl mx-auto">
        <div class="mb-6">
            <button class="bg-red-500 text-white px-4 py-2 rounded-lg">
                <a href="schedule.php">Return To Schedules</a>
            </button>
        </div>

        <h2 class="text-2xl font-semibold text-center mb-6">Received Schedules</h2>

        <?php if (!empty($receivedSchedules)): ?>
            <?php foreach ($receivedSchedules as $receivedSchedule): ?>
                <?php if ($receivedSchedule['receiver'] === $doctor_email): ?>
                    <div class="bg-white p-6 rounded-lg shadow-md mb-4">
                        <!-- Appointment ID -->
                        <h3 class="text-lg font-bold text-blue-600 mb-2">
                            Appointment ID: <?= htmlspecialchars($receivedSchedule['appointment_id']) ?>
                        </h3>

                        <!-- Patient & Doctor IDs -->
                        <div class="flex justify-between text-gray-700 mb-2">
                            <p><strong>Patient ID:</strong> <?= htmlspecialchars($receivedSchedule['patient_id']) ?></p>
                            <p><strong>Doctor ID:</strong> <?= htmlspecialchars($receivedSchedule['doctor_id']) ?></p>
                        </div>

                        <!-- Date & Time -->
                        <div class="flex justify-between text-gray-700 mb-4">
                            <p><strong>Date:</strong> <?= htmlspecialchars($receivedSchedule['date']) ?></p>
                            <p><strong>Time:</strong> <?= htmlspecialchars($receivedSchedule['time']) ?></p>
                        </div>

                        <!-- Message / Description -->
                        <div class="bg-gray-100 p-4 rounded-lg">
                            <p class="text-gray-700"><strong>Message:</strong></p>
                            <p class="text-gray-900"><?= htmlspecialchars($receivedSchedule['description']) ?></p>
                        </div>

                        <!-- Sender & Receiver -->
                        <div class="flex justify-between text-sm text-gray-600 mt-4">
                            <p><strong>From:</strong> <?= htmlspecialchars($receivedSchedule['sender']) ?></p>
                            <p><strong>To:</strong> <?= htmlspecialchars($receivedSchedule['receiver']) ?></p>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-center text-gray-500">No received schedules.</p>
        <?php endif; ?>

    </div>

</body>

</html>