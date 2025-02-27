<?php
session_start();
require_once __DIR__ . '/../../../../vendor/autoload.php';
$doctor = new Doctor();
$doctor_email = $doctor->getUserDetails($_SESSION['user_id'])['email'];
$doctor_id = $_SESSION['user_id'];
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
    <title>Document</title>
</head>

<body>
    <div>
        <div class="p-4 bg-gray-200 rounded-lg flex justify-start mx-4">
            <button type="submit" name="schedule" class="bg-red-500 text-white px-4 py-2 rounded-lg">
                <a href="schedule.php">Return To Schedules</a>
            </button>
        </div>

        <h2 class="text-xl font-semibold text-center my-10">Sent Schedules</h2>
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
                <?php foreach ($schedules as $schedule): ?>
                    <?php if ($schedule['sender'] === $doctor_email): ?>
                        <tr class="border-t">
                            <td class="p-4 text-center"> <?= htmlspecialchars($schedule['appointment_id']) ?> </td>
                            <td class="p-4  text-center"> <?= htmlspecialchars($schedule['patient_id']) ?> </td>
                            <td class="p-4  text-center"> <?= htmlspecialchars($schedule['doctor_id']) ?> </td>
                            <td class="p-4  text-center"> <?= htmlspecialchars($schedule['date']) ?> </td>
                            <td class="p-4  text-center"> <?= htmlspecialchars($schedule['time']) ?> </td>
                            <td class="p-4  text-center"> <?= htmlspecialchars($schedule['description']) ?> </td>
                            <td class="p-4  text-center"> <?= htmlspecialchars($schedule['sender']) ?> </td>
                            <td class="p-4  text-center"> <?= htmlspecialchars($schedule['receiver']) ?> </td>
                            <!-- <td>
                                <button class="bg-blue-500 text-white px-4 py-2 rounded-lg">
                                    <a href="establishSchedule.php?id=<?php echo $schedule['user_id']; ?>">Schedule</a>
                                </button>
                            </td> -->
                        </tr>
                    <?php endif; ?>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>

</html>