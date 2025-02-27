<?php
session_start();
require_once __DIR__ . '/../../../../vendor/autoload.php';
$patient = new User();
$receivedSchedules = $patient->getUserEstablishedSchedule($_SESSION['user_id']);
$patient_email = $patient->getUserDetails($_SESSION['user_id'])['email'];
echo $patient_email;
if (empty($receivedSchedules)) {
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
                <?php foreach ($receivedSchedules as $receivedSchedule): ?>
                    <?php echo $receivedSchedule['receiver'] . "<br>"; ?>
                    <?php if ($receivedSchedule['receiver'] === $patient_email): ?>
                        <tr class="border-t">
                            <td class="p-4 text-center"> <?= htmlspecialchars($receivedSchedule['appointment_id']) ?> </td>
                            <td class="p-4  text-center"> <?= htmlspecialchars($receivedSchedule['patient_id']) ?> </td>
                            <td class="p-4  text-center"> <?= htmlspecialchars($receivedSchedule['doctor_id']) ?> </td>
                            <td class="p-4  text-center"> <?= htmlspecialchars($receivedSchedule['date']) ?> </td>
                            <td class="p-4  text-center"> <?= htmlspecialchars($receivedSchedule['time']) ?> </td>
                            <td class="p-4  text-center"> <?= htmlspecialchars($receivedSchedule['description']) ?> </td>
                            <td class="p-4  text-center"> <?= htmlspecialchars($receivedSchedule['sender']) ?> </td>
                            <td class="p-4  text-center"> <?= htmlspecialchars($receivedSchedule['receiver']) ?> </td>
                            <?php $is_empty = false; ?>
                        </tr>
                    <?php endif; ?>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</body>

</html>