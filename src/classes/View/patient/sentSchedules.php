<?php
session_start();
require_once __DIR__ . '/../../../../vendor/autoload.php';
$patient = new User();
$sentSchedules = $patient->getUserEstablishedSchedule($_SESSION['user_id']);
$patient_email = $patient->getUserDetails($_SESSION['user_id'])['email'];
if (empty($sentSchedules)) {
    $is_empty = true;
}
?>

<!DOCTYPE html>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://kit.fontawesome.com/YOUR_FONTAWESOME_KIT.js" crossorigin="anonymous"></script>
    <title>Document</title>
</head>

<body>
    <div>
        <div class="p-4 bg-gray-200 rounded-lg flex justify-start mx-4">
            <button type="submit" name="schedule" class="bg-red-500 text-white px-4 py-2 rounded-lg">
                <a href="schedule.php">Return To Schedules</a>
            </button>
        </div>

        <h2 class="text-xl font-semibold text-center my-10">Received Schedules</h2>
        <table class="w-full bg-white border rounded-lg shadow">
            <?php if (isset($is_empty)): ?>
                <tr>
                    <td colspan="8" class="text-center p-4 font-bold text-xl text-red-500">No schedules found!</td>
                </tr>
            <?php else: ?>
                <thead>
                    <tr class="bg-gray-200">
                        <th class="p-2">Appointment_Id</th>
                        <th class="p-2">Patient_Id</th>
                        <th class="p-2">Doctor_Id</th>
                        <th class="p-2">Date</th>
                        <th class="p-2">Time</th>
                        <th class="p-2">Description</th>
                        <th class="p-2">Sender</th>
                        <th class="p-2">Receiver</th>
                    </tr>
                </thead>
                <tbody id="patientList">
                    <?php foreach ($sentSchedules as $sentSchedule): ?>
                        <?php if ($sentSchedule['sender'] === $patient_email): ?>
                            <tr class="border-t">
                                <td class="p-4 text-center"> <?= htmlspecialchars($sentSchedule['appointment_id']) ?> </td>
                                <td class="p-4  text-center"> <?= htmlspecialchars($sentSchedule['patient_id']) ?> </td>
                                <td class="p-4  text-center"> <?= htmlspecialchars($sentSchedule['doctor_id']) ?> </td>
                                <td class="p-4  text-center"> <?= htmlspecialchars($sentSchedule['date']) ?> </td>
                                <td class="p-4  text-center"> <?= htmlspecialchars($sentSchedule['time']) ?> </td>
                                <td class="p-4  text-center"> <?= htmlspecialchars($sentSchedule['description']) ?> </td>
                                <td class="p-4  text-center"> <?= htmlspecialchars($sentSchedule['sender']) ?> </td>
                                <td class="p-4  text-center"> <?= htmlspecialchars($sentSchedule['receiver']) ?> </td>

                            </tr>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </tbody>
            <?php endif; ?>
        </table>
    </div>

</body>

</html>