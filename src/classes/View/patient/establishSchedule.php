<?php
session_start();

require_once __DIR__ . '/../../../../vendor/autoload.php';
require_once __DIR__ . '/../../../handle_error/handle_error.php';

$doctor = new Doctor();
$doctor_name =  $doctor->getUserDetails($_GET['id'])['name'];

if (isset($_POST['schedule'])) {
    $doctor_id = $_POST['doctor_id'];
    $patient_id = $_SESSION['user_id'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $details = $_POST['details'];
    if (empty($date) || empty($time) || empty($details)) {
        echo "<script>alert('Please fill all fields')</script>";
    } else {
        $doctor = new Doctor();
        $patient = new User();
        $sender = $patient->getUserDetails($patient_id)['email'];
        $receiver = $doctor->getUserDetails($doctor_id)['email'];
        $doctor_name =  $doctor->getUserDetails($doctor_id)['name'];
        $doctor->establishSchedule($patient_id, $doctor_id, $date, $time, $details, $sender, $receiver);
        success_message('Schedule established successfully');
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Schedule Appointments</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 p-6">
    <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow-lg">
        <h2 class="text-2xl font-semibold text-gray-800 mb-4">Schedule Appointment with Dr. <span class="text-red-500"><?php echo $doctor_name; ?> </span></h2>

        <!-- Appointment Form -->
        <form action="" method="post" id="scheduleForm" class="mb-6">
            <input type="hidden" name="doctor_id" value="<?php echo $_GET['id']; ?>">
            <div class="mb-4">
                <label class="block text-gray-700 font-medium" for="date">Select Date</label>
                <input type="date" id="date" name="date" class="w-full p-2 border rounded-lg">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-medium" for="time">Select Time</label>
                <input type="time" id="time" name="time" class="w-full p-2 border rounded-lg">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-medium" for="details">Appointment Details</label>
                <textarea id="details" name="details" class="w-full p-2 border rounded-lg" rows="3"></textarea>
            </div>

            <button type="submit" name="schedule" class="bg-blue-500 text-white px-4 py-2 rounded-lg">Schedule</button>
        </form>

        <!-- Return to Appointments Page -->
        <div>
            <h3 class="text-xl font-semibold text-gray-800 mb-3">Return to Appointments</h3>
            <div id="appointmentList" class="space-y-3">
                <div class="p-4 bg-gray-200 rounded-lg">
                    <button type="submit" name="schedule" class="bg-red-500 text-white px-4 py-2 rounded-lg">
                        <a href="schedule.php">return</a>
                    </button>
                </div>
            </div>
        </div>
    </div>
</body>

</html>